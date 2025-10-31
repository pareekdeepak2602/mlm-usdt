<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\LevelReferralCommission;

class LevelReferralService
{
    /**
     * Process level-based referral commissions when a user deposits
     */
    public static function processLevelReferralCommission($depositingUserId, $amount)
    {
        $depositingUser = User::find($depositingUserId);
        if (!$depositingUser || !$depositingUser->referred_by) {
            return;
        }

        // Get direct referrer (Level A)
        $directReferrer = User::where('referral_code', $depositingUser->referred_by)->first();
        if (!$directReferrer) {
            return;
        }

        // Process commissions for all upline levels
        self::processUplineCommissions($directReferrer, $depositingUser, $amount, 1);
    }

    /**
     * Recursively process commissions for upline users
     */
    private static function processUplineCommissions($referrer, $depositingUser, $amount, $level)
    {
        if ($level > 3 || !$referrer) {
            return;
        }

        // Get referrer's level commission rates
        $commissionRates = LevelReferralCommission::where('level', $referrer->current_level)->first();
        
        if (!$commissionRates) {
            // Move to next upline if no commission rates found
            self::processNextUpline($referrer, $depositingUser, $amount, $level);
            return;
        }

        $percentage = 0;
        $levelType = '';

        switch ($level) {
            case 1: // Direct referral (A)
                $percentage = $commissionRates->direct_percentage;
                $levelType = 'A';
                break;
            case 2: // Level B
                $percentage = $commissionRates->level_b_percentage;
                $levelType = 'B';
                break;
            case 3: // Level C
                $percentage = $commissionRates->level_c_percentage;
                $levelType = 'C';
                break;
        }

        // Only process if percentage is greater than 0
        if ($percentage > 0) {
            $commissionAmount = $amount * ($percentage / 100);
            self::addCommissionToWallet($referrer, $commissionAmount, $depositingUser, $levelType, $percentage);
        }

        // Process next upline level
        self::processNextUpline($referrer, $depositingUser, $amount, $level);
    }

    /**
     * Process the next upline user
     */
    private static function processNextUpline($currentReferrer, $depositingUser, $amount, $currentLevel)
    {
        if ($currentLevel >= 3 || !$currentReferrer->referred_by) {
            return;
        }

        $nextUpline = User::where('referral_code', $currentReferrer->referred_by)->first();
        if ($nextUpline) {
            self::processUplineCommissions($nextUpline, $depositingUser, $amount, $currentLevel + 1);
        }
    }

    /**
     * Add commission to user's wallet and create records
     */
    private static function addCommissionToWallet($user, $amount, $depositingUser, $levelType, $percentage)
    {
        $wallet = $user->wallet;
        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $user->id]);
        }

        // Add to referral balance
        $wallet->referral_balance += $amount;
        $wallet->total_income += $amount;
        $wallet->save();

        // Create transaction record
        Transaction::create([
            'user_id' => $user->id,
            'txn_id' => Transaction::generateTxnId(),
            'txn_type' => 'referral',
            'amount' => $amount,
            'status' => 'completed',
            'details' => "Level {$levelType} referral commission ({$percentage}%) from {$depositingUser->name}'s deposit",
        ]);

        // Create notification
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Referral Commission Received',
            'message' => "You received {$amount} USDT as Level {$levelType} referral commission from {$depositingUser->name}'s deposit",
            'type' => 'success'
        ]);

        // Log the commission
        \Log::info("Level referral commission processed", [
            'referrer_id' => $user->id,
            'depositing_user_id' => $depositingUser->id,
            'level_type' => $levelType,
            'percentage' => $percentage,
            'amount' => $amount,
            'deposit_amount' => $amount / ($percentage / 100)
        ]);
    }

    /**
     * Get commission rates for a specific level
     */
    public static function getCommissionRates($level)
    {
        return LevelReferralCommission::where('level', $level)->first();
    }

    /**
     * Check if user is eligible for level referral commissions
     */
    public static function isEligibleForCommissions($user)
    {
        $commissionRates = self::getCommissionRates($user->current_level);
        return $commissionRates && (
            $commissionRates->direct_percentage > 0 || 
            $commissionRates->level_b_percentage > 0 || 
            $commissionRates->level_c_percentage > 0
        );
    }
}