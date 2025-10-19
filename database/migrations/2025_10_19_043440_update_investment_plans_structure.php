<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add new columns to investment_plans table
        Schema::table('investment_plans', function (Blueprint $table) {
            $table->integer('level')->default(0)->after('id');
            $table->decimal('asset_hold', 18, 2)->default(0)->after('max_investment');
            $table->integer('direct_referrals_required')->nullable()->after('asset_hold');
            $table->integer('indirect_referrals_required')->nullable()->after('direct_referrals_required');
            $table->boolean('is_popular')->default(0)->after('status');
        });

        // Update existing plans
        DB::table('investment_plans')->where('id', 1)->update([
            'name' => 'Level 0 - Starter',
            'level' => 0,
            'min_investment' => 50.00,
            'max_investment' => 99.00,
            'daily_percentage' => 1.00,
            'asset_hold' => 50.00,
            'direct_referrals_required' => null,
            'indirect_referrals_required' => null,
        ]);

        DB::table('investment_plans')->where('id', 2)->update([
            'name' => 'Level 1 - Growth',
            'level' => 1,
            'min_investment' => 100.00,
            'max_investment' => 299.00,
            'daily_percentage' => 1.80,
            'asset_hold' => 100.00,
            'direct_referrals_required' => null,
            'indirect_referrals_required' => null,
            'is_popular' => 1,
        ]);

        DB::table('investment_plans')->where('id', 3)->update([
            'name' => 'Level 2 - Premium',
            'level' => 2,
            'min_investment' => 300.00,
            'max_investment' => 699.00,
            'daily_percentage' => 2.10,
            'asset_hold' => 300.00,
            'direct_referrals_required' => 3,
            'indirect_referrals_required' => 2,
        ]);

        DB::table('investment_plans')->where('id', 4)->update([
            'name' => 'Level 3 - Advanced',
            'level' => 3,
            'min_investment' => 700.00,
            'max_investment' => 1499.00,
            'daily_percentage' => 2.60,
            'asset_hold' => 700.00,
            'direct_referrals_required' => 6,
            'indirect_referrals_required' => 12,
        ]);

        DB::table('investment_plans')->where('id', 5)->update([
            'name' => 'Level 4 - Professional',
            'level' => 4,
            'min_investment' => 1500.00,
            'max_investment' => 3499.00,
            'daily_percentage' => 3.10,
            'asset_hold' => 1500.00,
            'direct_referrals_required' => 15,
            'indirect_referrals_required' => 30,
        ]);

        DB::table('investment_plans')->where('id', 6)->update([
            'name' => 'Level 5 - Expert',
            'level' => 5,
            'min_investment' => 3500.00,
            'max_investment' => 6999.00,
            'daily_percentage' => 3.70,
            'asset_hold' => 3500.00,
            'direct_referrals_required' => 25,
            'indirect_referrals_required' => 60,
        ]);

        // Add new Level 6 plan
        DB::table('investment_plans')->insert([
            'name' => 'Level 6 - Elite',
            'level' => 6,
            'min_investment' => 7000.00,
            'max_investment' => null,
            'daily_percentage' => 4.10,
            'duration_days' => 365,
            'asset_hold' => 7000.00,
            'direct_referrals_required' => 35,
            'indirect_referrals_required' => 100,
            'status' => 'active',
            'is_popular' => 0,
        ]);

        // Add tracking columns to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('current_level')->default(0)->after('activation_date');
            $table->integer('direct_referrals_count')->default(0)->after('current_level');
            $table->integer('indirect_referrals_count')->default(0)->after('direct_referrals_count');
            $table->decimal('total_asset_hold', 18, 2)->default(0)->after('indirect_referrals_count');
        });
    }

    public function down(): void
    {
        // Reverse the changes (optional, if you want rollback support)
        Schema::table('investment_plans', function (Blueprint $table) {
            $table->dropColumn([
                'level',
                'asset_hold',
                'direct_referrals_required',
                'indirect_referrals_required',
                'is_popular',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'current_level',
                'direct_referrals_count',
                'indirect_referrals_count',
                'total_asset_hold',
            ]);
        });

        DB::table('investment_plans')->where('level', 6)->delete(); // Remove Level 6
    }
};
