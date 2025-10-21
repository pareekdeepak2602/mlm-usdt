<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\UserInvestment;
use App\Models\Transaction;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('wallet')->latest()->paginate(10);
        
        // Only pass user-related statistics for users index page
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $pendingKYC = User::where('kyc_status', 'pending')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'pendingKYC'));
    }

    public function show($id)
    {
        $user = User::with(['wallet', 'investments.plan'])->findOrFail($id);
        $transactions = Transaction::where('user_id', $id)->latest()->paginate(10);
        return view('admin.users.show', compact('user', 'transactions'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'usdt_wallet_address' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $id)->with('success', 'User updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update(['status' => $request->status]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function userInvestments($id)
    {
        $user = User::findOrFail($id);
        $investments = UserInvestment::with('plan')->where('user_id', $id)->latest()->paginate(10);
        
        return view('admin.users.investments', compact('user', 'investments'));
    }

    public function userTransactions($id)
    {
        $user = User::findOrFail($id);
        $transactions = Transaction::where('user_id', $id)->latest()->paginate(10);
        
        return view('admin.users.transactions', compact('user', 'transactions'));
    }
}