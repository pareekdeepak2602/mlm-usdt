<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->latest()->paginate(20);
        
        $filters = [
            'type' => $request->type ?? '',
            'status' => $request->status ?? '',
            'date_from' => $request->date_from ?? '',
            'date_to' => $request->date_to ?? '',
        ];

        return view('admin.transactions.index', compact('transactions', 'filters'));
    }

    public function show($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function filter(Request $request)
    {
        $query = Transaction::with('user');
        
        if ($request->type) {
            $query->where('txn_type', $request->type);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $transactions = $query->latest()->paginate(20);
        
        $filters = [
            'type' => $request->type ?? '',
            'status' => $request->status ?? '',
            'date_from' => $request->date_from ?? '',
            'date_to' => $request->date_to ?? '',
        ];

        return view('admin.transactions.index', compact('transactions', 'filters'));
    }
}