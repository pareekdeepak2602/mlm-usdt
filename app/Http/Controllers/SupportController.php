<?php

namespace App\Http\Controllers;

use App\Models\SupportInquiry;
use App\Models\SupportSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $supportSettings = SupportSetting::getAllSettings();
        
        return view('support.index', compact('supportSettings'));
    }

    public function storeInquiry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);

        SupportInquiry::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Your inquiry has been submitted successfully. We will get back to you soon.');
    }
}