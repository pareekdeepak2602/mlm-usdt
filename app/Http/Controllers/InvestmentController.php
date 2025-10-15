<?php

namespace App\Http\Controllers;

use App\Models\InvestmentPlan;
use App\Models\UserInvestment;
use App\Services\InvestmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $plans = InvestmentPlan::where('status', 'active')->get();
        $investments = $user->investments()->with('plan')->orderBy('created_at', 'desc')->get();
        
        return view('investments.index', compact('plans', 'investments'));
    }
    
    public function create($planId)
    {
        $plan = InvestmentPlan::findOrFail($planId);
        $user = Auth::user();
        
        return view('investments.create', compact('plan', 'user'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:investment_plans,id',
            'amount' => 'required|numeric|min:50',
        ]);
        
        $result = InvestmentService::createInvestment(
            Auth::id(),
            $request->input('plan_id'),
            $request->input('amount')
        );
        
        if ($result['success']) {
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