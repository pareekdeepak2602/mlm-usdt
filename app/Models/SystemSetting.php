<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
    ];

    public static function getValue($key, $default = null)
    {
        $setting = self::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }

    public static function setValue($key, $value, $description = null)
    {
        return self::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'description' => $description
            ]
        );
    }

    /**
     * Get USDT BEP20 wallet address
     */
    public static function getUsdtWallet()
    {
        return self::getValue('usdt_bep20_wallet');
    }

    /**
     * Get QR code image URL
     */
    public static function getQrCode()
    {
        return self::getValue('qr_code_image');
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        return self::getValue('maintenance_mode') === 'true';
    }
}