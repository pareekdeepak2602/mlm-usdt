<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralLevel;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Notification;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;
class ReferralService
{
    public static function processReferral($user)
    {
        if (!$user->referred_by) {
            return;
        }

        $referrer = User::where('referral_code', $user->referred_by)->first();
        if (!$referrer) {
            return;
        }

        // Process direct referral (Level A)
        self::createReferralRecord($referrer->id, $user->id, 1);
        
        // Process Level B and C referrals
        self::processUpperLevelReferrals($referrer, $user->id, 2);
    }

    private static function processUpperLevelReferrals($referrer, $referredId, $level)
    {
        if ($level > 3 || !$referrer->referred_by) {
            return;
        }

        $upperReferrer = User::where('referral_code', $referrer->referred_by)->first();
        if (!$upperReferrer) {
            return;
        }

        self::createReferralRecord($upperReferrer->id, $referredId, $level);
        self::processUpperLevelReferrals($upperReferrer, $referredId, $level + 1);
    }

    private static function createReferralRecord($referrerId, $referredId, $level)
    {
        $referralLevel = ReferralLevel::where('level_number', $level)->first();
        if (!$referralLevel) {
            return;
        }

        Referral::create([
            'referrer_id' => $referrerId,
            'referred_id' => $referredId,
            'level_number' => $level,
            'status' => 'active',
        ]);
    }

    public static function processReferralBonus($userId, $amount, $type = 'activation')
    {
        $user = User::find($userId);
        if (!$user || !$user->referred_by) {
            return;
        }

        $referrer = User::where('referral_code', $user->referred_by)->first();
        if (!$referrer) {
            return;
        }

        $wallet = $referrer->wallet;
        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $referrer->id]);
        }

        // Process direct referral bonus (Level A)
        $levelA = ReferralLevel::where('level_number', 1)->first();
        if ($levelA) {
            $bonusAmount = $amount * ($levelA->percentage / 100);
            
            $wallet->referral_balance += $bonusAmount;
            $wallet->total_income += $bonusAmount;
            $wallet->save();

            Transaction::create([
                'user_id' => $referrer->id,
                'txn_id' => Transaction::generateTxnId(),
                'txn_type' => 'referral',
                'amount' => $bonusAmount,
                'status' => 'completed',
                'details' => "Level A referral bonus from {$user->name} for {$type}",
            ]);

            Notification::createNotification(
                $referrer->id,
                'Referral Bonus Received',
                "You received {$bonusAmount} USDT as Level A referral bonus from {$user->name}",
                'success'
            );

            // Update referral record
            $referral = Referral::where('referrer_id', $referrer->id)
                                ->where('referred_id', $userId)
                                ->where('level_number', 1)
                                ->first();
            if ($referral) {
                $referral->bonus_amount += $bonusAmount;
                $referral->save();
            }
        }

        // Process Level B and C bonuses
        self::processUpperLevelBonuses($referrer, $userId, $amount, $type, 2);
    }

    private static function processUpperLevelBonuses($referrer, $referredId, $amount, $type, $level)
    {
        if ($level > 3 || !$referrer->referred_by) {
            return;
        }

        $upperReferrer = User::where('referral_code', $referrer->referred_by)->first();
        if (!$upperReferrer) {
            return;
        }

        $referralLevel = ReferralLevel::where('level_number', $level)->first();
        if (!$referralLevel) {
            return;
        }

        $wallet = $upperReferrer->wallet;
        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $upperReferrer->id]);
        }

        $bonusAmount = $amount * ($referralLevel->percentage / 100);
        
        $wallet->referral_balance += $bonusAmount;
        $wallet->total_income += $bonusAmount;
        $wallet->save();

        Transaction::create([
            'user_id' => $upperReferrer->id,
            'txn_id' => Transaction::generateTxnId(),
            'txn_type' => 'referral',
            'amount' => $bonusAmount,
            'status' => 'completed',
            'details' => "Level {$referralLevel->level_name} referral bonus from " . User::find($referredId)->name . " for {$type}",
        ]);

        Notification::createNotification(
            $upperReferrer->id,
            'Referral Bonus Received',
            "You received {$bonusAmount} USDT as Level {$referralLevel->level_name} referral bonus from " . User::find($referredId)->name,
            'success'
        );

        // Update referral record
        $referral = Referral::where('referrer_id', $upperReferrer->id)
                            ->where('referred_id', $referredId)
                            ->where('level_number', $level)
                            ->first();
        if ($referral) {
            $referral->bonus_amount += $bonusAmount;
            $referral->save();
        }

        self::processUpperLevelBonuses($upperReferrer, $referredId, $amount, $type, $level + 1);
    }

     public static function processActivationCommission($userId, $activationAmount)
    {
        $user = User::find($userId);
        if (!$user || !$user->referred_by) {
            return;
        }
        
        // Find direct referrer
        $referrer = User::where('referral_code', $user->referred_by)->first();
        if (!$referrer) {
            return;
        }
        
        $commissionPercentage = SystemSetting::getValue('referral_activation_bonus', 10);
        $commissionAmount = $activationAmount * ($commissionPercentage / 100);
        
        DB::transaction(function () use ($referrer, $commissionAmount, $user) {
            
            $wallet = $referrer->wallet;
            if (!$wallet) {
                $wallet = Wallet::create(['user_id' => $referrer->id]);
            }
            
            // Add commission to referral balance
            $wallet->referral_balance += $commissionAmount;
            $wallet->total_income += $commissionAmount;
            $wallet->save();
            
            // Create transaction record
            Transaction::create([
                'user_id' => $referrer->id,
                'txn_id' => Transaction::generateTxnId(),
                'txn_type' => 'referral',
                'amount' => $commissionAmount,
                'status' => 'completed',
                'details' => "Level A activation bonus from {$user->name}",
            ]);
            
            // Create referral record
            Referral::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'level_number' => 1,
                'bonus_amount' => $commissionAmount,
                'status' => 'active',
            ]);
            
            // Create notification
            Notification::create([
                'user_id' => $referrer->id,
                'title' => 'Referral Bonus Received',
                'message' => "You received " . number_format($commissionAmount, 2) . " USDT as Level A activation bonus from {$user->name}",
                'type' => 'success'
            ]);
        });
    }   
}