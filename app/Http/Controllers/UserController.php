<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        $investments = $user->investments()->with('plan')->get();
        $referrals = $user->referrals()->with('referred')->get();
        $transactions = $user->transactions()->orderBy('created_at', 'desc')->limit(10)->get();
        $notifications = $user->notifications()->where('is_read', false)->get();
        
        return view('dashboard.index', compact(
            'user', 
            'wallet', 
            'investments', 
            'referrals', 
            'transactions', 
            'notifications'
        ));
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'usdt_wallet_address' => 'nullable|string|max:255',
        ]);
        
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'usdt_wallet_address' => $request->input('usdt_wallet_address'),
        ]);
        
        return redirect()->back()->with('success', 'Profile updated successfully');
    }
    
    public function changePassword()
    {
        return view('user.change-password');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
        
        $user->password = Hash::make($request->input('password'));
        $user->save();
        
        return redirect()->route('dashboard')->with('success', 'Password changed successfully');
    }
    
    public function kyc()
    {
        $user = Auth::user();
        return view('user.kyc', compact('user'));
    }
    
    public function submitKyc(Request $request)
    {
        $user = Auth::user();
        
        if ($user->kyc_status !== 'pending') {
            return back()->with('error', 'KYC already submitted');
        }
        
        $request->validate([
            'kyc_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        if ($request->hasFile('kyc_document')) {
            $file = $request->file('kyc_document');
            $path = $file->store('kyc_documents', 'public');
            
            $user->kyc_document = $path;
            $user->kyc_status = 'pending';
            $user->save();
            
            Notification::createNotification(
                $user->id,
                'KYC Submitted',
                'Your KYC document has been submitted for verification',
                'info'
            );
            
            return redirect()->route('dashboard')->with('success', 'KYC document submitted successfully');
        }
        
        return back()->with('error', 'Failed to upload KYC document');
    }
    
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(20);
        
        return view('user.notifications', compact('notifications'));
    }
    
    public function markNotificationAsRead($id)
    {
        $notification = Notification::find($id);
        
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->is_read = true;
            $notification->save();
        }
        
        return redirect()->back();
    }
    
    public function markAllNotificationsAsRead()
    {
        Auth::user()->notifications()->update(['is_read' => true]);
        
        return redirect()->back();
    }
}