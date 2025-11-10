<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\LevelReferralCommission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public static function distributeDailyIncomeToUpline(User $user, $dailyIncome)
    {
        $totalDistributed = 0;
        
        try {
            // Get upline users (Level A, B, C)
            $uplineUsers = self::getUplineUsers($user);
            
            foreach ($uplineUsers as $level => $uplineUser) {
                if (!$uplineUser) continue;
                
                // Get commission percentage for this level from level_referral_commissions table
                $commissionRate = self::getCommissionRate($uplineUser->current_level, $level);
                
                if ($commissionRate > 0) {
                    $commissionAmount = $dailyIncome * ($commissionRate / 100);
                    
                    // Distribute to upline user
                    $distributed = self::creditUplineUser($uplineUser, $commissionAmount, $user, $level);
                    
                    if ($distributed) {
                        $totalDistributed += $commissionAmount;
                        
                        Log::info("Daily income commission distributed to upline", [
                            'from_user_id' => $user->id,
                            'to_user_id' => $uplineUser->id,
                            'level' => $level,
                            'upline_level' => $uplineUser->current_level,
                            'commission_rate' => $commissionRate,
                            'commission_amount' => $commissionAmount,
                            'daily_income' => $dailyIncome
                        ]);
                    }
                }
            }
            
            return $totalDistributed;
            
        } catch (\Exception $e) {
            Log::error("Error distributing daily income to upline", [
                'user_id' => $user->id,
                'daily_income' => $dailyIncome,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
    
    /**
     * Get upline users (Level A, B, C)
     */
    private static function getUplineUsers(User $user)
    {
        $upline = [];
        
        // Level A - Direct referrer
        $levelA = Referral::where('referred_id', $user->id)
                         ->where('level_number', 1)
                         ->with('referrer')
                         ->first();
        
        $upline['A'] = $levelA ? $levelA->referrer : null;
        
        // Level B - Referrer of Level A
        if ($upline['A']) {
            $levelB = Referral::where('referred_id', $upline['A']->id)
                             ->where('level_number', 1)
                             ->with('referrer')
                             ->first();
            
            $upline['B'] = $levelB ? $levelB->referrer : null;
        }
        
        // Level C - Referrer of Level B
        if (isset($upline['B']) && $upline['B']) {
            $levelC = Referral::where('referred_id', $upline['B']->id)
                             ->where('level_number', 1)
                             ->with('referrer')
                             ->first();
            
            $upline['C'] = $levelC ? $levelC->referrer : null;
        }
        
        return $upline;
    }
    
    /**
     * Get commission rate based on upline user's level and referral level
     */
    private static function getCommissionRate($uplineUserLevel, $referralLevel)
    {
        // Get commission rates from level_referral_commissions table
        $commissionRates = LevelReferralCommission::where('level', $uplineUserLevel)->first();
        
        if (!$commissionRates) {
            return 0;
        }
        
        // Return commission based on referral level (A, B, C)
        return match($referralLevel) {
            'A' => $commissionRates->direct_percentage,
            'B' => $commissionRates->level_b_percentage,
            'C' => $commissionRates->level_c_percentage,
            default => 0
        };
    }
    
    /**
     * Credit commission to upline user
     */
    private static function creditUplineUser(User $uplineUser, $amount, User $downlineUser, $level)
    {
        return DB::transaction(function () use ($uplineUser, $amount, $downlineUser, $level) {
            
            $wallet = $uplineUser->wallet;
            if (!$wallet) {
                $wallet = Wallet::create(['user_id' => $uplineUser->id]);
            }
            
            // Add to referral balance
            $wallet->referral_balance += $amount;
            $wallet->total_income += $amount;
            $wallet->save();
            
            // Create transaction record
            Transaction::create([
                'user_id' => $uplineUser->id,
                'txn_id' => Transaction::generateTxnId(),
                'txn_type' => 'referral',
                'amount' => $amount,
                'status' => 'completed',
                'details' => "Level {$level} daily income commission from {$downlineUser->name}",
            ]);
            
            // Create notification
            Notification::create([
                'user_id' => $uplineUser->id,
                'title' => 'Daily Income Commission',
                'message' => "You received " . number_format($amount, 2) . " USDT as Level {$level} commission from {$downlineUser->name}'s daily income",
                'type' => 'success'
            ]);
            
            return true;
        });
    }
}