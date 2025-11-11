@extends('layouts.app_new')

@section('page-title', 'Transaction Details')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaction Details</h1>
        <div>
            <a href="{{ route('wallet.transactions') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Transactions
            </a>
            <a href="{{ route('wallet.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-wallet fa-sm text-white-50"></i> My Wallet
            </a>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Transaction Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered" style="background: var(--card-bg); color: var(--text-primary);">
                                <tr>
                                    <th style="background: var(--bg-secondary); color: var(--text-primary); width: 40%;">Transaction ID</th>
                                    <td>
                                        <code style="color: var(--text-primary);">{{ $transaction->txn_id }}</code>
                                        <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ $transaction->txn_id }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: var(--bg-secondary); color: var(--text-primary);">Type</th>
                                    <td>
                                        <span class="badge bg-{{ $transaction->txn_type === 'deposit' ? 'success' : ($transaction->txn_type === 'withdraw' ? 'warning' : ($transaction->txn_type === 'income' ? 'info' : 'primary')) }}">
                                            {{ ucfirst($transaction->txn_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: var(--bg-secondary); color: var(--text-primary);">Amount</th>
                                    <td class="font-weight-bold text-{{ $transaction->txn_type === 'withdraw' || $transaction->txn_type === 'withdrawal_fee' ? 'danger' : 'success' }}">
                                        {{ $transaction->txn_type === 'withdraw' || $transaction->txn_type === 'withdrawal_fee' ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: var(--bg-secondary); color: var(--text-primary);">Status</th>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : ($transaction->status === 'failed' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered" style="background: var(--card-bg); color: var(--text-primary);">
                                <tr>
                                    <th style="background: var(--bg-secondary); color: var(--text-primary); width: 40%;">Date & Time</th>
                                    <td style="color: var(--text-primary);">{{ $transaction->created_at->setTimeZone('Asia/Kolkata')->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th style="background: var(--bg-secondary); color: var(--text-primary);">BSC Transaction Hash</th>
                                    <td>
                                        @if($transaction->usdt_txn_hash)
                                            <code class="small" style="color: var(--text-primary);">{{ $transaction->usdt_txn_hash }}</code>
                                            <button class="btn btn-sm btn-outline-warning ms-2" onclick="copyToClipboard('{{ $transaction->usdt_txn_hash }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <a href="https://bscscan.com/tx/{{ $transaction->usdt_txn_hash }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-info ms-1"
                                               title="View on BSC Scan">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @else
                                            <span style="color: var(--text-secondary);">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background: var(--bg-secondary); color: var(--text-primary);">Updated At</th>
                                    <td style="color: var(--text-primary);">{{ $transaction->updated_at->setTimeZone('Asia/Kolkata')->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($transaction->details)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="font-weight-bold text-primary">Additional Details</h6>
                            <div class="card" style="background: var(--bg-secondary); border-color: var(--border-color);">
                                <div class="card-body">
                                    <p class="mb-0" style="color: var(--text-primary);">{{ $transaction->details }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('wallet.deposit') }}" class="btn btn-success btn-block">
                            <i class="fas fa-plus-circle"></i> Make Deposit
                        </a>
                        <a href="{{ route('withdraw.create') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-money-bill-wave"></i> Request Withdrawal
                        </a>
                        <a href="{{ route('wallet.transactions') }}" class="btn btn-info btn-block">
                            <i class="fas fa-history"></i> View All Transactions
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaction Status Guide -->
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Status Guide</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <p style="color: var(--text-primary);">
                            <span class="badge bg-success">Completed</span> - Transaction successfully processed
                        </p>
                        <p style="color: var(--text-primary);">
                            <span class="badge bg-warning text-dark">Pending</span> - Waiting for confirmation
                        </p>
                        <p style="color: var(--text-primary);">
                            <span class="badge bg-danger">Failed</span> - Transaction was not completed
                        </p>
                        <p style="color: var(--text-primary);">
                            <span class="badge bg-secondary">Cancelled</span> - Transaction was cancelled
                        </p>
                    </div>
                </div>
            </div>

            <!-- Transaction Timeline -->
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Transaction Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 style="color: var(--text-primary); margin-bottom: 0.25rem;">Created</h6>
                                <small style="color: var(--text-secondary);">{{ $transaction->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                        @if($transaction->status === 'completed')
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 style="color: var(--text-primary); margin-bottom: 0.25rem;">Completed</h6>
                                <small style="color: var(--text-secondary);">{{ $transaction->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                        @elseif($transaction->status === 'failed')
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 style="color: var(--text-primary); margin-bottom: 0.25rem;">Failed</h6>
                                <small style="color: var(--text-secondary);">{{ $transaction->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                        @endif
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

/* Code styling */
code {
    background: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.875em;
}

/* Timeline styling */
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    display: flex;
    align-items: flex-start;
}

.timeline-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    position: absolute;
    left: -20px;
    top: 5px;
}

.timeline-content {
    flex: 1;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-color);
}

/* Button hover effects */
.btn-outline-secondary:hover,
.btn-outline-warning:hover,
.btn-outline-info:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

/* Badge styling for dark mode */
.badge.bg-light {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
}

/* Table header styling */
.table th {
    background: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
    font-weight: 600;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        
        if (btn.classList.contains('btn-outline-secondary')) {
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
        } else if (btn.classList.contains('btn-outline-warning')) {
            btn.classList.remove('btn-outline-warning');
            btn.classList.add('btn-success');
        }
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            if (originalHTML.includes('fa-copy')) {
                if (btn.classList.contains('btn-outline-warning')) {
                    btn.classList.add('btn-outline-warning');
                } else {
                    btn.classList.add('btn-outline-secondary');
                }
            }
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        // Show error message
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-times"></i>';
        btn.classList.remove('btn-outline-secondary', 'btn-outline-warning');
        btn.classList.add('btn-danger');
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-danger');
            if (originalHTML.includes('fa-copy')) {
                if (btn.classList.contains('btn-outline-warning')) {
                    btn.classList.add('btn-outline-warning');
                } else {
                    btn.classList.add('btn-outline-secondary');
                }
            }
        }, 2000);
    });
}

// Add some interactive enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for better UX
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endpush