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
     public static function checkAndUpgradeLevel($user)
    {
        $currentLevel = $user->current_level;
        $assetHold = $user->wallet ? $user->wallet->deposit_balance : 0;
        
        // Get the highest level the user qualifies for
        $qualifiedPlan = InvestmentPlan::where('asset_hold', '<=', $assetHold)
                                    ->where('status', 'active')
                                    ->orderBy('level', 'desc')
                                    ->first();

        if (!$qualifiedPlan || $qualifiedPlan->level <= $currentLevel) {
            return ['upgraded' => false, 'current_level' => $currentLevel];
        }

        // Check if user meets referral requirements for the qualified level
        if (!self::meetsLevelRequirements($user, $qualifiedPlan)) {
            return ['upgraded' => false, 'current_level' => $currentLevel];
        }

        // Upgrade user level
        $oldLevel = $user->current_level;
        $user->current_level = $qualifiedPlan->level;
        $user->save();

        Log::info("User level upgraded", [
            'user_id' => $user->id,
            'old_level' => $oldLevel,
            'new_level' => $qualifiedPlan->level,
            'asset_hold' => $assetHold
        ]);

        return [
            'upgraded' => true,
            'old_level' => $oldLevel,
            'new_level' => $qualifiedPlan->level,
            'plan_name' => $qualifiedPlan->name
        ];
    }

    /**
     * Check if user meets referral requirements for a plan
     */
    private static function meetsLevelRequirements($user, $plan)
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
     * Get user's next level requirements
     */
    public static function getNextLevelRequirements($userId)
    {
        $user = User::with(['wallet', 'referrals'])->find($userId);
        if (!$user) {
            return null;
        }

        $nextLevel = $user->current_level + 1;
        $nextPlan = InvestmentPlan::where('level', $nextLevel)
                                ->where('status', 'active')
                                ->first();

        if (!$nextPlan) {
            return null; // User is at max level
        }

        $directReferrals = $user->referrals()->where('level_number', 1)->count();
        $indirectReferrals = $user->referrals()->where('level_number', '>', 1)->count();
        $assetHold = $user->wallet ? $user->wallet->deposit_balance : 0;

        return [
            'next_level' => $nextLevel,
            'plan_name' => $nextPlan->name,
            'asset_hold_required' => $nextPlan->asset_hold,
            'current_asset_hold' => $assetHold,
            'direct_referrals_required' => $nextPlan->direct_referrals_required,
            'current_direct_referrals' => $directReferrals,
            'indirect_referrals_required' => $nextPlan->indirect_referrals_required,
            'current_indirect_referrals' => $indirectReferrals,
            'daily_percentage' => $nextPlan->daily_percentage,
            'min_investment' => $nextPlan->min_investment,
            'max_investment' => $nextPlan->max_investment
        ];
    }

    /**
     * Get user level progress
     */
    public static function getUserLevelProgress($userId)
    {
        $requirements = self::getNextLevelRequirements($userId);
        if (!$requirements) {
            return ['max_level_reached' => true];
        }

        $assetProgress = min(100, ($requirements['current_asset_hold'] / $requirements['asset_hold_required']) * 100);
        
        $directProgress = $requirements['direct_referrals_required'] ? 
            min(100, ($requirements['current_direct_referrals'] / $requirements['direct_referrals_required']) * 100) : 100;
            
        $indirectProgress = $requirements['indirect_referrals_required'] ? 
            min(100, ($requirements['current_indirect_referrals'] / $requirements['indirect_referrals_required']) * 100) : 100;

        return [
            'max_level_reached' => false,
            'next_level' => $requirements['next_level'],
            'asset_progress' => $assetProgress,
            'direct_referrals_progress' => $directProgress,
            'indirect_referrals_progress' => $indirectProgress,
            'requirements' => $requirements
        ];
    }
}