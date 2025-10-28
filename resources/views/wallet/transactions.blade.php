@extends('layouts.app_new')

@section('page-title', 'Transaction History')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaction History</h1>
        <div>
            <a href="{{ route('wallet.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Wallet
            </a>
            <a href="{{ route('wallet.deposit') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus-circle fa-sm text-white-50"></i> Make Deposit
            </a>
        </div>
    </div>

    <!-- Transaction Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="background: var(--card-bg); border-color: var(--card-border); border-left: 4px solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Transactions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $transactions->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
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
                                Completed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $transactions->where('status', 'completed')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $transactions->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="background: var(--card-bg); border-color: var(--card-border); border-left: 4px solid #e74a3b !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Failed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $transactions->where('status', 'failed')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">All Transactions</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterDropdown" style="background: var(--card-bg); border-color: var(--border-color);">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => '']) }}" style="color: var(--text-primary);">All Types</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'deposit']) }}" style="color: var(--text-primary);">Deposits Only</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'withdraw']) }}" style="color: var(--text-primary);">Withdrawals Only</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'income']) }}" style="color: var(--text-primary);">Income Only</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'referral']) }}" style="color: var(--text-primary);">Referrals Only</a></li>
                        </ul>
                    </div>
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
                                        <th style="color: var(--text-primary);">Details</th>
                                        <th style="color: var(--text-primary);">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td style="color: var(--text-primary);">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                            @if($transaction->txn_type === 'deposit' && $transaction->usdt_txn_hash)
                                            <td>
                                                <code style="color: var(--text-primary);">{{ $transaction->txn_id }}</code>
                                                <br>
                                                <small>
                                                    <a href="https://bscscan.com/tx/{{ $transaction->usdt_txn_hash }}" 
                                                       target="_blank" 
                                                       class="text-warning"
                                                       title="View on BSC Scan">
                                                        <i class="fab fa-bootstrap me-1"></i>View on BSC Scan
                                                    </a>
                                                </small>
                                            </td>
                                            @else
                                            <td>
                                                <code style="color: var(--text-primary);">{{ $transaction->txn_id }}</code>
                                            </td>
                                            @endif
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
                                                <small style="color: var(--text-secondary);">{{ $transaction->details ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('wallet.transaction-details', $transaction->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div style="color: var(--text-secondary);">
                                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions
                            </div>
                            <div>
                                {{ $transactions->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exchange-alt fa-4x text-gray-300 mb-3"></i>
                            <h5 style="color: var(--text-secondary);">No Transactions Found</h5>
                            <p style="color: var(--text-secondary);">You haven't made any transactions yet.</p>
                            <a href="{{ route('wallet.deposit') }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus-circle"></i> Make Deposit
                            </a>
                            <a href="{{ route('wallet.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-wallet"></i> Back to Wallet
                            </a>
                        </div>
                    @endif
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

/* Pagination styling */
.pagination .page-link {
    background: var(--card-bg) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
}

.pagination .page-item.active .page-link {
    background-color: #667eea !important;
    border-color: #667eea !important;
    color: white !important;
}

.pagination .page-item.disabled .page-link {
    background: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-secondary) !important;
}

/* Dropdown menu styling */
.dropdown-menu {
    background: var(--card-bg) !important;
    border-color: var(--border-color) !important;
}

.dropdown-item {
    color: var(--text-primary) !important;
}

.dropdown-item:hover {
    background: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
}

/* Code styling */
code {
    background: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
    padding: 2px 6px;
    border-radius: 3px;
}

/* Balance card specific styling */
.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
.border-left-danger { border-left: 4px solid #e74a3b !important; }
</style>
@endsection