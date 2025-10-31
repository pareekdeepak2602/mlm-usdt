@extends('layouts.app_new')

@section('page-title', 'Transaction History')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaction History</h1>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('wallet.index') }}" class="btn btn-sm btn-primary shadow-sm me-2 mb-2 mb-sm-0">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> <span class="d-none d-sm-inline">Back to Wallet</span>
            </a>
            <a href="{{ route('wallet.deposit') }}" class="btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus-circle fa-sm text-white-50"></i> <span class="d-none d-sm-inline">Make Deposit</span>
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
                <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary mb-2 mb-md-0">All Transactions</h6>
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
                            <table class="table table-bordered table-hover mb-0" style="background: var(--card-bg); color: var(--text-primary);">
                                <thead style="background: var(--bg-secondary);">
                                    <tr>
                                        <th style="color: var(--text-primary); min-width: 120px;">Date</th>
                                        <th style="color: var(--text-primary); min-width: 150px;">Transaction ID</th>
                                        <th style="color: var(--text-primary); min-width: 100px;">Type</th>
                                        <th style="color: var(--text-primary); min-width: 120px;">Amount</th>
                                        <th style="color: var(--text-primary); min-width: 100px;">Status</th>
                                        
                                        <th style="color: var(--text-primary); min-width: 90px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td style="color: var(--text-primary);">
                                                <div class="d-none d-md-block">{{ $transaction->created_at->format('M d, Y H:i') }}</div>
                                                <div class="d-md-none">
                                                    <div>{{ $transaction->created_at->format('M d, Y') }}</div>
                                                    <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($transaction->txn_type === 'deposit' && $transaction->usdt_txn_hash)
                                                    <code class="d-block text-truncate" style="color: var(--text-primary); max-width: 150px;" title="{{ $transaction->txn_id }}">
                                                        {{ $transaction->txn_id }}
                                                    </code>
                                                    <small class="d-block mt-1">
                                                        <a href="https://bscscan.com/tx/{{ $transaction->usdt_txn_hash }}" 
                                                           target="_blank" 
                                                           class="text-warning"
                                                           title="View on BSC Scan">
                                                            <i class="fab fa-bootstrap me-1"></i>BSC Scan
                                                        </a>
                                                    </small>
                                                @else
                                                    <code class="text-truncate d-block" style="color: var(--text-primary); max-width: 150px;" title="{{ $transaction->txn_id }}">
                                                        {{ $transaction->txn_id }}
                                                    </code>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->txn_type === 'deposit' ? 'success' : ($transaction->txn_type === 'withdraw' ? 'warning' : ($transaction->txn_type === 'income' ? 'info' : 'primary')) }}">
                                                    <span class="d-none d-sm-inline">{{ ucfirst($transaction->txn_type) }}</span>
                                                    <span class="d-sm-none">
                                                        @if($transaction->txn_type === 'deposit') DEP
                                                        @elseif($transaction->txn_type === 'withdraw') W/D
                                                        @elseif($transaction->txn_type === 'income') INC
                                                        @elseif($transaction->txn_type === 'referral') REF
                                                        @else {{ substr(ucfirst($transaction->txn_type), 0, 3) }}
                                                        @endif
                                                    </span>
                                                </span>
                                            </td>
                                            <td class="font-weight-bold text-{{ $transaction->txn_type === 'withdraw' || $transaction->txn_type === 'withdrawal_fee' ? 'danger' : 'success' }}">
                                                {{ $transaction->txn_type === 'withdraw' || $transaction->txn_type === 'withdrawal_fee' ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : ($transaction->status === 'failed' ? 'danger' : 'secondary')) }}">
                                                    <span class="d-none d-md-inline">{{ ucfirst($transaction->status) }}</span>
                                                    <span class="d-md-none">
                                                        @if($transaction->status === 'completed') OK
                                                        @elseif($transaction->status === 'pending') PEND
                                                        @elseif($transaction->status === 'failed') FAIL
                                                        @else {{ substr(ucfirst($transaction->status), 0, 4) }}
                                                        @endif
                                                    </span>
                                                </span>
                                            </td>
                                         
                                            <td>
                                                <a href="{{ route('wallet.transaction-details', $transaction->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye d-none d-sm-inline"></i>
                                                    <span class="d-sm-none">View</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                            <div style="color: var(--text-secondary);" class="mb-2 mb-md-0 text-center text-md-start">
                                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions
                            </div>
                            <div class="mt-2 mt-md-0">
                                {{ $transactions->onEachSide(1)->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exchange-alt fa-4x text-gray-300 mb-3"></i>
                            <h5 style="color: var(--text-secondary);">No Transactions Found</h5>
                            <p style="color: var(--text-secondary);" class="mb-4">You haven't made any transactions yet.</p>
                            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                                <a href="{{ route('wallet.deposit') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> Make Deposit
                                </a>
                                <a href="{{ route('wallet.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-wallet"></i> Back to Wallet
                                </a>
                            </div>
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
    padding: 0.375rem 0.75rem;
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
    font-size: 0.8em;
}

/* Balance card specific styling */
.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
.border-left-danger { border-left: 4px solid #e74a3b !important; }

/* Mobile-specific optimizations */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
        border: 1px solid var(--border-color);
        border-radius: 0.35rem;
    }
    
    .table td, .table th {
        padding: 0.5rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .pagination .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .d-sm-flex h1 {
        font-size: 1.5rem;
    }
    
    .stat-card .h5 {
        font-size: 1.25rem;
    }
}

/* Text truncation for long content */
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Ensure proper spacing on mobile */
.mb-2-mobile {
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .mb-2-mobile {
        margin-bottom: 1rem;
    }
}
</style>

@push('scripts')
<script>
// Enhance mobile experience
document.addEventListener('DOMContentLoaded', function() {
    // Add touch-friendly enhancements
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function(e) {
            // Don't trigger if user clicked a button or link
            if (!e.target.closest('a') && !e.target.closest('button')) {
                const detailsLink = this.querySelector('a[href*="transaction-details"]');
                if (detailsLink) {
                    detailsLink.click();
                }
            }
        });
    });

    // Enhance dropdown for mobile
    const filterDropdown = document.getElementById('filterDropdown');
    if (filterDropdown) {
        filterDropdown.addEventListener('click', function() {
            // Ensure dropdown is properly positioned on mobile
            const dropdownMenu = this.nextElementSibling;
            if (window.innerWidth < 768) {
                dropdownMenu.style.width = '100%';
            }
        });
    }
});
</script>
@endpush
@endsection