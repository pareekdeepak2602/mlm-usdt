<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\InvestmentPlan;
use App\Models\SystemSetting;
use App\Models\Notification;
use App\Utils\HmacGenerator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransactionVerificationService
{
    private $apiBaseUrl;
    private $apiKey;
    private $network;

    public function __construct()
    {
        $this->apiBaseUrl = 'https://api.smartchoice.org.in';
        $this->apiKey = config('services.transaction_verifier.key');
        $this->network = config('services.transaction_verifier.network', 'bsc_testnet');
    }

    /**
     * Verify transaction hash and process deposit using Node.js API
     */
    public function verifyAndProcessDeposit($userId, $amount, $txnHash, $planId = null)
    {
        try {
            $user = User::with('wallet')->find($userId);
            if (!$user) {
                return ['success' => false, 'message' => 'User not found'];
            }

            // Step 1: Verify transaction via Node.js API
            $verificationResult = $this->verifyTransactionWithNodeAPI($txnHash, $amount, $user);
            
            if (!$verificationResult['success']) {
                Log::error('Transaction verification failed', [
                    'user_id' => $userId,
                    'txn_hash' => $txnHash,
                    'amount' => $amount,
                    'error' => $verificationResult['message']
                ]);
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
     * Verify transaction using Node.js API with HMAC authentication
     */
  private function verifyTransactionWithNodeAPI($txnHash, $amount, $user)
{
    try {
        $requestData = [
            'txHash' => $txnHash,
            'toAddress' => $this->getCompanyWalletAddress(), // Company wallet
            'amount' => $amount,
            'userId' => $user->id
        ];

        // Generate timestamp (milliseconds)
        $timestamp = round(microtime(true) * 1000);

        // Load secrets from config (.env)
        $apiSecret = config('services.transaction_verifier.secret');
        $apiKey = config('services.transaction_verifier.key');
        $appToken = config('services.transaction_verifier.app_token');

        // ✅ Generate signature
        $signature = \App\Utils\HmacGenerator::generateSignature($requestData, $apiSecret, $timestamp);

        // ✅ Make secure request to Node.js API
        $response = \Illuminate\Support\Facades\Http::timeout(30)
            ->withHeaders([
                'x-api-key'    => $apiKey,
                'x-timestamp'  => $timestamp,
                'x-signature'  => $signature,
                'x-app-token'  => $appToken,
                'Content-Type' => 'application/json',
            ])
            ->post($this->apiBaseUrl . '/api/confirm-payment', $requestData);

        if (!$response->successful()) {
            \Illuminate\Support\Facades\Log::error('Node.js API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return ['success' => false, 'message' => 'Transaction verification service unavailable'];
        }

        $apiData = $response->json();

        if (isset($apiData['status']) && $apiData['status'] === 'success') {
            return [
                'success' => true,
                'message' => $apiData['message'] ?? 'Transaction verified successfully',
                'data' => $apiData['data'] ?? $apiData,
            ];
        }

        return [
            'success' => false,
            'message' => $apiData['message'] ?? 'Transaction verification failed',
        ];
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Node.js API Verification Error: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Transaction verification service error: ' . $e->getMessage(),
        ];
    }
}

    /**
     * Get company wallet address (you can store this in database or config)
     */
    private function getCompanyWalletAddress()
    {
        // You can store this in system settings or config
        return SystemSetting::getValue('company_usdt_wallet', '0x5CE2C945eeD9FBA974363fF028D86ed641b7b185');
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
            
            // Use WalletService for deposit processing
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
                    'network' => $verificationData['network'] ?? 'BSC',
                    'block_number' => $verificationData['blockNumber'] ?? null,
                    'type' => 'BEP20 Deposit',
                    'verified_at' => now()->toDateTimeString()
                ]);
                
                // Update status based on verification
                $transaction->status = 'completed';
                $transaction->save();

                // Update user's wallet and handle activation
                $this->handleUserActivation($user, $amount);
            }

            Log::info("Deposit processed successfully via Node.js API", [
                'user_id' => $user->id,
                'amount' => $amount,
                'txn_hash' => $txnHash,
                'verification_data' => $verificationData
            ]);

            return [
                'success' => true,
                'message' => 'Deposit processed successfully',
                'transaction_id' => $transaction->txn_id ?? null,
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
     * Get transaction status from both local DB and Node.js API
     */
    public function getTransactionStatus($txnHash)
    {
        $transaction = Transaction::where('usdt_txn_hash', $txnHash)->first();
        
        if (!$transaction) {
            return ['success' => false, 'message' => 'Transaction not found in local database'];
        }

        // Optionally verify with Node.js API for latest status
        $apiStatus = $this->verifyTransactionWithNodeAPI($txnHash, $transaction->amount, $transaction->user);

        return [
            'success' => true,
            'status' => $transaction->status,
            'amount' => $transaction->amount,
            'created_at' => $transaction->created_at,
            'user_id' => $transaction->user_id,
            'api_verification' => $apiStatus
        ];
    }

    /**
     * Get real-time transaction status from blockchain via Node.js API
     */
    public function getRealTimeTransactionStatus($txnHash)
    {
        $requestData = [
            'txHash' => $txnHash,
            'timestamp' => time()
        ];

        $signature = HmacGenerator::generateSignature($requestData, $this->apiKey);

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'X-Signature' => $signature,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiBaseUrl . '/api/transaction-status', $requestData);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to get real-time transaction status: ' . $e->getMessage());
        }

        return ['success' => false, 'message' => 'Unable to fetch real-time status'];
    }
}