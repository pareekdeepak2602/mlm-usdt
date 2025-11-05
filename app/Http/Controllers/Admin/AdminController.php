<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth')->except(['showLoginForm', 'login']);
    }

    public function showLoginForm()
    {
        // If already logged in as admin, redirect to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login with admin guard
        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            
            // Check if admin is active
            if (!$admin->isActive()) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated.',
                ]);
            }

            // Update last login
            $admin->updateLastLogin();

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
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

    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $admin->update($request->only(['name', 'email', 'phone']));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $admin->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}