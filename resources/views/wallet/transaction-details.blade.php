@extends('layouts.app')

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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaction Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="bg-light">Transaction ID</th>
                                    <td>
                                        <code>{{ $transaction->txn_id }}</code>
                                        <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ $transaction->txn_id }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Type</th>
                                    <td>
                                        <span class="badge bg-{{ $transaction->txn_type === 'deposit' ? 'success' : ($transaction->txn_type === 'withdraw' ? 'warning' : ($transaction->txn_type === 'income' ? 'info' : 'primary')) }}">
                                            {{ ucfirst($transaction->txn_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Amount</th>
                                    <td class="font-weight-bold text-{{ $transaction->txn_type === 'withdraw' || $transaction->txn_type === 'withdrawal_fee' ? 'danger' : 'success' }}">
                                        {{ $transaction->txn_type === 'withdraw' || $transaction->txn_type === 'withdrawal_fee' ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Status</th>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : ($transaction->status === 'failed' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="bg-light">Date & Time</th>
                                    <td>{{ $transaction->created_at->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                                <tr>
    <th class="bg-light">BSC Transaction Hash</th>
    <td>
        @if($transaction->usdt_txn_hash)
            <code class="small">{{ $transaction->usdt_txn_hash }}</code>
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
            <span class="text-muted">N/A</span>
        @endif
    </td>
</tr>
                                <tr>
                                    <th class="bg-light">Updated At</th>
                                    <td>{{ $transaction->updated_at->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($transaction->details)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="font-weight-bold text-primary">Additional Details</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $transaction->details }}</p>
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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Guide</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <p><span class="badge bg-success">Completed</span> - Transaction successfully processed</p>
                        <p><span class="badge bg-warning">Pending</span> - Waiting for confirmation</p>
                        <p><span class="badge bg-danger">Failed</span> - Transaction was not completed</p>
                        <p><span class="badge bg-secondary">Cancelled</span> - Transaction was cancelled</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endpush