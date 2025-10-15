<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use App\Models\UserInvestment;
use App\Services\InvestmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    public function plans()
    {
        $plans = InvestmentPlan::where('status', 'active')->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'plans' => $plans
            ]
        ]);
    }
    
    public function investments(Request $request)
    {
        $investments = $request->user()
                               ->investments()
                               ->with('plan')
                               ->orderBy('created_at', 'desc')
                               ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => [
                'investments' => $investments
            ]
        ]);
    }
    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:investment_plans,id',
            'amount' => 'required|numeric|min:50',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $result = InvestmentService::createInvestment(
            $request->user()->id,
            $request->input('plan_id'),
            $request->input('amount')
        );
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'investment' => $result['investment']
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }
    }
    
    public function show(Request $request, $id)
    {
        $investment = UserInvestment::with(['plan', 'dailyIncomes'])
                                    ->where('user_id', $request->user()->id)
                                    ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'investment' => $investment
            ]
        ]);
    }
}