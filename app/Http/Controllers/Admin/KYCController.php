<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class KYCController extends Controller
{
    public function index()
    {
        $users = User::whereIn('kyc_status', ['pending', 'rejected'])
                    ->whereNotNull('kyc_document')
                    ->latest()
                    ->paginate(15);
                    
        return view('admin.kyc.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.kyc.show', compact('user'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->kyc_status !== 'pending') {
            return back()->with('error', 'KYC already processed.');
        }

        $user->update(['kyc_status' => 'approved']);

        // Send notification to user
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => 'KYC Approved',
            'message' => 'Your KYC verification has been approved. You can now access all platform features.',
            'type' => 'success'
        ]);

        return back()->with('success', 'KYC approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($user->kyc_status !== 'pending') {
            return back()->with('error', 'KYC already processed.');
        }

        $user->update([
            'kyc_status' => 'rejected',
            'kyc_document' => null // Remove document so they can re-upload
        ]);

        // Send notification to user
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => 'KYC Rejected',
            'message' => 'Your KYC verification was rejected. Reason: ' . $request->rejection_reason . '. Please upload valid documents.',
            'type' => 'error'
        ]);

        return back()->with('success', 'KYC rejected successfully.');
    }
}