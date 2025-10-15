<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\WithdrawalController;

// API Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // Wallet
    Route::get('/wallet/balance', [WalletController::class, 'balance']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
    Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
    
    // Investments
    Route::get('/investment-plans', [InvestmentController::class, 'plans']);
    Route::get('/investments', [InvestmentController::class, 'investments']);
    Route::post('/investments', [InvestmentController::class, 'create']);
    Route::get('/investments/{id}', [InvestmentController::class, 'show']);
    
    // Referrals
    Route::get('/referrals', [ReferralController::class, 'info']);
    Route::get('/referrals/tree', [ReferralController::class, 'tree']);
    Route::get('/referrals/earnings', [ReferralController::class, 'earnings']);
    
    // Withdrawals
    Route::get('/withdrawals', [WithdrawalController::class, 'index']);
    Route::post('/withdrawals', [WithdrawalController::class, 'create']);
    Route::get('/withdrawals/{id}', [WithdrawalController::class, 'show']);
});