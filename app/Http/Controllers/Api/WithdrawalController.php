<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Models\SystemSetting;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $withdrawals = $request->user()
                               ->withdrawalRequests()
                               ->orderBy('created_at', 'desc')
                               ->paginate(20);
        
        $balance = \App\Services\WalletService::getBalance($request->user()->id);
        $minWithdrawal = SystemSetting::getValue('minimum_withdrawal', 30);
        $withdrawalFee = SystemSetting::getValue('withdrawal_fee_percentage', 10);
        
        return response()->json([
            'success' => true,
            'data' => [
                'withdrawals' => $withdrawals,
                'balance' => $balance,
                'min_withdrawal' => $minWithdrawal,
                'withdrawal_fee' => $withdrawalFee
            ]
        ]);
    }
    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:30',
            'usdt_address' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $result = WithdrawalService::requestWithdrawal(
            $request->user()->id,
            $request->input('amount'),
            $request->input('usdt_address')
        );
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'withdrawal' => $result['withdrawal']
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
        $withdrawal = WithdrawalRequest::where('user_id', $request->user()->id)
                                       ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'withdrawal' => $withdrawal
            ]
        ]);
    }
}