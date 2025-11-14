    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\AuthController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\InvestmentController;
    use App\Http\Controllers\WalletController;
    use App\Http\Controllers\ReferralController;
    use App\Http\Controllers\WithdrawalController;
    use App\Http\Controllers\LandingPageController;
    use App\Http\Controllers\SupportController;

    // Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\InvestmentPlanController;
use App\Http\Controllers\Admin\WithdrawalRequestController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\Admin\KYCController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\LevelCommissionController;
use App\Http\Controllers\Admin\AdminSupportController;


    // Authentication Routes
    Route::get('/', [LandingPageController::class, 'index'])->name('landing');
    Route::get('/terms', function () {
    return view('legal.terms');
})->name('legal.terms');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('legal.privacy');

Route::get('/about', function () {
    return view('legal.about');
})->name('legal.about');

Route::get('/refund-policy', function () {
    return view('legal.refund');
})->name('legal.refund');

Route::get('/risk-disclosure', function () {
    return view('legal.risk');
})->name('legal.risk');
    Route::post('/contact', [LandingPageController::class, 'submitContact'])->name('contact.submit');

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
        
        Route::get('/kyc', [UserController::class, 'kyc'])->name('user.kyc');
        Route::post('/kyc', [UserController::class, 'submitKyc'])->name('kyc.submit');
        
        // KYC
        Route::get('/kyc', [UserController::class, 'kyc'])->name('kyc');
        Route::post('/kyc', [UserController::class, 'submitKyc'])->name('kyc.submit');
        
        // Notifications
        Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
        Route::get('/notifications/{id}/mark-read', [UserController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
        Route::get('/notifications/mark-all-read', [UserController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
        
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
        Route::get('/withdrawals/create', [WithdrawalController::class, 'create'])->name('withdraw.create');
        Route::post('/withdrawals', [WithdrawalController::class, 'store'])->name('withdrawals.store');
        Route::get('/withdrawals/{id}', [WithdrawalController::class, 'show'])->name('withdrawals.show');

            Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
        Route::get('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
        Route::post('/wallet/check-realtime-status', [WalletController::class, 'getRealTimeStatus'])
    ->name('wallet.realtime-status');
        Route::post('/wallet/deposit', [WalletController::class, 'processDeposit'])->name('wallet.process-deposit');
        Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');
        Route::get('/wallet/transaction/{id}', [WalletController::class, 'transactionDetails'])->name('wallet.transaction-details');
        Route::post('/wallet/check-transaction-status', [WalletController::class, 'checkTransactionStatus'])
    ->name('wallet.check-transaction-status')
    ->middleware('auth');
        // Withdrawal routes (you'll need to create this controller too)
        Route::get('/withdraw', [WithdrawalController::class, 'create'])->name('withdraw.create');
        Route::get('/withdrawals/create', [WithdrawalController::class, 'create'])->name('withdraw.create');
        Route::post('/withdrawals', [WithdrawalController::class, 'store'])->name('withdraw.store');
        Route::get('/withdrawals/{id}', [WithdrawalController::class, 'show'])->name('withdrawals.show');

         Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::post('/support/inquiry', [SupportController::class, 'storeInquiry'])->name('support.store');
    });


    // Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Authentication
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('/withdrawals/blockchain-status', [WithdrawalRequestController::class, 'checkBlockchainStatus'])->name('withdrawals.blockchain-status');

    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
         Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::post('/change-password', [AdminController::class, 'changePassword'])->name('password.update');
        // User Management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
        Route::post('/users/{id}/status', [UserManagementController::class, 'updateStatus'])->name('users.status');
        Route::get('/users/{id}/investments', [UserManagementController::class, 'userInvestments'])->name('users.investments');
        Route::get('/users/{id}/transactions', [UserManagementController::class, 'userTransactions'])->name('users.transactions');
        
        // Investment Plans
        Route::get('/plans', [InvestmentPlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/create', [InvestmentPlanController::class, 'create'])->name('plans.create');
        Route::post('/plans', [InvestmentPlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/{id}/edit', [InvestmentPlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{id}', [InvestmentPlanController::class, 'update'])->name('plans.update');
        Route::post('/plans/{id}/status', [InvestmentPlanController::class, 'updateStatus'])->name('plans.status');
        Route::delete('/plans/{id}', [InvestmentPlanController::class, 'destroy'])->name('plans.destroy');
        
        // Withdrawal Requests
        Route::get('/withdrawals', [WithdrawalRequestController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/{id}', [WithdrawalRequestController::class, 'show'])->name('withdrawals.show');
        Route::post('/withdrawals/{id}/process', [WithdrawalRequestController::class, 'process'])->name('withdrawals.process');
        Route::post('/withdrawals/{id}/reject', [WithdrawalRequestController::class, 'reject'])->name('withdrawals.reject');
        // Bulk withdrawal actions
Route::post('/withdrawals/bulk-action', [WithdrawalRequestController::class, 'bulkAction'])->name('withdrawals.bulk-action');
Route::get('/withdrawals/stats', [WithdrawalRequestController::class, 'getStats'])->name('withdrawals.stats');
        
        // Transactions
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
        
        // KYC Management
        Route::get('/kyc', [KYCController::class, 'index'])->name('kyc.index');
        Route::get('/kyc/{id}', [KYCController::class, 'show'])->name('kyc.show');
        Route::post('/kyc/{id}/approve', [KYCController::class, 'approve'])->name('kyc.approve');
        Route::post('/kyc/{id}/reject', [KYCController::class, 'reject'])->name('kyc.reject');
        
        // Contact Messages
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::get('/contact-messages/{id}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
        Route::post('/contact-messages/{id}/mark-read', [ContactMessageController::class, 'markAsRead'])->name('contact-messages.mark-read');
        Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
        
        // System Settings
        Route::get('/settings', [SystemSettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SystemSettingsController::class, 'update'])->name('settings.update');
         Route::delete('/settings/remove-qr-code', [SystemSettingsController::class, 'removeQrCode'])->name('settings.remove-qr-code');
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/users', [ReportController::class, 'usersReport'])->name('reports.users');
        Route::get('/reports/transactions', [ReportController::class, 'transactionsReport'])->name('reports.transactions');
        Route::get('/reports/financial', [ReportController::class, 'financialReport'])->name('reports.financial');
//level commision
        Route::get('/level-commissions', [LevelCommissionController::class, 'index'])->name('level-commissions.index');
Route::get('/level-commissions/create', [LevelCommissionController::class, 'create'])->name('level-commissions.create');
Route::post('/level-commissions', [LevelCommissionController::class, 'store'])->name('level-commissions.store');
Route::get('/level-commissions/{id}', [LevelCommissionController::class, 'show'])->name('level-commissions.show');
Route::get('/level-commissions/{id}/edit', [LevelCommissionController::class, 'edit'])->name('level-commissions.edit');
Route::put('/level-commissions/{id}', [LevelCommissionController::class, 'update'])->name('level-commissions.update');
Route::delete('/level-commissions/{id}', [LevelCommissionController::class, 'destroy'])->name('level-commissions.destroy');
   
Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [AdminSupportController::class, 'index'])->name('index');
        Route::post('/settings', [AdminSupportController::class, 'updateSettings'])->name('settings.update');
        Route::get('/inquiries', [AdminSupportController::class, 'inquiries'])->name('inquiries');
        Route::get('/inquiries/{inquiry}', [AdminSupportController::class, 'showInquiry'])->name('inquiries.show');
        Route::put('/inquiries/{inquiry}/status', [AdminSupportController::class, 'updateInquiryStatus'])->name('inquiries.update-status');
    });
});

});