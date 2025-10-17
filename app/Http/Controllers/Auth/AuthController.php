<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Notification;
use App\Services\WalletService;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Add this import
use Illuminate\Support\Str;
use App\Mail\WelcomeEmail;
use App\Mail\ResetPasswordEmail;
use Carbon\Carbon;

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
        ]);
        
        // Create wallet for the user
        Wallet::create(['user_id' => $user->id]);
        
        // Add 10 USDT welcome bonus to referral balance
        WalletService::addBonus($user->id, 10, 'Welcome Bonus');
        
        // Process referral if applicable
        if ($referralCode) {
            ReferralService::processReferral($user);
        }
        
        // Send welcome notification
        Notification::createNotification(
            $user->id,
            'Welcome to Smart Choice',
            'Your account has been created successfully with a 10 USDT welcome bonus. Please deposit funds to activate your account.',
            'info'
        );
        
        // Send welcome email
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            // Log error but don't stop registration process
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
        
        Auth::login($user);
        
        return redirect()->route('dashboard')
            ->with('success', 'Registration successful! You have received 10 USDT as a welcome bonus. Please deposit funds to activate your account.');
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
        
        // Check if password_resets table exists, if not create it
        if (!Schema::hasTable('password_resets')) {
            $this->createPasswordResetsTable();
        }
        
        // Delete any existing tokens for this email
        DB::table('password_resets')->where('email', $request->email)->delete();
        
        // Save token to database
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);
        
        // Send password reset email
        try {
            Mail::to($request->email)->send(new ResetPasswordEmail($token, $request->email));
            
            return back()->with('status', 'Password reset link has been sent to your email address.');
        } catch (\Exception $e) {
            \Log::error('Failed to send reset password email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send reset link. Please try again.']);
        }
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
        
        // Verify token
        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();
        
        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Invalid password reset token.']);
        }
        
        // Check if token is valid (within 60 minutes)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Password reset token has expired.']);
        }
        
        // Verify token matches
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Invalid password reset token.']);
        }
        
        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Delete token
        DB::table('password_resets')->where('email', $request->email)->delete();
        
        // Send password changed notification
        Notification::createNotification(
            $user->id,
            'Password Changed',
            'Your password has been successfully changed. If you did not make this change, please contact support immediately.',
            'warning'
        );
        
        return redirect()->route('login')->with('status', 'Password has been reset successfully. You can now login with your new password.');
    }
    
    /**
     * Create password_resets table if it doesn't exist
     */
    private function createPasswordResetsTable()
    {
        Schema::create('password_resets', function ($table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
}