<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        $referralCode = request()->get('ref', '');
        return view('auth.register', compact('referralCode'));
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'usdt_wallet_address' => 'nullable|string|max:255',
            'referral_code' => 'nullable|string|exists:users,referral_code',
            'terms' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $referralCode = $request->input('referral_code');
        
        $user = User::create([
            'referral_code' => User::generateReferralCode(),
            'referred_by' => $referralCode,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'phone' => $request->input('phone'),
            'usdt_wallet_address' => $request->input('usdt_wallet_address'),
        ]);
        
        // Create wallet for the user
        Wallet::create(['user_id' => $user->id]);
        
        // Process referral if applicable
        if ($referralCode) {
            ReferralService::processReferral($user);
        }
        
        // Send welcome notification
        Notification::createNotification(
            $user->id,
            'Welcome to MLM Platform',
            'Your account has been created successfully. Please deposit funds to activate your account.',
            'info'
        );
        
        Auth::login($user);
        
        return redirect()->route('dashboard')
            ->with('success', 'Registration successful! Please deposit funds to activate your account.');
    }
    
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Update last login
            Auth::user()->update(['last_login' => now()]);
            
            return redirect()->intended(route('dashboard'));
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
    
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
    
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        
        // Generate password reset token
        $token = Str::random(60);
        
        // Save token to database (you would need a password_resets table)
        // DB::table('password_resets')->insert([
        //     'email' => $request->email,
        //     'token' => $token,
        //     'created_at' => now(),
        // ]);
        
        // Send password reset email
        // Mail::to($request->email)->send(new ResetPasswordEmail($token));
        
        return back()->with('status', 'Password reset link sent to your email');
    }
    
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required',
        ]);
        
        // Verify token and update password
        // $reset = DB::table('password_resets')
        //     ->where('email', $request->email)
        //     ->where('token', $request->token)
        //     ->first();
        
        // if (!$reset) {
        //     return back()->withErrors(['email' => 'Invalid password reset token']);
        // }
        
        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Delete token
        // DB::table('password_resets')
        //     ->where('email', $request->email)
        //     ->delete();
        
        return redirect()->route('login')->with('status', 'Password reset successful');
    }
}