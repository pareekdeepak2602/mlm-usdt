<?php

namespace Database\Seeders;

use App\Models\SupportSetting;
use Illuminate\Database\Seeder;

class SupportSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['key' => 'email', 'value' => 'support@mlmusdt.com', 'group' => 'contact'],
            ['key' => 'phone', 'value' => '+1234567890', 'group' => 'contact'],
            ['key' => 'whatsapp_number', 'value' => '1234567890', 'group' => 'contact'],
            ['key' => 'telegram_link', 'value' => 'https://t.me/mlmusdt_support', 'group' => 'contact'],
            ['key' => 'response_time', 'value' => '24-48 hours', 'group' => 'contact'],
            ['key' => 'working_hours', 'value' => '24/7', 'group' => 'contact'],
            ['key' => 'support_type', 'value' => 'Technical & Account Support', 'group' => 'contact'],
        ];

        foreach ($settings as $setting) {
            SupportSetting::create($setting);
        }
    }
}