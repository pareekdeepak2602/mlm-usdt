<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InvestmentService;
use Illuminate\Support\Facades\Log;

class ProcessDailyIncome extends Command
{
    protected $signature = 'income:process-daily 
                            {--test : Process test data for today}
                            {--date= : Process for specific date (YYYY-MM-DD)}
                            {--user= : Process for specific user ID only}';

    protected $description = 'Process daily income for all active users based on their level and deposit balance';

    public function handle()
    {
        $this->info('🚀 Starting Daily Income Processing...');
        
        $startTime = microtime(true);
        
        try {
            $isTest = $this->option('test');
            $specificDate = $this->option('date');
            $specificUser = $this->option('user');
            
            if ($isTest) {
                $this->warn('🧪 TEST MODE - No actual payments will be made');
            }
            
            if ($specificDate) {
                $this->info("📅 Processing for date: {$specificDate}");
            }
            
            if ($specificUser) {
                $this->info("👤 Processing for user ID: {$specificUser}");
            }
            
            $result = InvestmentService::processDailyIncome($isTest, $specificDate, $specificUser);
            
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            
            if ($result['success']) {
                $this->info('✅ Daily Income Processing Completed Successfully!');
                $this->line('');
                $this->info('📊 Processing Statistics:');
                $this->line("   👥 Total Active Users: {$result['total_users']}");
                $this->line("   💰 Income Processed: {$result['income_processed']} users");
                $this->line("   💵 Total Amount Distributed: " . number_format($result['total_amount'], 2) . " USDT");
                $this->line("   ⏱️  Execution Time: {$executionTime} seconds");
                $this->line('');
                
                if ($isTest) {
                    $this->warn('⚠️  TEST MODE: No actual payments were made to wallets');
                }
                
            } else {
                $this->error('❌ Daily Income Processing Failed!');
                $this->error("Error: {$result['message']}");
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Exception occurred during daily income processing!');
            $this->error("Exception: {$e->getMessage()}");
            $this->error("File: {$e->getFile()}:{$e->getLine()}");
            return 1;
        }
        
        return 0;
    }
}