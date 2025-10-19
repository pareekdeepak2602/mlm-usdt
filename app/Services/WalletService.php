<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\SystemSetting;
use App\Models\Notification;
use App\Models\InvestmentPlan;

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

        // Check level-based deposit restrictions
        $levelCheck = self::checkDepositLimitByLevel($user, $amount);
        if (!$levelCheck['success']) {
            return $levelCheck;
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
            
            Notification::create([
                'user_id' => $userId,
                'title' => 'Account Activated',
                'message' => "Your account has been successfully activated with {$amount} USDT",
                'type' => 'success'
            ]);
        }
        
        Notification::create([
            'user_id' => $userId,
            'title' => 'Deposit Received',
            'message' => "Your deposit of {$amount} USDT has been credited to your wallet",
            'type' => 'success'
        ]);

        // Update user's total asset hold for level calculation
        LevelService::updateUserAssetHold($user);
        
        return ['success' => true, 'message' => 'Deposit successful'];
    }

    public static function checkDepositLimitByLevel(User $user, $amount)
    {
        $currentLevel = $user->current_level;
        
        // Get the investment plan for user's current level
        $currentPlan = InvestmentPlan::where('level', $currentLevel)
                                   ->where('status', 'active')
                                   ->first();
        
        if (!$currentPlan) {
            return ['success' => false, 'message' => 'No investment plan found for your current level'];
        }

        // Check minimum investment requirement
        if ($amount < $currentPlan->min_investment) {
            return [
                'success' => false, 
                'message' => "Minimum deposit for Level {$currentLevel} is $" . number_format($currentPlan->min_investment, 2)
            ];
        }

        // Check maximum investment limit
        if ($currentPlan->max_investment && $amount > $currentPlan->max_investment) {
            return [
                'success' => false, 
                'message' => "Maximum deposit for Level {$currentLevel} is $" . number_format($currentPlan->max_investment, 2)
            ];
        }

        // Check if user has sufficient level for the deposit amount
        $requiredPlan = InvestmentPlan::where('min_investment', '<=', $amount)
                                    ->where('status', 'active')
                                    ->orderBy('level', 'desc')
                                    ->first();
        
        if ($requiredPlan && $user->current_level < $requiredPlan->level) {
            $nextPlan = InvestmentPlan::where('level', $user->current_level + 1)
                                    ->where('status', 'active')
                                    ->first();
            
            if ($nextPlan) {
                return [
                    'success' => false,
                    'message' => "To deposit $" . number_format($amount, 2) . ", you need to reach Level {$requiredPlan->level}. " .
                               "Your current level is {$user->current_level}. " .
                               "Requirements for Level " . ($user->current_level + 1) . ": " .
                               ($nextPlan->direct_referrals_required ? "{$nextPlan->direct_referrals_required} direct referrals, " : "") .
                               ($nextPlan->indirect_referrals_required ? "{$nextPlan->indirect_referrals_required} indirect referrals, " : "") .
                               "$" . number_format($nextPlan->asset_hold, 2) . " asset hold."
                ];
            }
        }

        return ['success' => true];
    }

    public static function getDepositLimitsByLevel($level)
    {
        $plan = InvestmentPlan::where('level', $level)
                             ->where('status', 'active')
                             ->first();
        
        if (!$plan) {
            return [
                'min_deposit' => 50,
                'max_deposit' => null,
                'asset_hold_required' => 0
            ];
        }

        return [
            'min_deposit' => $plan->min_investment,
            'max_deposit' => $plan->max_investment,
            'asset_hold_required' => $plan->asset_hold
        ];
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
            'available_balance' => $wallet->deposit_balance + $wallet->earning_balance + $wallet->referral_balance,
        ];
    }
}