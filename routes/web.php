<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\WithdrawalController;

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    
    // User Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('password.change');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.update.x');
    
    // KYC
    Route::get('/kyc', [UserController::class, 'kyc'])->name('kyc');
    Route::post('/kyc', [UserController::class, 'submitKyc'])->name('kyc.submit');
    
    // Notifications
    Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
    Route::get('/notifications/{id}/read', [UserController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::get('/notifications/read-all', [UserController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
    
    // Investments
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');
    Route::get('/investments/create/{planId}', [InvestmentController::class, 'create'])->name('investments.create');
    Route::post('/investments', [InvestmentController::class, 'store'])->name('investments.store');
    Route::get('/investments/{id}', [InvestmentController::class, 'show'])->name('investments.show');
    
    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/deposit', [WalletController::class, 'processDeposit'])->name('wallet.deposit.process');
    Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');
    Route::get('/wallet/transactions/{id}', [WalletController::class, 'transactionDetails'])->name('wallet.transaction-details');
    
    // Referrals
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');
    Route::get('/referrals/tree', [ReferralController::class, 'tree'])->name('referrals.tree');
    Route::get('/referrals/earnings', [ReferralController::class, 'earnings'])->name('referrals.earnings');
    
    // Withdrawals
    Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals/{id}', [WithdrawalController::class, 'show'])->name('withdrawals.show');
});