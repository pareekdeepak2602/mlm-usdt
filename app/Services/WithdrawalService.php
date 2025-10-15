<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawalRequest;
use App\Models\Transaction;
use App\Models\SystemSetting;
use App\Models\Notification;

class WithdrawalService
{
    public static function requestWithdrawal($userId, $amount, $usdtAddress)
    {
        $user = User::find($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        $wallet = $user->wallet;
        if (!$wallet || $wallet->available_balance < $amount) {
            return ['success' => false, 'message' => 'Insufficient balance'];
        }
        
        $minWithdrawal = SystemSetting::getValue('minimum_withdrawal', 30);
        if ($amount < $minWithdrawal) {
            return ['success' => false, 'message' => "Minimum withdrawal amount is {$minWithdrawal} USDT"];
        }
        
        $feePercentage = SystemSetting::getValue('withdrawal_fee_percentage', 10);
        $fee = $amount * ($feePercentage / 100);
        $netAmount = $amount - $fee;
        
        // Create withdrawal request
        $withdrawal = WithdrawalRequest::create([
            'user_id' => $userId,
            'amount' => $amount,
            'fee' => $fee,
            'net_amount' => $netAmount,
            'usdt_address' => $usdtAddress,
            'status' => 'pending',
        ]);
        
        // Create transaction record
        Transaction::create([
            'user_id' => $userId,
            'txn_id' => Transaction::generateTxnId(),
            'txn_type' => 'withdraw',
            'amount' => -$amount,
            'status' => 'pending',
            'details' => "Withdrawal request of {$amount} USDT with fee {$fee} USDT",
        ]);
        
        // Create fee transaction record
        if ($fee > 0) {
            Transaction::create([
                'user_id' => $userId,
                'txn_id' => Transaction::generateTxnId(),
                'txn_type' => 'withdrawal_fee',
                'amount' => -$fee,
                'status' => 'pending',
                'details' => "Withdrawal fee of {$fee} USDT",
            ]);
        }
        
        Notification::createNotification(
            $userId,
            'Withdrawal Requested',
            "Your withdrawal request of {$amount} USDT has been submitted. Net amount: {$netAmount} USDT",
            'info'
        );
        
        return ['success' => true, 'message' => 'Withdrawal request submitted successfully', 'withdrawal' => $withdrawal];
    }
    
    public static function processWithdrawal($withdrawalId, $status, $adminNote = null)
    {
        $withdrawal = WithdrawalRequest::find($withdrawalId);
        if (!$withdrawal) {
            return ['success' => false, 'message' => 'Withdrawal request not found'];
        }
        
        if ($withdrawal->status !== 'pending') {
            return ['success' => false, 'message' => 'Withdrawal request has already been processed'];
        }
        
        $withdrawal->status = $status;
        $withdrawal->admin_note = $adminNote;
        $withdrawal->processed_at = now();
        $withdrawal->save();
        
        $user = $withdrawal->user;
        $wallet = $user->wallet;
        
        if ($status === 'completed') {
            // Deduct from wallet
            $totalDeduction = $withdrawal->amount;
            
            // Deduct from different balances in order
            $remainingDeduction = $totalDeduction;
            
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
            
            // Update transaction status
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
            
            Notification::createNotification(
                $user->id,
                'Withdrawal Completed',
                "Your withdrawal of {$withdrawal->net_amount} USDT has been processed and sent to your USDT address",
                'success'
            );
        } else if ($status === 'rejected') {
            // Update transaction status
            Transaction::where('user_id', $user->id)
                      ->where('txn_type', 'withdraw')
                      ->where('amount', -$withdrawal->amount)
                      ->where('status', 'pending')
                      ->update(['status' => 'cancelled']);
            
            Transaction::where('user_id', $user->id)
                      ->where('txn_type', 'withdrawal_fee')
                      ->where('amount', -$withdrawal->fee)
                      ->where('status', 'pending')
                      ->update(['status' => 'cancelled']);
            
            Notification::createNotification(
                $user->id,
                'Withdrawal Rejected',
                "Your withdrawal request has been rejected. Reason: {$adminNote}",
                'error'
            );
        }
        
        return ['success' => true, 'message' => 'Withdrawal request processed successfully'];
    }
}