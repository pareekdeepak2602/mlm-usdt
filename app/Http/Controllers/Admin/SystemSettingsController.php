<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->keyBy('setting_key');
        return view('admin.settings.index', compact('settings'));
    }

 public function update(Request $request)
{
    // Remove the dd() - it's stopping execution
    // dd($request->all());

    $validated = $request->validate([
        'minimum_withdrawal' => 'required|numeric|min:0',
        'withdrawal_fee_percentage' => 'required|numeric|min:0|max:100',
        'minimum_activation' => 'required|numeric|min:0',
        'referral_activation_bonus' => 'required|numeric|min:0|max:100',
        'daily_income_time' => 'required|date_format:H:i',
        'maintenance_mode' => 'required|in:true,false',
        'usdt_bep20_wallet' => 'required|string|max:255',
    ]);

    // Handle QR code upload separately since it's a file
    if ($request->hasFile('qr_code_image')) {
        $request->validate([
            'qr_code_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old QR code if exists
        $oldQrCode = SystemSetting::where('setting_key', 'qr_code_image')->first();
        if ($oldQrCode && $oldQrCode->setting_value) {
            // Extract filename from URL or path
            $filename = basename($oldQrCode->setting_value);
            Storage::delete('public/qr-codes/' . $filename);
        }

        // Store new QR code
        $qrCodePath = $request->file('qr_code_image')->store('qr-codes', 'public');
        $qrCodeUrl = Storage::url($qrCodePath);
        
        // Update QR code setting
        SystemSetting::updateOrCreate(
            ['setting_key' => 'qr_code_image'],
            [
                'setting_value' => $qrCodeUrl, 
                'description' => 'QR Code for USDT BEP20 Wallet'
            ]
        );
    }

    // Update all other settings
    foreach ($validated as $key => $value) {
        // Skip QR code image as it's handled separately above
        if ($key === 'qr_code_image') {
            continue;
        }
        
        SystemSetting::updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value]
        );
    }

    return back()->with('success', 'Settings updated successfully.');
}

    public function removeQrCode()
    {
        $qrCodeSetting = SystemSetting::where('setting_key', 'qr_code_image')->first();
        
        if ($qrCodeSetting && $qrCodeSetting->setting_value) {
            // Delete file from storage
            Storage::delete('public/qr-codes/' . basename($qrCodeSetting->setting_value));
            
            // Remove from database
            $qrCodeSetting->delete();
            
            return back()->with('success', 'QR code removed successfully.');
        }

        return back()->with('error', 'No QR code found to remove.');
    }
}