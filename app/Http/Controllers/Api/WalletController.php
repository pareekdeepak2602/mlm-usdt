<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function balance(Request $request)
    {
        $balance = WalletService::getBalance($request->user()->id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'balance' => $balance
            ]
        ]);
    }
    
    public function transactions(Request $request)
    {
        $transactions = $request->user()
                                ->transactions()
                                ->orderBy('created_at', 'desc')
                                ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => [
                'transactions' => $transactions
            ]
        ]);
    }
    
    public function deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:50',
            'txn_hash' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $result = WalletService::deposit(
            $request->user()->id,
            $request->input('amount'),
            $request->input('txn_hash')
        );
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }
    }
}