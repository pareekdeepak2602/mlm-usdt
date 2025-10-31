@extends('layouts.app_new')

@section('page-title', 'Make Deposit')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Make Deposit</h1>
        <div>
            <a href="{{ route('wallet.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Wallet
            </a>
            <a href="{{ route('wallet.transactions') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-history fa-sm text-white-50"></i> Transaction History
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Deposit Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Deposit Funds</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            @if(session('transaction_id'))
                                <br><small>Transaction ID: <strong>{{ session('transaction_id') }}</strong></small>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- User Level Info -->
                    @php
                        $user = Auth::user();
                        $depositLimits = App\Services\WalletService::getDepositLimitsByLevel($user->current_level);
                    @endphp
                    
                    <div class="alert alert-info mb-4" style="background: rgba(23, 162, 184, 0.1); border-color: rgba(23, 162, 184, 0.2); color: var(--text-primary);">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1" style="color: var(--text-primary);">Your Current Level: <span class="badge bg-primary">Level {{ $user->current_level }}</span></h6>
                                <p class="mb-1" style="color: var(--text-primary);">Deposit Limits: 
                                    <strong>${{ number_format($depositLimits['min_deposit'], 2) }} 
                                    @if($depositLimits['max_deposit'])
                                        - ${{ number_format($depositLimits['max_deposit'], 2) }}
                                    @else
                                        and above
                                    @endif
                                    </strong>
                                </p>
                                <small style="color: var(--text-secondary);">Asset Hold Required: ${{ number_format($depositLimits['asset_hold_required'], 2) }}</small>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('wallet.process-deposit') }}" id="depositForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label" style="color: var(--text-primary);">Deposit Amount (USDT)</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: var(--bg-secondary); border-color: var(--border-color); color: var(--text-primary);">$</span>
                                        <input type="number" 
                                               class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" 
                                               name="amount" 
                                               value="{{ old('amount', '100') }}" 
                                               step="0.01" 
                                               min="{{ $depositLimits['min_deposit'] }}" 
                                               @if($depositLimits['max_deposit'])
                                                   max="{{ $depositLimits['max_deposit'] }}"
                                               @endif
                                               placeholder="Enter amount"
                                               required
                                               style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text" style="color: var(--text-secondary);">
                                        Minimum: ${{ number_format($depositLimits['min_deposit'], 2) }}
                                        @if($depositLimits['max_deposit'])
                                            | Maximum: ${{ number_format($depositLimits['max_deposit'], 2) }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--text-primary);">Your Current Balance</label>
                                    <div class="card" style="background: var(--bg-secondary); border-color: var(--border-color);">
                                        <div class="card-body text-center">
                                            <h4 class="text-primary">${{ number_format($balance['total'] ?? 0, 2) }}</h4>
                                            <small style="color: var(--text-secondary);">Total Available Balance</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="txn_hash" class="form-label" style="color: var(--text-primary);">BSC Transaction Hash (BEP20)</label>
                            <input type="text" 
                                   class="form-control @error('txn_hash') is-invalid @enderror" 
                                   id="txn_hash" 
                                   name="txn_hash" 
                                   value="{{ old('txn_hash', '0x8f3e2c1a0b9c8d7e6f5a4b3c2d1e0f9a8b7c6d5e4f3a2b1c0d9e8f7a6b5c4d3e2') }}" 
                                   placeholder="Enter your BSC transaction hash (0x...)"
                                   required
                                   style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                            @error('txn_hash')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text" style="color: var(--text-secondary);">
                                Copy the transaction hash from your wallet after making the BEP20 transfer. Format: 0x followed by 64 hexadecimal characters.
                            </small>
                        </div>

                        <div class="alert alert-warning" style="background: rgba(255, 193, 7, 0.1); border-color: rgba(255, 193, 7, 0.2); color: var(--text-primary);">
                            <h6 class="alert-heading" style="color: var(--text-primary);"><i class="fas fa-exclamation-triangle me-2"></i>Important BEP20 Instructions</h6>
                            <ul class="mb-0" style="color: var(--text-primary);">
                                <li>Send <strong>USDT BEP20</strong> only to our wallet address</li>
                                <li>Ensure you are on <strong>Binance Smart Chain (BSC)</strong> network</li>
                                <li>Copy and paste the BSC transaction hash correctly (0x... format)</li>
                                <li>Minimum deposit amount is ${{ number_format($depositLimits['min_deposit'], 2) }} USDT</li>
                                <li>Keep sufficient BNB for transaction fees</li>
                                <li>Transaction verification may take 2-3 minutes</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i> Submit Deposit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Deposit Information -->
        <div class="col-lg-4">
            <!-- BEP20 Wallet Address -->
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bs-warning); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fab fa-bootstrap me-2"></i>Our USDT BEP20 Wallet Address
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="p-3 rounded border" style="background: var(--bg-secondary); border-color: var(--border-color) !important;">
                            <code class="small" id="walletAddress" style="color: var(--text-primary); word-break: break-all;">0x742E4D6c4C8B6C4D8E6F7C5A3B2C1D0E9F8A7B6C</code>
                            <button class="btn btn-sm btn-outline-warning ms-2" onclick="copyWalletAddress()" type="button">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="network-info">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="badge bg-warning text-dark me-2">BEP20</span>
                            <small style="color: var(--text-secondary);">Binance Smart Chain</small>
                        </div>
                        <p class="small text-center mb-0" style="color: var(--text-secondary);">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Send <strong>USDT BEP20</strong> only to this address
                        </p>
                    </div>
                </div>
            </div>

            <!-- Support Card -->
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Need Help?</h6>
                </div>
                <div class="card-body text-center">
                    <i class="fab fa-bootstrap fa-2x text-warning mb-2"></i>
                    <i class="fas fa-headset fa-2x text-primary mb-3"></i>
                    <p class="mb-3" style="color: var(--text-primary);">If you face any issues with BEP20 deposit, contact our support team.</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-envelope me-2"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.step {
    display: flex;
    align-items: flex-start;
}
.step-number {
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 15px;
    flex-shrink: 0;
}
.step-content {
    flex: 1;
}
.network-details {
    font-size: 0.9rem;
}
.detail-item {
    padding: 5px 0;
    border-bottom: 1px solid var(--border-color);
}
.progress {
    background-color: var(--bg-secondary);
}

/* Card specific styling */
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

/* Ensure form elements are properly styled */
.form-control {
    background: var(--bg-primary) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
}

.form-control:focus {
    background: var(--bg-primary) !important;
    border-color: #667eea !important;
    color: var(--text-primary) !important;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Alert styling for dark mode */
.alert {
    background: rgba(var(--bs-primary-rgb), 0.1);
    border-color: rgba(var(--bs-primary-rgb), 0.2);
}

.alert-success {
    background: rgba(var(--bs-success-rgb), 0.1);
    border-color: rgba(var(--bs-success-rgb), 0.2);
}

.alert-danger {
    background: rgba(var(--bs-danger-rgb), 0.1);
    border-color: rgba(var(--bs-danger-rgb), 0.2);
}

.alert-warning {
    background: rgba(var(--bs-warning-rgb), 0.1);
    border-color: rgba(var(--bs-warning-rgb), 0.2);
}

.alert-info {
    background: rgba(var(--bs-info-rgb), 0.1);
    border-color: rgba(var(--bs-info-rgb), 0.2);
}

/* Loading state for submit button */
.btn-loading {
    position: relative;
    color: transparent !important;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-right-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script>
function copyWalletAddress() {
    const walletAddress = document.getElementById('walletAddress').textContent;
    navigator.clipboard.writeText(walletAddress).then(function() {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.remove('btn-outline-warning');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-warning');
        }, 2000);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const txnHashInput = document.getElementById('txn_hash');
    const depositForm = document.getElementById('depositForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Form submission loading state
    depositForm.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.classList.add('btn-loading');
        submitBtn.innerHTML = 'Processing...';
    });
});
</script>
@endpush