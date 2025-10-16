<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\SystemSetting;
use App\Models\Notification;

class WalletService
{
    public static function deposit($userId, $amount, $txnHash = null)
    {
        $user = User::find($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        if ($amount <= 0) {
            return ['success' => false, 'message' => 'Invalid amount'];
        }
        
        $wallet = $user->wallet;
        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $userId]);
        }
        
        $wallet->deposit_balance += $amount;
        $wallet->save();
        
        // Create transaction record
        Transaction::create([
            'user_id' => $userId,
            'txn_id' => Transaction::generateTxnId(),
            'txn_type' => 'deposit',
            'amount' => $amount,
            'usdt_txn_hash' => $txnHash,
            'status' => 'completed',
            'details' => "USDT deposit of {$amount}",
        ]);
        
        // Check if this is the first deposit and meets minimum activation
        $minActivation = SystemSetting::getValue('minimum_activation', 50);
        if ($user->activation_amount == 0 && $amount >= $minActivation) {
            $user->activation_amount = $amount;
            $user->activation_date = now();
            $user->status = 'active';
            $user->save();
            
            // Process referral bonus for activation
            ReferralService::processReferralBonus($userId, $amount, 'activation');
            
            Notification::createNotification(
                $userId,
                'Account Activated',
                "Your account has been successfully activated with {$amount} USDT",
                'success'
            );
        }
        
        Notification::createNotification(
            $userId,
            'Deposit Received',
            "Your deposit of {$amount} USDT has been credited to your wallet",
            'success'
        );
        
        return ['success' => true, 'message' => 'Deposit successful'];
    }
    
    public static function addBonus($userId, $amount, $description)
    {
        $user = User::find($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        if ($amount <= 0) {
            return ['success' => false, 'message' => 'Invalid amount'];
        }
        
        $wallet = $user->wallet;
        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $userId]);
        }
        
        $wallet->referral_balance += $amount;
        $wallet->total_income += $amount;
        $wallet->save();
        
        // Create transaction record
        Transaction::create([
            'user_id' => $userId,
            'txn_id' => Transaction::generateTxnId(),
            'txn_type' => 'bonus',
            'amount' => $amount,
            'status' => 'completed',
            'details' => $description,
        ]);
        
        return ['success' => true, 'message' => 'Bonus added successfully'];
    }
    
    public static function getBalance($userId)
    {
        $wallet = Wallet::where('user_id', $userId)->first();
        if (!$wallet) {
            return [
                'deposit_balance' => 0,
                'earning_balance' => 0,
                'referral_balance' => 0,
                'total_income' => 0,
                'total_withdrawn' => 0,
                'available_balance' => 0,
            ];
        }
        
        return [
            'deposit_balance' => $wallet->deposit_balance,
            'earning_balance' => $wallet->earning_balance,
            'referral_balance' => $wallet->referral_balance,
            'total_income' => $wallet->total_income,
            'total_withdrawn' => $wallet->total_withdrawn,
            'available_balance' => $wallet->available_balance,
        ];
    }
}