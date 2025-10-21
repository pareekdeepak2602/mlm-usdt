<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'active_users' => \App\Models\User::where('status', 'active')->count(),
            'total_investments' => \App\Models\UserInvestment::count(),
            'pending_withdrawals' => \App\Models\WithdrawalRequest::where('status', 'pending')->count(),
            'total_deposits' => \App\Models\Transaction::where('txn_type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_withdrawals' => \App\Models\Transaction::where('txn_type', 'withdraw')->where('status', 'completed')->sum('amount'),
        ];

        $recentUsers = \App\Models\User::latest()->take(5)->get();
        $recentWithdrawals = \App\Models\WithdrawalRequest::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentWithdrawals'));
    }
}