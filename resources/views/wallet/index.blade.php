@extends('layouts.app_new')

@section('page-title', 'My Wallet')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Wallet</h1>
        <div>
            <a href="{{ route('wallet.transactions') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm me-2">
                <i class="fas fa-history fa-sm text-white-50"></i> Transaction History
            </a>
            <a href="{{ route('wallet.deposit') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus-circle fa-sm text-white-50"></i> Make Deposit
            </a>
        </div>
    </div>

    <!-- Level Status -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Your Level Status</h6>
                    <span class="badge bg-primary">Level {{ Auth::user()->current_level }}</span>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                <h5 class="text-primary">{{ Auth::user()->direct_referrals_count }}</h5>
                                <small style="color: var(--text-secondary);">Direct Referrals (A)</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                <h5 class="text-success">{{ Auth::user()->indirect_referrals_count }}</h5>
                                <small style="color: var(--text-secondary);">Indirect Referrals (B+C)</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                <h5 class="text-info">${{ number_format(Auth::user()->total_asset_hold, 2) }}</h5>
                                <small style="color: var(--text-secondary);">Total Asset Hold</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                <h5 class="text-warning">
                                    @php
                                        $nextLevel = Auth::user()->current_level + 1;
                                        $nextPlan = App\Models\InvestmentPlan::where('level', $nextLevel)->first();
                                    @endphp
                                    @if($nextPlan)
                                        Level {{ $nextLevel }}
                                    @else
                                        Max Level
                                    @endif
                                </h5>
                                <small style="color: var(--text-secondary);">Next Level</small>
                            </div>
                        </div>
                    </div>
                    @if($nextPlan)
                    <div class="mt-3 p-3 rounded" style="background: var(--bg-secondary); color: var(--text-primary);">
                        <h6 class="font-weight-bold mb-2">Requirements for Level {{ $nextLevel }}:</h6>
                        <div class="row">
                            @if($nextPlan->direct_referrals_required)
                            <div class="col-md-4">
                                <small style="color: var(--text-secondary);">Direct Referrals:</small>
                                <div class="d-flex justify-content-between">
                                    <span style="color: var(--text-primary);">{{ Auth::user()->direct_referrals_count }}/{{ $nextPlan->direct_referrals_required }}</span>
                                    <span class="badge bg-{{ Auth::user()->direct_referrals_count >= $nextPlan->direct_referrals_required ? 'success' : 'warning' }}">
                                        {{ Auth::user()->direct_referrals_count >= $nextPlan->direct_referrals_required ? 'Completed' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                            @endif
                            @if($nextPlan->indirect_referrals_required)
                            <div class="col-md-4">
                                <small style="color: var(--text-secondary);">Indirect Referrals:</small>
                                <div class="d-flex justify-content-between">
                                    <span style="color: var(--text-primary);">{{ Auth::user()->indirect_referrals_count }}/{{ $nextPlan->indirect_referrals_required }}</span>
                                    <span class="badge bg-{{ Auth::user()->indirect_referrals_count >= $nextPlan->indirect_referrals_required ? 'success' : 'warning' }}">
                                        {{ Auth::user()->indirect_referrals_count >= $nextPlan->indirect_referrals_required ? 'Completed' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-4">
                                <small style="color: var(--text-secondary);">Asset Hold:</small>
                                <div class="d-flex justify-content-between">
                                    <span style="color: var(--text-primary);">${{ number_format(Auth::user()->total_asset_hold, 2) }}/${{ number_format($nextPlan->asset_hold, 2) }}</span>
                                    <span class="badge bg-{{ Auth::user()->total_asset_hold >= $nextPlan->asset_hold ? 'success' : 'warning' }}">
                                        {{ Auth::user()->total_asset_hold >= $nextPlan->asset_hold ? 'Completed' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="background: var(--card-bg); border-color: var(--card-border); border-left: 4px solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Deposit Balance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($wallet->deposit_balance ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="background: var(--card-bg); border-color: var(--card-border); border-left: 4px solid #1cc88a !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Earning Balance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($wallet->earning_balance ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="background: var(--card-bg); border-color: var(--card-border); border-left: 4px solid #36b9cc !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Referral Balance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($wallet->referral_balance ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="background: var(--card-bg); border-color: var(--card-border); border-left: 4px solid #f6c23e !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Balance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format(($wallet->deposit_balance ?? 0) + ($wallet->earning_balance ?? 0) + ($wallet->referral_balance ?? 0), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('wallet.deposit') }}" class="btn btn-success btn-lg btn-block py-3">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                                Make Deposit<br>
                                <small class="text-light">Add funds to your account</small>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('withdraw.create') }}" class="btn btn-warning btn-lg btn-block py-3">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i><br>
                                Request Withdrawal<br>
                                <small class="text-light">Withdraw your earnings</small>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('wallet.transactions') }}" class="btn btn-info btn-lg btn-block py-3">
                                <i class="fas fa-history fa-2x mb-2"></i><br>
                                View Transactions<br>
                                <small class="text-light">Check your transaction history</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
                    <a href="{{ route('wallet.transactions') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" style="background: var(--card-bg); color: var(--text-primary);">
                                <thead style="background: var(--bg-secondary);">
                                    <tr>
                                        <th style="color: var(--text-primary);">Date</th>
                                        <th style="color: var(--text-primary);">Transaction ID</th>
                                        <th style="color: var(--text-primary);">Type</th>
                                        <th style="color: var(--text-primary);">Amount</th>
                                        <th style="color: var(--text-primary);">Status</th>
                                        <th style="color: var(--text-primary);">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td style="color: var(--text-primary);">{{ $transaction->created_at->format('M d, Y') }}</td>
                                            <td style="color: var(--text-primary);">
                                                <code>{{ substr($transaction->txn_id, 0, 8) }}...</code>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->txn_type === 'deposit' ? 'success' : ($transaction->txn_type === 'withdraw' ? 'warning' : ($transaction->txn_type === 'income' ? 'info' : 'primary')) }}">
                                                    {{ ucfirst($transaction->txn_type) }}
                                                </span>
                                            </td>
                                            <td class="font-weight-bold text-{{ $transaction->txn_type === 'withdraw' || $transaction->txn_type === 'withdrawal_fee' ? 'danger' : 'success' }}">
                                                {{ $transaction->txn_type === 'withdraw' || $transaction->txn_type === 'withdrawal_fee' ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : ($transaction->status === 'failed' ? 'danger' : 'secondary')) }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('wallet.transaction-details', $transaction->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exchange-alt fa-3x text-gray-300 mb-3"></i>
                            <h5 style="color: var(--text-secondary);">No Transactions Yet</h5>
                            <p style="color: var(--text-secondary);">You haven't made any transactions yet.</p>
                            <a href="{{ route('wallet.deposit') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i> Make Your First Deposit
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Information -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Wallet Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-primary">Balance Types</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2" style="color: var(--text-primary);">
                                    <i class="fas fa-wallet text-primary me-2"></i>
                                    <strong>Deposit Balance:</strong> Funds you have deposited
                                </li>
                                <li class="mb-2" style="color: var(--text-primary);">
                                    <i class="fas fa-chart-line text-success me-2"></i>
                                    <strong>Earning Balance:</strong> Income from investments
                                </li>
                                <li class="mb-2" style="color: var(--text-primary);">
                                    <i class="fas fa-users text-info me-2"></i>
                                    <strong>Referral Balance:</strong> Earnings from referrals
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-primary">Quick Tips</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2" style="color: var(--text-primary);">
                                    <i class="fab fa-bootstrap text-warning me-2"></i>
                                    We accept USDT BEP20 only
                                </li>
                                <li class="mb-2" style="color: var(--text-primary);">
                                    <i class="fas fa-info-circle text-warning me-2"></i>
                                    Minimum deposit: $50
                                </li>
                                <li class="mb-2" style="color: var(--text-primary);">
                                    <i class="fas fa-info-circle text-warning me-2"></i>
                                    Minimum withdrawal: $30
                                </li>
                                <li class="mb-2" style="color: var(--text-primary);">
                                    <i class="fas fa-info-circle text-warning me-2"></i>
                                    Withdrawal fee: 10%
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

/* Ensure table borders are visible in dark mode */
.table-bordered {
    border-color: var(--border-color) !important;
}

.table-bordered th,
.table-bordered td {
    border-color: var(--border-color) !important;
}

/* Card header specific styling */
.card-header {
    background: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
}

/* Ensure text colors are properly set */
.text-gray-800 {
    color: var(--text-primary) !important;
}

/* Badge styling for dark mode */
.badge.bg-light {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
}

/* Table hover effects */
.table-hover tbody tr:hover {
    background-color: var(--bg-secondary) !important;
}

/* Balance card specific styling */
.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }
.border-left-info { border-left: 4px solid #36b9cc !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
</style>
@endsection