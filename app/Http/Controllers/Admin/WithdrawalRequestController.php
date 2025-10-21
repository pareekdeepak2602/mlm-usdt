<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WithdrawalRequestController extends Controller
{
    public function index()
    {
        $withdrawals = WithdrawalRequest::with('user')->latest()->paginate(10);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function show($id)
    {
        $withdrawal = WithdrawalRequest::with('user')->findOrFail($id);
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function process(Request $request, $id)
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);
        
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal request has already been processed.');
        }

        \DB::transaction(function () use ($withdrawal) {
            // Update withdrawal status
            $withdrawal->update([
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Create transaction record
            Transaction::create([
                'user_id' => $withdrawal->user_id,
                'txn_id' => 'TXN' . strtoupper(uniqid()),
                'txn_type' => 'withdraw',
                'amount' => $withdrawal->amount,
                'status' => 'completed',
                'details' => 'Withdrawal processed',
            ]);

            // Update user's wallet
            $wallet = Wallet::where('user_id', $withdrawal->user_id)->first();
            if ($wallet) {
                $wallet->decrement('earning_balance', $withdrawal->amount);
                $wallet->increment('total_withdrawn', $withdrawal->amount);
            }
        });

        return back()->with('success', 'Withdrawal processed successfully.');
    }

    public function reject(Request $request, $id)
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);
        
        $request->validate([
            'admin_note' => 'required|string|max:500',
        ]);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal request has already been processed.');
        }

        $withdrawal->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
            'processed_at' => now(),
        ]);

        // Return funds to user's wallet
        $wallet = Wallet::where('user_id', $withdrawal->user_id)->first();
        if ($wallet) {
            $wallet->increment('earning_balance', $withdrawal->amount);
        }

        return back()->with('success', 'Withdrawal rejected successfully.');
    }
}