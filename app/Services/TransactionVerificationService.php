<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\InvestmentPlan;
use App\Models\SystemSetting;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransactionVerificationService
{
    private $apiBaseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiBaseUrl = config('services.transaction_verifier.url', 'https://your-api-server.com/api');
        $this->apiKey = config('services.transaction_verifier.key', 'your-api-key');
    }

    /**
     * Verify transaction hash and process deposit
     */
    public function verifyAndProcessDeposit($userId, $amount, $txnHash, $planId = null)
    {
        try {
            $user = User::with('wallet')->find($userId);
            if (!$user) {
                return ['success' => false, 'message' => 'User not found'];
            }

            // Step 1: Verify transaction hash via API
            $verificationResult = $this->verifyTransactionHash($txnHash, $amount);
            
            if (!$verificationResult['success']) {
                return $verificationResult;
            }

            // Step 2: Check if transaction already processed
            if ($this->isTransactionProcessed($txnHash)) {
                return ['success' => false, 'message' => 'Transaction already processed'];
            }

            // Step 3: Check level-based restrictions
            $levelCheck = WalletService::checkDepositLimitByLevel($user, $amount);
            if (!$levelCheck['success']) {
                return $levelCheck;
            }

            // Step 4: Process the deposit
            return $this->processVerifiedDeposit($user, $amount, $txnHash, $planId, $verificationResult['data']);

        } catch (\Exception $e) {
            Log::error('Transaction verification failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Transaction processing failed: ' . $e->getMessage()];
        }
    }

    /**
     * Verify transaction hash with external API
     */
    private function verifyTransactionHash($txnHash, $amount)
    {
        try {
            // For now, we'll fake the API response - replace with actual API call later
            $fakeApiResponse = $this->fakeTransactionVerification($txnHash, $amount);
            
            // Actual API implementation (commented out for now)
            /*
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiBaseUrl . '/verify-transaction', [
                'txn_hash' => $txnHash,
                'amount' => $amount,
                'currency' => 'USDT',
                'network' => 'BEP20'
            ]);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Transaction verification service unavailable'
                ];
            }

            $apiData = $response->json();
            */
            
            $apiData = $fakeApiResponse;

            if ($apiData['status'] === 'success') {
                return [
                    'success' => true,
                    'message' => 'Transaction verified successfully',
                    'data' => $apiData['data']
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $apiData['message'] ?? 'Transaction verification failed'
                ];
            }

        } catch (\Exception $e) {
            Log::error('API Verification Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Transaction verification service error'
            ];
        }
    }

    /**
     * Fake transaction verification - replace with real API
     */
    private function fakeTransactionVerification($txnHash, $amount)
    {
        // Simulate API delay
        sleep(2);

        // Basic validation of transaction hash format
        // if (!preg_match('/^0x[a-fA-F0-9]{64}$/', $txnHash)) {
        //     return [
        //         'status' => 'error',
        //         'message' => 'Invalid transaction hash format'
        //     ];
        // }

        // Simulate successful verification
        return [
            'status' => 'success',
            'message' => 'Transaction verified successfully',
            'data' => [
                'txn_hash' => $txnHash,
                'amount' => $amount,
                'confirmed' => true,
                'confirmations' => 15,
                'from_address' => '0x' . substr(md5(uniqid()), 0, 40),
                'to_address' => '0x742E4D6c4C8B6C4D8E6F7C5A3B2C1D0E9F8A7B6C',
                'block_number' => rand(20000000, 25000000),
                'timestamp' => now()->timestamp,
                'network' => 'BSC'
            ]
        ];
    }

    /**
     * Check if transaction was already processed
     */
    private function isTransactionProcessed($txnHash)
    {
        return Transaction::where('usdt_txn_hash', $txnHash)
                        ->whereIn('status', ['completed', 'pending'])
                        ->exists();
    }

    /**
     * Process verified deposit
     */
    private function processVerifiedDeposit($user, $amount, $txnHash, $planId, $verificationData)
{
    return DB::transaction(function () use ($user, $amount, $txnHash, $planId, $verificationData) {
        
        // USE WalletService INSTEAD OF MANUAL PROCESSING
        $depositResult = WalletService::deposit($user->id, $amount, $txnHash);
        
        if (!$depositResult['success']) {
            throw new \Exception($depositResult['message']);
        }

        // Update transaction details with verification data
        $transaction = Transaction::where('usdt_txn_hash', $txnHash)
                                ->where('user_id', $user->id)
                                ->first();
        
        if ($transaction) {
            $transaction->details = json_encode([
                'verified_data' => $verificationData,
                'plan_id' => $planId,
                'type' => 'BEP20 Deposit'
            ]);
            $transaction->save();
        }

        Log::info("Deposit processed successfully", [
            'user_id' => $user->id,
            'amount' => $amount,
            'txn_hash' => $txnHash
        ]);

        return [
            'success' => true,
            'message' => 'Deposit processed successfully',
            'transaction_id' => $transaction->txn_id,
            'wallet_balance' => $user->wallet->deposit_balance
        ];
    });
}

    /**
     * Handle user activation and level management
     */
    private function handleUserActivation($user, $amount)
    {
        $minActivation = SystemSetting::getValue('minimum_activation', 50);
        
        // Activate user if this is first sufficient deposit
        if ($user->activation_amount == 0 && $amount >= $minActivation) {
            $user->activation_amount = $amount;
            $user->activation_date = now();
            $user->status = 'active';
            
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Account Activated',
                'message' => "Your account has been successfully activated with {$amount} USDT",
                'type' => 'success'
            ]);
        }

        // Update user's total asset hold
        $user->total_asset_hold = $user->wallet->deposit_balance;
        
        // Check for level upgrade
        $this->checkAndUpgradeLevel($user);
        
        $user->save();
    }

    /**
     * Check and upgrade user level based on asset hold and referrals
     */
    private function checkAndUpgradeLevel($user)
    {
        $currentLevel = $user->current_level;
        $assetHold = $user->total_asset_hold;
        
        // Get the highest level the user qualifies for based on asset hold
        $qualifiedPlan = InvestmentPlan::where('asset_hold', '<=', $assetHold)
                                    ->where('status', 'active')
                                    ->orderBy('level', 'desc')
                                    ->first();

        if ($qualifiedPlan && $qualifiedPlan->level > $currentLevel) {
            // Check if user meets referral requirements for the new level
            if ($this->meetsLevelRequirements($user, $qualifiedPlan)) {
                $user->current_level = $qualifiedPlan->level;
                
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Level Upgraded!',
                    'message' => "Congratulations! You've been upgraded to Level {$qualifiedPlan->level}",
                    'type' => 'success'
                ]);

                Log::info("User level upgraded", [
                    'user_id' => $user->id,
                    'from_level' => $currentLevel,
                    'to_level' => $qualifiedPlan->level,
                    'asset_hold' => $assetHold
                ]);
            }
        }
    }

    /**
     * Check if user meets referral requirements for a plan
     */
    private function meetsLevelRequirements($user, $plan)
    {
        // Check direct referrals requirement
        if ($plan->direct_referrals_required) {
            $directReferrals = $user->referrals()->where('level_number', 1)->count();
            if ($directReferrals < $plan->direct_referrals_required) {
                return false;
            }
        }

        // Check indirect referrals requirement
        if ($plan->indirect_referrals_required) {
            $indirectReferrals = $user->referrals()->where('level_number', '>', 1)->count();
            if ($indirectReferrals < $plan->indirect_referrals_required) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus($txnHash)
    {
        $transaction = Transaction::where('usdt_txn_hash', $txnHash)->first();
        
        if (!$transaction) {
            return ['success' => false, 'message' => 'Transaction not found'];
        }

        return [
            'success' => true,
            'status' => $transaction->status,
            'amount' => $transaction->amount,
            'created_at' => $transaction->created_at,
            'user_id' => $transaction->user_id
        ];
    }
}