<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalRequest;
use App\Models\SystemSetting;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $withdrawals = $user->withdrawalRequests()->orderBy('created_at', 'desc')->get();
        $balance = \App\Services\WalletService::getBalance($user->id);
        $minWithdrawal = SystemSetting::getValue('minimum_withdrawal', 30);
        $withdrawalFee = SystemSetting::getValue('withdrawal_fee_percentage', 10);
        
        return view('withdrawals.index', compact(
            'withdrawals', 
            'balance', 
            'minWithdrawal', 
            'withdrawalFee'
        ));
    }
    
   public function create()
{
    $user = Auth::user();
    $balance = \App\Services\WalletService::getBalance($user->id);
    $minWithdrawal = SystemSetting::getValue('minimum_withdrawal', 30);
    $withdrawalFee = SystemSetting::getValue('withdrawal_fee_percentage', 10);
    
    return view('withdrawals.create', compact(
        'balance', 
        'minWithdrawal', 
        'withdrawalFee'
    ));
}
    
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:30',
            'usdt_address' => 'required|string|max:255',
        ]);
        
        $result = WithdrawalService::requestWithdrawal(
            Auth::id(),
            $request->input('amount'),
            $request->input('usdt_address')
        );
        
        if ($result['success']) {
            return redirect()->route('withdrawals.index')->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
    
    public function show($id)
    {
        $withdrawal = WithdrawalRequest::where('user_id', Auth::id())->findOrFail($id);
        
        return view('withdrawals.show', compact('withdrawal'));
    }
}