<?php

namespace App\Services;

use App\Models\WithdrawalRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalApprovedMail;
use App\Mail\WithdrawalRejectedMail;

class WithdrawalProcessingService
{
    private $blockchainService;

    public function __construct(BlockchainWithdrawalService $blockchainService)
    {
        $this->blockchainService = $blockchainService;
    }

    /**
     * Process single withdrawal request
     */
    public function processWithdrawal(WithdrawalRequest $withdrawal, $adminNote = null)
    {
        return DB::transaction(function () use ($withdrawal, $adminNote) {
            try {
                // Update withdrawal status to processing first
                $withdrawal->update([
                    'status' => 'processing',
                    'admin_note' => $adminNote ?? 'Processing withdrawal...',
                ]);

                // Process via blockchain
                $blockchainResult = $this->blockchainService->processWithdrawal(
                    $withdrawal->usdt_address,
                    $withdrawal->net_amount
                );

                if (!$blockchainResult['success']) {
                    throw new \Exception('Blockchain transfer failed: ' . ($blockchainResult['error'] ?? 'Unknown error'));
                }

                // Complete the withdrawal
                return $this->completeWithdrawal($withdrawal, $blockchainResult, $adminNote);
                
            } catch (\Exception $e) {
                // Mark as failed
                $withdrawal->update([
                    'status' => 'failed',
                    'admin_note' => 'Processing failed: ' . $e->getMessage(),
                ]);

                Log::error('Withdrawal processing failed: ' . $e->getMessage(), [
                    'withdrawal_id' => $withdrawal->id,
                    'user_id' => $withdrawal->user_id
                ]);

                throw $e;
            }
        });
    }

    /**
     * Complete withdrawal after successful blockchain transfer
     */
    private function completeWithdrawal(WithdrawalRequest $withdrawal, $blockchainResult, $adminNote = null)
    {
        $user = $withdrawal->user;
        $wallet = $user->wallet;

        // Update withdrawal status
        $withdrawal->update([
            'status' => 'completed',
            'processed_at' => now(),
            'admin_note' => $adminNote ?? 'Withdrawal processed successfully.',
            'tx_hash' => $blockchainResult['txHash'] ?? null,
        ]);

        // Deduct from wallet using the same logic as user service
        if ($wallet) {
            $totalDeduction = $withdrawal->amount;
            $remainingDeduction = $totalDeduction;
            
            // Deduct from different balances in order (same as user service)
            if ($wallet->referral_balance > 0) {
                $deductFromReferral = min($wallet->referral_balance, $remainingDeduction);
                $wallet->referral_balance -= $deductFromReferral;
                $remainingDeduction -= $deductFromReferral;
            }
            
            if ($remainingDeduction > 0 && $wallet->earning_balance > 0) {
                $deductFromEarning = min($wallet->earning_balance, $remainingDeduction);
                $wallet->earning_balance -= $deductFromEarning;
                $remainingDeduction -= $deductFromEarning;
            }
            
            if ($remainingDeduction > 0 && $wallet->deposit_balance > 0) {
                $deductFromDeposit = min($wallet->deposit_balance, $remainingDeduction);
                $wallet->deposit_balance -= $deductFromDeposit;
                $remainingDeduction -= $deductFromDeposit;
            }
            
            $wallet->total_withdrawn += $withdrawal->net_amount;
            $wallet->save();
        }

        // Update transaction statuses
        Transaction::where('user_id', $user->id)
                  ->where('txn_type', 'withdraw')
                  ->where('amount', -$withdrawal->amount)
                  ->where('status', 'pending')
                  ->update(['status' => 'completed']);
        
        Transaction::where('user_id', $user->id)
                  ->where('txn_type', 'withdrawal_fee')
                  ->where('amount', -$withdrawal->fee)
                  ->where('status', 'pending')
                  ->update(['status' => 'completed']);

        // Create notification for user
        Notification::create([
            'user_id' => $withdrawal->user_id,
            'title' => 'Withdrawal Approved',
            'message' => 'Your withdrawal request of ' . $withdrawal->amount . ' USDT has been approved and processed. Transaction Hash: ' . ($blockchainResult['txHash'] ?? 'Pending'),
            'type' => 'success'
        ]);

        // Send approval email (queued)
        try {
            Mail::to($withdrawal->user->email)
                ->queue(new WithdrawalApprovedMail($withdrawal));
        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal approval email: ' . $e->getMessage());
        }

        return [
            'success' => true,
            'withdrawal' => $withdrawal,
            'txHash' => $blockchainResult['txHash'] ?? null
        ];
    }

    /**
     * Process multiple withdrawals efficiently with error handling
     */
    public function processBulkWithdrawals($withdrawalIds, $adminNote = null)
    {
        $results = [
            'processed' => 0,
            'failed' => 0,
            'successful' => [],
            'failed_items' => []
        ];

        $withdrawals = WithdrawalRequest::with('user')
            ->whereIn('id', $withdrawalIds)
            ->where('status', 'pending')
            ->get();

        foreach ($withdrawals as $withdrawal) {
            try {
                $result = $this->processWithdrawal($withdrawal, $adminNote);
                
                $results['processed']++;
                $results['successful'][] = [
                    'id' => $withdrawal->id,
                    'user' => $withdrawal->user->name,
                    'amount' => $withdrawal->net_amount,
                    'txHash' => $result['txHash'] ?? null
                ];

                // Small delay to avoid overwhelming the blockchain service
                usleep(500000); // 0.5 seconds

            } catch (\Exception $e) {
                $results['failed']++;
                $results['failed_items'][] = [
                    'id' => $withdrawal->id,
                    'user' => $withdrawal->user->name,
                    'amount' => $withdrawal->net_amount,
                    'error' => $e->getMessage()
                ];

                Log::error('Bulk withdrawal processing failed for ID ' . $withdrawal->id . ': ' . $e->getMessage());
                
                // Continue with next withdrawal even if one fails
                continue;
            }
        }

        return $results;
    }

    /**
     * Reject withdrawal request
     */
    public function rejectWithdrawal(WithdrawalRequest $withdrawal, $adminNote)
    {
        return DB::transaction(function () use ($withdrawal, $adminNote) {
            $withdrawal->update([
                'status' => 'rejected',
                'admin_note' => $adminNote,
                'processed_at' => now(),
            ]);

            // Update transaction statuses to cancelled
            Transaction::where('user_id', $withdrawal->user_id)
                      ->where('txn_type', 'withdraw')
                      ->where('amount', -$withdrawal->amount)
                      ->where('status', 'pending')
                      ->update(['status' => 'cancelled']);
            
            Transaction::where('user_id', $withdrawal->user_id)
                      ->where('txn_type', 'withdrawal_fee')
                      ->where('amount', -$withdrawal->fee)
                      ->where('status', 'pending')
                      ->update(['status' => 'cancelled']);

            // Create notification for user
            Notification::create([
                'user_id' => $withdrawal->user_id,
                'title' => 'Withdrawal Rejected',
                'message' => 'Your withdrawal request has been rejected. Reason: ' . $adminNote,
                'type' => 'error'
            ]);

            // Send rejection email (queued)
            try {
                Mail::to($withdrawal->user->email)
                    ->queue(new WithdrawalRejectedMail($withdrawal, $adminNote));
            } catch (\Exception $e) {
                Log::error('Failed to send withdrawal rejection email: ' . $e->getMessage());
            }

            return true;
        });
    }

    /**
     * Reject multiple withdrawals
     */
    public function rejectBulkWithdrawals($withdrawalIds, $adminNote)
    {
        $results = [
            'processed' => 0,
            'failed' => 0
        ];

        $withdrawals = WithdrawalRequest::with('user')
            ->whereIn('id', $withdrawalIds)
            ->where('status', 'pending')
            ->get();

        foreach ($withdrawals as $withdrawal) {
            try {
                $this->rejectWithdrawal($withdrawal, $adminNote);
                $results['processed']++;
            } catch (\Exception $e) {
                $results['failed']++;
                Log::error('Bulk withdrawal rejection failed for ID ' . $withdrawal->id . ': ' . $e->getMessage());
                continue;
            }
        }

        return $results;
    }

    /**
     * Check blockchain service status
     */
    public function getBlockchainStatus()
    {
        return $this->blockchainService->getServiceStatus();
    }

    /**
     * Check if blockchain service is available
     */
    public function isBlockchainAvailable()
    {
        return $this->blockchainService->isServiceAvailable();
    }
}