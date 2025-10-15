<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInvestment;
use App\Models\InvestmentPlan;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\DailyIncome;
use App\Models\Notification;
use Carbon\Carbon;

class InvestmentService
{
    public static function createInvestment($userId, $planId, $amount)
    {
        $user = User::find($userId);
        $plan = InvestmentPlan::find($planId);
        
        if (!$user || !$plan) {
            return ['success' => false, 'message' => 'Invalid user or investment plan'];
        }
        
        if ($amount < $plan->min_investment || ($plan->max_investment && $amount > $plan->max_investment)) {
            return ['success' => false, 'message' => 'Investment amount is not within the plan limits'];
        }
        
        $wallet = $user->wallet;
        if (!$wallet || $wallet->deposit_balance < $amount) {
            return ['success' => false, 'message' => 'Insufficient balance'];
        }
        
        // Deduct from deposit balance
        $wallet->deposit_balance -= $amount;
        $wallet->save();
        
        // Create transaction record
        Transaction::create([
            'user_id' => $userId,
            'txn_id' => Transaction::generateTxnId(),
            'txn_type' => 'deposit',
            'amount' => -$amount,
            'status' => 'completed',
            'details' => "Investment in {$plan->name} plan",
        ]);
        
        // Create investment record
        $dailyIncome = $amount * ($plan->daily_percentage / 100);
        $startDate = now();
        $endDate = $startDate->copy()->addDays($plan->duration_days - 1);
        
        $investment = UserInvestment::create([
            'user_id' => $userId,
            'plan_id' => $planId,
            'amount' => $amount,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'daily_income' => $dailyIncome,
            'status' => 'active',
        ]);
        
        // Process referral bonus if this is the first investment
        if ($user->investments()->count() === 1) {
            ReferralService::processReferralBonus($userId, $amount, 'investment');
        }
        
        Notification::createNotification(
            $userId,
            'Investment Created',
            "Your investment of {$amount} USDT in {$plan->name} plan has been activated",
            'success'
        );
        
        return ['success' => true, 'message' => 'Investment created successfully', 'investment' => $investment];
    }
    
    public static function processDailyIncome()
    {
        $activeInvestments = UserInvestment::where('status', 'active')
                                          ->where('end_date', '>=', now())
                                          ->get();
        
        foreach ($activeInvestments as $investment) {
            $today = now()->format('Y-m-d');
            
            // Check if income for today has already been processed
            $existingIncome = DailyIncome::where('user_id', $investment->user_id)
                                        ->where('investment_id', $investment->id)
                                        ->where('income_date', $today)
                                        ->first();
            
            if ($existingIncome) {
                continue;
            }
            
            // Create daily income record
            DailyIncome::create([
                'user_id' => $investment->user_id,
                'investment_id' => $investment->id,
                'amount' => $investment->daily_income,
                'income_date' => $today,
            ]);
            
            // Update investment total earned
            $investment->total_earned += $investment->daily_income;
            $investment->save();
            
            // Update user wallet
            $wallet = $investment->user->wallet;
            if ($wallet) {
                $wallet->earning_balance += $investment->daily_income;
                $wallet->total_income += $investment->daily_income;
                $wallet->save();
                
                // Create transaction record
                Transaction::create([
                    'user_id' => $investment->user_id,
                    'txn_id' => Transaction::generateTxnId(),
                    'txn_type' => 'income',
                    'amount' => $investment->daily_income,
                    'status' => 'completed',
                    'details' => "Daily income from {$investment->plan->name} investment",
                ]);
            }
            
            // Check if investment has completed
            if (now()->greaterThan($investment->end_date)) {
                $investment->status = 'completed';
                $investment->save();
                
                Notification::createNotification(
                    $investment->user_id,
                    'Investment Completed',
                    "Your investment in {$investment->plan->name} has been completed. Total earned: {$investment->total_earned} USDT",
                    'success'
                );
            }
        }
    }
}