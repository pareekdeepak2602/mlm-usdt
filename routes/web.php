    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\AuthController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\InvestmentController;
    use App\Http\Controllers\WalletController;
    use App\Http\Controllers\ReferralController;
    use App\Http\Controllers\WithdrawalController;
    use App\Http\Controllers\LandingPageController;

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

    // Authentication Routes
    Route::get('/', [LandingPageController::class, 'index'])->name('landing');
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
        Route::post('/notifications/mark-all-read', [UserController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
        
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
        Route::post('/wallet/deposit', [WalletController::class, 'processDeposit'])->name('wallet.process-deposit');
        Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');
        Route::get('/wallet/transaction/{id}', [WalletController::class, 'transactionDetails'])->name('wallet.transaction-details');
        
        // Withdrawal routes (you'll need to create this controller too)
        Route::get('/withdraw', [WithdrawalController::class, 'create'])->name('withdraw.create');
        Route::get('/withdrawals/create', [WithdrawalController::class, 'create'])->name('withdraw.create');
        Route::post('/withdrawals', [WithdrawalController::class, 'store'])->name('withdraw.store');
        Route::get('/withdrawals/{id}', [WithdrawalController::class, 'show'])->name('withdrawals.show');
    });


    // Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Authentication
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
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
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/users', [ReportController::class, 'usersReport'])->name('reports.users');
        Route::get('/reports/transactions', [ReportController::class, 'transactionsReport'])->name('reports.transactions');
        Route::get('/reports/financial', [ReportController::class, 'financialReport'])->name('reports.financial');
    });
});