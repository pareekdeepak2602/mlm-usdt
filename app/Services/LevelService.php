<?php

namespace App\Services;

use App\Models\User;
use App\Models\InvestmentPlan;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class LevelService
{
    public static function updateUserLevel(User $user)
    {
        $currentLevel = $user->current_level;
        
        // Get all plans ordered by level
        $plans = InvestmentPlan::where('status', 'active')
                              ->orderBy('level', 'asc')
                              ->get();
        
        $newLevel = 0;
        
        foreach ($plans as $plan) {
            if (self::meetsPlanRequirements($user, $plan)) {
                $newLevel = $plan->level;
            } else {
                break;
            }
        }
        
        // If level changed, update and notify
        if ($newLevel != $currentLevel) {
            $user->current_level = $newLevel;
            $user->save();
            
            // Send notification
            if ($newLevel > $currentLevel) {
                self::sendLevelUpNotification($user, $newLevel);
            }
        }
        
        return $newLevel;
    }
    
    public static function meetsPlanRequirements(User $user, InvestmentPlan $plan)
    {
        // Check referral requirements
        if ($plan->direct_referrals_required && 
            $user->direct_referrals_count < $plan->direct_referrals_required) {
            return false;
        }
        
        if ($plan->indirect_referrals_required && 
            $user->indirect_referrals_count < $plan->indirect_referrals_required) {
            return false;
        }
        
        // Check asset hold requirement
        if ($user->total_asset_hold < $plan->asset_hold) {
            return false;
        }
        
        return true;
    }
    
    public static function canUserInvestInPlan(User $user, InvestmentPlan $plan)
    {
        if ($user->current_level < $plan->level) {
            return [
                'success' => false,
                'message' => "You need to reach Level {$plan->level} to invest in this plan. Your current level is {$user->current_level}."
            ];
        }
        
        if (!self::meetsPlanRequirements($user, $plan)) {
            $message = "You don't meet the requirements for Level {$plan->level}:";
            
            if ($plan->direct_referrals_required && $user->direct_referrals_count < $plan->direct_referrals_required) {
                $message .= " Need {$plan->direct_referrals_required} direct referrals (you have {$user->direct_referrals_count}).";
            }
            
            if ($plan->indirect_referrals_required && $user->indirect_referrals_count < $plan->indirect_referrals_required) {
                $message .= " Need {$plan->indirect_referrals_required} indirect referrals (you have {$user->indirect_referrals_count}).";
            }
            
            if ($user->total_asset_hold < $plan->asset_hold) {
                $message .= " Need $" . number_format($plan->asset_hold, 2) . " asset hold (you have $" . number_format($user->total_asset_hold, 2) . ").";
            }
            
            return ['success' => false, 'message' => $message];
        }
        
        return ['success' => true];
    }
    
    public static function updateUserAssetHold(User $user)
    {
        $totalAssetHold = $user->investments()
                              ->where('status', 'active')
                              ->sum('amount');
        
        $user->total_asset_hold = $totalAssetHold;
        $user->save();
        
        // Update level after asset hold change
        self::updateUserLevel($user);
        
        return $totalAssetHold;
    }
    
    public static function updateReferralCounts(User $user)
    {
        $directReferrals = $user->referrals()
                               ->where('level_number', 1)
                               ->where('status', 'active')
                               ->count();
        
        $indirectReferrals = $user->referrals()
                                 ->where('level_number', '>', 1)
                                 ->where('status', 'active')
                                 ->count();
        
        $user->direct_referrals_count = $directReferrals;
        $user->indirect_referrals_count = $indirectReferrals;
        $user->save();
        
        // Update level after referral counts change
        self::updateUserLevel($user);
        
        return [
            'direct' => $directReferrals,
            'indirect' => $indirectReferrals
        ];
    }
    
    private static function sendLevelUpNotification(User $user, $newLevel)
    {
        // Create notification
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Level Upgraded!',
            'message' => "Congratulations! You have been upgraded to Level {$newLevel}. You can now access higher investment plans.",
            'type' => 'success'
        ]);
        
        // Here you can also send email notification
        // Mail::to($user->email)->send(new LevelUpMail($user, $newLevel));
    }
}