<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportInquiry;
use App\Models\SupportSetting;
use Illuminate\Http\Request;

class AdminSupportController extends Controller
{
    public function index()
    {
        $supportSettings = SupportSetting::getAllSettings();
       
        return view('admin.support.index', compact('supportSettings'));
    }

    public function updateSettings(Request $request)
    {
        $settings = $request->except('_token');
        
        foreach ($settings as $key => $value) {
            SupportSetting::setValue($key, $value);
        }

        return redirect()->back()->with('success', 'Support settings updated successfully.');
    }

    public function inquiries()
    {
        $inquiries = SupportInquiry::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.support.inquiries', compact('inquiries'));
    }

    public function showInquiry(SupportInquiry $inquiry)
    {
        return response()->json([
            'inquiry' => $inquiry->load('user')
        ]);
    }

    public function updateInquiryStatus(Request $request, SupportInquiry $inquiry)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
            'admin_notes' => 'nullable|string'
        ]);

        $inquiry->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'resolved_at' => $request->status === 'resolved' ? now() : null
        ]);

        return redirect()->back()->with('success', 'Inquiry status updated successfully.');
    }
}