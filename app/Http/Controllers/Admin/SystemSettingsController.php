<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // We’ll use File instead of Storage for public path

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
        'usdt_bep20_wallet' => 'required|string|max:255',
    ]);

    /**
     * ============================
     * Handle QR Code Upload
     * ============================
     */
    if ($request->hasFile('qr_code_image')) {
        $request->validate([
            'qr_code_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ✅ Correct upload path: public/storage/qr-codes (no extra "public/")
        $uploadPath = public_path('storage/qr-codes');

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0775, true);
        }

        // Delete old QR code if it exists
        $oldQrCode = SystemSetting::where('setting_key', 'qr_code_image')->first();
        if ($oldQrCode && $oldQrCode->setting_value) {
            $oldFile = public_path('storage/qr-codes/' . basename($oldQrCode->setting_value));
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }
        }

        // Store new file directly in /public/storage/qr-codes
        $file = $request->file('qr_code_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($uploadPath, $filename);

        // ✅ Correct public URL (no "public/" in URL)
        $qrCodeUrl = asset('storage/qr-codes/' . $filename);

        // Update or create database record
        SystemSetting::updateOrCreate(
            ['setting_key' => 'qr_code_image'],
            [
                'setting_value' => $qrCodeUrl,
                'description' => 'QR Code for USDT BEP20 Wallet',
            ]
        );
    }

    /**
     * ============================
     * Update Other Settings
     * ============================
     */
    foreach ($validated as $key => $value) {
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
            $filePath = public_path('public/storage/qr-codes/' . basename($qrCodeSetting->setting_value));
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $qrCodeSetting->delete();

            return back()->with('success', 'QR code removed successfully.');
        }

        return back()->with('error', 'No QR code found to remove.');
    }
}
