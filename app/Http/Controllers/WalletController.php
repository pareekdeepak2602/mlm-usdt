<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Services\WalletService;
use App\Services\LevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        $balance = WalletService::getBalance($user->id);
        $transactions = $user->transactions()->orderBy('created_at', 'desc')->paginate(20);
        
        return view('wallet.index', compact('wallet', 'balance', 'transactions'));
    }
    
    public function deposit()
    {
        $user = Auth::user();
        $balance = WalletService::getBalance($user->id);
        
        return view('wallet.deposit', compact('balance'));
    }
    
    public function processDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50',
            'txn_hash' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        
        // Check level-based restrictions before processing
        $levelCheck = WalletService::checkDepositLimitByLevel($user, $request->input('amount'));
        if (!$levelCheck['success']) {
            return redirect()->back()->with('error', $levelCheck['message'])->withInput();
        }
        
        $result = WalletService::deposit(
            $user->id,
            $request->input('amount'),
            $request->input('txn_hash')
        );
        
        if ($result['success']) {
            return redirect()->route('wallet.index')->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message'])->withInput();
        }
    }
    
    public function transactions()
    {
        $user = Auth::user();
        $transactions = $user->transactions()->orderBy('created_at', 'desc')->paginate(20);
        
        return view('wallet.transactions', compact('transactions'));
    }
    
    public function transactionDetails($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        
        return view('wallet.transaction-details', compact('transaction'));
    }
}