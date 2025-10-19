<?php

namespace App\Http\Controllers;

use App\Models\InvestmentPlan;
use App\Models\UserInvestment;
use App\Models\User;
use App\Services\InvestmentService;
use App\Services\LevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Update user level based on current stats
        LevelService::updateUserLevel($user);
        
        $plans = InvestmentPlan::where('status', 'active')
                              ->orderBy('level', 'asc')
                              ->get();
        
        $investments = $user->investments()
                           ->with('plan')
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        // Get user's current level stats
        $userStats = [
            'current_level' => $user->current_level,
            'direct_referrals' => $user->direct_referrals_count,
            'indirect_referrals' => $user->indirect_referrals_count,
            'total_asset_hold' => $user->total_asset_hold,
            'next_level' => $user->current_level < 6 ? $plans->where('level', $user->current_level + 1)->first() : null,
        ];
        
        return view('investments.index', compact('plans', 'investments', 'userStats'));
    }
    
    public function create($planId)
    {
        $plan = InvestmentPlan::findOrFail($planId);
        $user = Auth::user();
        
        // Check if user can invest in this plan
        $canInvest = LevelService::canUserInvestInPlan($user, $plan);
        
        if (!$canInvest['success']) {
            return redirect()->route('investments.index')
                           ->with('error', $canInvest['message']);
        }
        
        return view('investments.create', compact('plan', 'user'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:investment_plans,id',
            'amount' => 'required|numeric|min:50',
        ]);
        
        $plan = InvestmentPlan::find($request->input('plan_id'));
        $user = Auth::user();
        
        // Check eligibility again
        $canInvest = LevelService::canUserInvestInPlan($user, $plan);
        if (!$canInvest['success']) {
            return redirect()->back()->with('error', $canInvest['message']);
        }
        
        // Check amount range
        if ($request->amount < $plan->min_investment) {
            return redirect()->back()->with('error', 
                "Minimum investment for this plan is $" . number_format($plan->min_investment, 2));
        }
        
        if ($plan->max_investment && $request->amount > $plan->max_investment) {
            return redirect()->back()->with('error', 
                "Maximum investment for this plan is $" . number_format($plan->max_investment, 2));
        }
        
        $result = InvestmentService::createInvestment(
            $user->id,
            $request->input('plan_id'),
            $request->input('amount')
        );
        
        if ($result['success']) {
            // Update user's asset hold
            LevelService::updateUserAssetHold($user);
            
            return redirect()->route('investments.index')->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
    
    public function show($id)
    {
        $investment = UserInvestment::with(['plan', 'dailyIncomes'])
                                    ->where('user_id', Auth::id())
                                    ->findOrFail($id);
        
        return view('investments.show', compact('investment'));
    }
}