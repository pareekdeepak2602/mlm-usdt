<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->keyBy('setting_key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'minimum_withdrawal' => 'required|numeric|min:0',
            'withdrawal_fee_percentage' => 'required|numeric|min:0|max:100',
            'minimum_activation' => 'required|numeric|min:0',
            'referral_activation_bonus' => 'required|numeric|min:0|max:100',
            'daily_income_time' => 'required|date_format:H:i',
            'maintenance_mode' => 'required|in:true,false',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::where('setting_key', $key)->update(['setting_value' => $value]);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}