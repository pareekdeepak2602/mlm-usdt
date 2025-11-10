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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        
        // Use transaction for data consistency
        return DB::transaction(function () use ($user, $plan, $amount, $wallet) {
            // Deduct from deposit balance
            $wallet->deposit_balance -= $amount;
            $wallet->save();
            
            // Create transaction record
            Transaction::create([
                'user_id' => $user->id,
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
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount' => $amount,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'daily_income' => $dailyIncome,
                'total_earned' => 0.00,
                'status' => 'active',
            ]);
            
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Investment Created',
                'message' => "Your investment of {$amount} USDT in {$plan->name} plan has been activated",
                'type' => 'success'
            ]);
            
            return [
                'success' => true, 
                'message' => 'Investment created successfully', 
                'investment' => $investment
            ];
        });
    }
    
    public static function processDailyIncome($testMode = false, $specificDate = null, $specificUser = null)
    {
        try {
            $processingDate = $specificDate ? Carbon::parse($specificDate) : now();
            $dateString = $processingDate->format('Y-m-d');
            
            Log::info("Starting daily income processing based on user level and deposit", [
                'date' => $dateString,
                'test_mode' => $testMode,
                'specific_user' => $specificUser
            ]);
            
            // Get all active users with deposit balance
            $query = User::with(['wallet'])
                        ->where('status', 'active')
                        ->where('activation_amount', '>', 0)
                        ->whereHas('wallet', function($q) {
                            $q->where('deposit_balance', '>', 0);
                        });
            
            if ($specificUser) {
                $query->where('id', $specificUser);
            }
            
            $activeUsers = $query->get();
            
            $stats = [
                'total_users' => $activeUsers->count(),
                'income_processed' => 0,
                'total_amount' => 0,
                'success' => true,
                'message' => 'Processing completed'
            ];
            
            if ($activeUsers->isEmpty()) {
                Log::info("No active users found for daily income processing");
                return $stats;
            }
            
            foreach ($activeUsers as $user) {
                try {
                    // Check if income already processed for today
                    $existingIncome = DailyIncome::where('user_id', $user->id)
                                                ->where('income_date', $dateString)
                                                ->first();
                    
                    if ($existingIncome) {
                        continue; // Already processed today
                    }
                    
                    // Get user's current plan based on level
                    $currentPlan = InvestmentPlan::where('level', $user->current_level)
                                               ->where('status', 'active')
                                               ->first();
                    
                    if (!$currentPlan) {
                        Log::warning("No investment plan found for user level", [
                            'user_id' => $user->id,
                            'level' => $user->current_level
                        ]);
                        continue;
                    }
                    
                    // Calculate daily income based on deposit balance and plan percentage
                    $depositBalance = $user->wallet ? $user->wallet->deposit_balance : 0;
                    
                    if ($depositBalance <= 0) {
                        
                        continue;
                    }
                    
                    $dailyIncome = $depositBalance * ($currentPlan->daily_percentage / 100);
                    
                    if ($dailyIncome <= 0) {
                        
                        continue;
                    }
                    
                    if ($testMode) {
                        // Test mode - just log what would happen
                        Log::info("TEST MODE: Would process daily income", [
                            'user_id' => $user->id,
                            'level' => $user->current_level,
                            'deposit_balance' => $depositBalance,
                            'daily_percentage' => $currentPlan->daily_percentage,
                            'daily_income' => $dailyIncome,
                            'date' => $dateString
                        ]);
                        
                        $stats['income_processed']++;
                        $stats['total_amount'] += $dailyIncome;
                        continue;
                    }
                    
                    // Real processing within transaction
                    DB::transaction(function () use ($user, $dailyIncome, $dateString, $currentPlan, $depositBalance, &$stats) {
                        
                        // ✅ DISTRIBUTE DAILY INCOME TO UPLINE (NEW LOGIC)
                        $distributedAmount = LevelReferralService::distributeDailyIncomeToUpline($user, $dailyIncome);
                        
                        // User gets remaining amount after upline distribution
                        $userIncomeAmount = $dailyIncome ;
                        
                        // Create daily income record for user
                        DailyIncome::create([
                            'user_id' => $user->id,
                            'investment_id' => null,
                            'level' => $user->current_level,
                            'amount' => $userIncomeAmount,
                            'income_date' => $dateString,
                            'calculation_type' => 'level_based',
                            'details' => json_encode([
                                'level' => $user->current_level,
                                'plan_name' => $currentPlan->name,
                                'daily_percentage' => $currentPlan->daily_percentage,
                                'deposit_balance' => $depositBalance,
                                'calculation_type' => 'level_based',
                                'gross_income' => $dailyIncome,
                                'upline_distributed' => $distributedAmount,
                                'net_income' => $userIncomeAmount
                            ])
                        ]);
                        
                        // Update user wallet with NET income (after upline distribution)
                        if ($user->wallet) {
                            $user->wallet->earning_balance += $userIncomeAmount;
                            $user->wallet->total_income += $userIncomeAmount;
                            $user->wallet->save();
                        }
                        
                        // Create transaction record for user
                        Transaction::create([
                            'user_id' => $user->id,
                            'txn_id' => Transaction::generateTxnId(),
                            'txn_type' => 'income',
                            'amount' => $userIncomeAmount,
                            'status' => 'completed',
                            'details' => "Daily income from Level {$user->current_level} - {$currentPlan->name} ({$currentPlan->daily_percentage}%) - Net after upline distribution",
                        ]);
                        
                        // Create notification for user
                        Notification::create([
                            'user_id' => $user->id,
                            'title' => 'Daily Income Received',
                            'message' => "You received " . number_format($userIncomeAmount, 2) . " USDT daily income from your Level {$user->current_level} investment",
                            'type' => 'success'
                        ]);
                        
                        $stats['income_processed']++;
                        $stats['total_amount'] += $userIncomeAmount;
                        
                        Log::info("Daily income processed for user with upline distribution", [
                            'user_id' => $user->id,
                            'level' => $user->current_level,
                            'deposit_balance' => $depositBalance,
                            'gross_daily_income' => $dailyIncome,
                            'upline_distributed' => $distributedAmount,
                            'user_net_income' => $userIncomeAmount,
                            'date' => $dateString
                        ]);
                    });
                    
                } catch (\Exception $e) {
                    Log::error("Error processing daily income for user {$user->id}", [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id
                    ]);
                    continue;
                }
            }
            
            Log::info("Daily income processing completed", $stats);
            return $stats;
            
        } catch (\Exception $e) {
            Log::error("Daily income processing failed", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'total_users' => 0,
                'income_processed' => 0,
                'total_amount' => 0
            ];
        }
    }
    
    /**
     * Get investment statistics for reporting
     */
    public static function getInvestmentStats()
    {
        $totalInvestments = UserInvestment::count();
        $activeInvestments = UserInvestment::where('status', 'active')->count();
        $completedInvestments = UserInvestment::where('status', 'completed')->count();
        $totalInvested = UserInvestment::sum('amount');
        $totalEarned = UserInvestment::sum('total_earned');
        
        return [
            'total_investments' => $totalInvestments,
            'active_investments' => $activeInvestments,
            'completed_investments' => $completedInvestments,
            'total_invested' => $totalInvested,
            'total_earned' => $totalEarned
        ];
    }
}