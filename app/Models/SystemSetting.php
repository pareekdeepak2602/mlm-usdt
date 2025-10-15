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

    public static function setValue($key, $value)
    {
        return self::updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value]
        );
    }
}