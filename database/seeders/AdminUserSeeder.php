<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminExists = User::where('email', 'admin@mlmplatform.com')->exists();
        
        if (!$adminExists) {
            // Create admin user
            $admin = User::create([
                'name' => 'System Administrator',
                'email' => 'admin@mlmplatform.com',
                'password' => bcrypt('admin123'),
                'referral_code' => 'ADMIN' . strtoupper(uniqid()),
                'phone' => '+1234567890',
                'status' => 'active',
                'kyc_status' => 'approved',
                'current_level' => 6, // Highest level
                'activation_amount' => 7000.00,
                'activation_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create wallet for admin
            Wallet::create([
                'user_id' => $admin->id,
                'deposit_balance' => 10000.00,
                'earning_balance' => 5000.00,
                'referral_balance' => 2000.00,
                'total_income' => 15000.00,
                'total_withdrawn' => 5000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@mlmplatform.com');
            $this->command->info('Password: admin123');
        } else {
            $this->command->info('Admin user already exists!');
        }
    }
}