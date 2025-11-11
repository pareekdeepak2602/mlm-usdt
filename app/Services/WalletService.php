<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\SystemSetting;
use App\Models\Notification;
use App\Models\InvestmentPlan;
use App\Services\LevelReferralService;
use App\Mail\DepositConfirmationMail; // ADD THIS
use Illuminate\Support\Facades\Mail; // ADD THIS

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
       $transaction= Transaction::create([
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
            //ReferralService::processReferralBonus($userId, $amount, 'activation');
             ReferralService::processActivationCommission($userId, $amount);
            Notification::create([
                'user_id' => $userId,
                'title' => 'Account Activated',
                'message' => "Your account has been successfully activated with {$amount} USDT",
                'type' => 'success'
            ]);
        }
        // LevelReferralService::processLevelReferralCommission($userId, $amount);
        Notification::create([
            'user_id' => $userId,
            'title' => 'Deposit Received',
            'message' => "Your deposit of {$amount} USDT has been credited to your wallet",
            'type' => 'success'
        ]);

        // Update user's total asset hold for level calculation
        LevelService::updateUserAssetHold($user);
         $levelUpgradeResult = LevelService::checkAndUpgradeLevel($user);
        
        // SEND DEPOSIT CONFIRMATION EMAIL
        self::sendDepositConfirmationEmail($user, $amount, $transaction, $levelUpgradeResult, $wallet);

        return ['success' => true, 'message' => 'Deposit successful'];
    }
private static function sendDepositConfirmationEmail($user, $amount, $transaction, $levelUpgradeResult, $wallet)
    {
        try {
            // Get current level benefits
            $currentPlan = InvestmentPlan::where('level', $user->current_level)
                                       ->where('status', 'active')
                                       ->first();
            
            // Get level referral commission rates
            $commissionRates = \App\Services\LevelReferralService::getCommissionRates($user->current_level);
            $referralCommission = $commissionRates ? max(
                $commissionRates->direct_percentage, 
                $commissionRates->level_b_percentage, 
                $commissionRates->level_c_percentage
            ) : 0;

            // Calculate daily earnings
            $dailyEarnings = $wallet->deposit_balance * ($currentPlan->daily_percentage / 100);

            // Prepare email data
            $mailData = [
                'userName' => $user->name,
                'depositAmount' => $amount,
                'transactionId' => $transaction->txn_id,
                'depositDate' => $transaction->created_at->format('F j, Y \a\t g:i A'),
                'transactionHash' => $transaction->usdt_txn_hash,
                'levelUpgrade' => $levelUpgradeResult['upgraded'] ?? false,
                'newLevel' => $levelUpgradeResult['new_level'] ?? null,
                'dailyPercentage' => $currentPlan->daily_percentage ?? 0,
                'referralCommission' => $referralCommission,
                'walletBalance' => $wallet->deposit_balance,
                'referralBalance' => $wallet->referral_balance,
                'totalBalance' => $wallet->deposit_balance + $wallet->earning_balance + $wallet->referral_balance,
                'dailyEarnings' => $dailyEarnings,
            ];

            // Send email
            Mail::to($user->email)->send(new DepositConfirmationMail(...array_values($mailData)));

            \Log::info("Deposit confirmation email sent", [
                'user_id' => $user->id,
                'email' => $user->email,
                'amount' => $amount
            ]);

        } catch (\Exception $e) {
            \Log::error("Failed to send deposit confirmation email", [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
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
                            ->where('max_investment', '>=', $amount) // ADDED THIS
                            ->orderBy('level', 'asc') // Get lowest suitable level
                            ->first();
        
        if ($requiredPlan && $user->current_level < $requiredPlan->level) {
            $nextPlan = InvestmentPlan::where('level', $user->current_level + 1)
                                    ->where('status', 'active')
                                    ->first();
            
            // if ($nextPlan) {
            //     return [
            //         'success' => false,
            //         'message' => "To deposit $" . number_format($amount, 2) . ", you need to reach Level {$requiredPlan->level}. " .
            //                    "Your current level is {$user->current_level}. " .
            //                    "Requirements for Level " . ($user->current_level + 1) . ": " .
            //                    ($nextPlan->direct_referrals_required ? "{$nextPlan->direct_referrals_required} direct referrals, " : "") .
            //                    ($nextPlan->indirect_referrals_required ? "{$nextPlan->indirect_referrals_required} indirect referrals, " : "") .
            //                    "$" . number_format($nextPlan->asset_hold, 2) . " asset hold."
            //     ];
            // }
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
        $user = User::find($userId);
        if (!$user) {
            return [
                'deposit_balance' => 0,
                'earning_balance' => 0,
                'referral_balance' => 0,
                'available_balance' => 0,
                'total_balance' => 0,
                'asset_hold' => 0,
                'total_withdrawn' => 0,
                'withdrawable_balance' => 0,
                'profit_percentage' => 0,
                'is_asset_hold_locked' => false
            ];
        }

        $wallet = $user->wallet;
        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $userId]);
        }

        $totalBalance = $wallet->deposit_balance + $wallet->earning_balance + $wallet->referral_balance;
        
        // NEW LOGIC: Calculate profit percentage and asset hold lock
        $assetHoldInfo = self::calculateAssetHoldLock($user, $wallet);
        $assetHold = $assetHoldInfo['asset_hold'];
        $isAssetHoldLocked = $assetHoldInfo['is_locked'];
        $profitPercentage = $assetHoldInfo['profit_percentage'];
        
        // Withdrawable balance calculation based on new logic
        if ($isAssetHoldLocked) {
            // Asset hold is locked - can only withdraw above asset hold
            $withdrawableBalance = max(0, $totalBalance - $assetHold);
        } else {
            // Asset hold NOT locked - can withdraw everything
            $withdrawableBalance = $totalBalance;
        }

        return [
            'deposit_balance' => $wallet->deposit_balance,
            'earning_balance' => $wallet->earning_balance,
            'referral_balance' => $wallet->referral_balance,
            'available_balance' => $totalBalance,
            'total_balance' => $totalBalance,
            'asset_hold' => $assetHold,
            'total_withdrawn' => $wallet->total_withdrawn,
            'withdrawable_balance' => $withdrawableBalance,
            'profit_percentage' => $profitPercentage,
            'is_asset_hold_locked' => $isAssetHoldLocked
        ];
    }

    /**
     * NEW METHOD: Calculate asset hold lock based on 50% profit rule
     */
    private static function calculateAssetHoldLock(User $user, Wallet $wallet)
    {
        $currentLevel = $user->current_level;
        $requiredAssetHold = self::getAssetHoldByLevel($currentLevel);
        
        // If no asset hold required for this level, return zero
        if ($requiredAssetHold <= 0) {
            return [
                'asset_hold' => 0,
                'is_locked' => false,
                'profit_percentage' => 0
            ];
        }

        $totalBalance = $wallet->deposit_balance + $wallet->earning_balance + $wallet->referral_balance;
        
        // Calculate profit: Total balance minus total deposits (approximated by deposit_balance)
        // This is a simplified calculation - you might want to track actual deposits separately
        $totalDeposits = $wallet->deposit_balance; // Approximation
        $profit = max(0, $totalBalance - $totalDeposits);
        
        // Calculate profit percentage
        $profitPercentage = $totalDeposits > 0 ? ($profit / $totalDeposits) * 100 : 0;
        
        // Asset hold is locked only when profit reaches 50%
        $isAssetHoldLocked = $profitPercentage >= 50;

        return [
            'asset_hold' => $requiredAssetHold,
            'is_locked' => $isAssetHoldLocked,
            'profit_percentage' => $profitPercentage
        ];
    }

    /**
     * Check if user can withdraw specified amount considering new asset hold logic
     */
    public static function canWithdraw($userId, $amount)
    {
        $balance = self::getBalance($userId);
        return $amount <= $balance['withdrawable_balance'];
    }

    /**
     * Get maximum withdrawable amount for user
     */
    public static function getMaxWithdrawableAmount($userId)
    {
        $balance = self::getBalance($userId);
        return $balance['withdrawable_balance'];
    }


    /**
     * Get asset hold requirement based on user's current level
     */
    private static function getAssetHoldByLevel($currentLevel)
    {
        $investmentPlan = InvestmentPlan::where('level', $currentLevel)->first();
        
        if ($investmentPlan) {
            return $investmentPlan->asset_hold ?? 0;
        }
        
        // Default asset hold if level not found
        return match($currentLevel) {
            0 => 50.00,
            1 => 100.00,
            2 => 200.00,
            3 => 700.00,
            4 => 1500.00,
            5 => 3500.00,
            6 => 7000.00,
            default => 0
        };
    }

    /**
     * Check if user can withdraw specified amount considering asset hold
     */
    // public static function canWithdraw($userId, $amount)
    // {
    //     $balance = self::getBalance($userId);
    //     return $amount <= $balance['withdrawable_balance'];
    // }

    /**
     * Get maximum withdrawable amount for user
     */
    // public static function getMaxWithdrawableAmount($userId)
    // {
    //     $balance = self::getBalance($userId);
    //     return $balance['withdrawable_balance'];
    // }
    
}