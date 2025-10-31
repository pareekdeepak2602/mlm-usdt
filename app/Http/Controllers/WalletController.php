<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Services\WalletService;
use App\Services\LevelService;
use App\Services\TransactionVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    protected $transactionVerifier;

    public function __construct(TransactionVerificationService $transactionVerifier)
    {
        $this->transactionVerifier = $transactionVerifier;
    }

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
        ], [
            'txn_hash.required' => 'Transaction hash is required.',
            'txn_hash.max' => 'Transaction hash is too long.',
        ]);

        // Manual validation for transaction hash format
        $txnHash = $request->input('txn_hash');
        // if (!preg_match('/^0x[a-fA-F0-9]{64}$/', $txnHash)) {
        //     return redirect()->back()
        //                    ->with('error', 'Invalid transaction hash format. Must be a valid BSC transaction hash (0x followed by 64 hexadecimal characters).')
        //                    ->withInput();
        // }

        $user = Auth::user();
        
        // Use the transaction verification service
        $result = $this->transactionVerifier->verifyAndProcessDeposit(
            $user->id,
            $request->input('amount'),
            $txnHash
        );
        
        if ($result['success']) {
            return redirect()->route('wallet.index')
                           ->with('success', $result['message'])
                           ->with('transaction_id', $result['transaction_id'] ?? null);
        } else {
            return redirect()->back()
                           ->with('error', $result['message'])
                           ->withInput();
        }
    }

    /**
     * Check transaction status via AJAX
     */
    public function checkTransactionStatus(Request $request)
    {
        $request->validate([
            'txn_hash' => 'required|string'
        ]);

        $result = $this->transactionVerifier->getTransactionStatus($request->txn_hash);
        
        return response()->json($result);
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