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

                    <!-- Transaction Status Monitor -->
                    <div id="transactionMonitor" class="alert alert-info d-none">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm me-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-1" id="monitorTitle">Verifying Transaction</h6>
                                <p class="mb-0" id="monitorMessage">Checking blockchain for transaction confirmation...</p>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div id="verificationProgress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                </div>
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
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control @error('txn_hash') is-invalid @enderror" 
                                       id="txn_hash" 
                                       name="txn_hash" 
                                       value="{{ old('txn_hash', '') }}" 
                                       placeholder="Enter your BSC transaction hash (0x...)"
                                       required
                                       style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                                <button type="button" class="btn btn-outline-secondary" id="verifyTxnBtn" onclick="verifyTransactionHash()">
                                    <i class="fas fa-search me-1"></i> Verify
                                </button>
                            </div>
                            @error('txn_hash')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text" style="color: var(--text-secondary);">
                                Copy the transaction hash from your wallet after making the BEP20 transfer. Format: 0x followed by 64 hexadecimal characters.
                            </small>
                            
                            <!-- Verification Result -->
                            <div id="verificationResult" class="mt-2 d-none">
                                <div class="alert d-flex align-items-center" id="verificationAlert">
                                    <i class="fas me-2" id="verificationIcon"></i>
                                    <span id="verificationText"></span>
                                </div>
                            </div>
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

            <!-- Transaction Steps -->
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">How to Deposit</h6>
                </div>
                <div class="card-body">
                    <div class="step mb-4">
                        <div class="step-number" style="background: var(--bs-primary);">1</div>
                        <div class="step-content">
                            <h6 style="color: var(--text-primary);">Send USDT to Our Wallet</h6>
                            <p class="mb-2" style="color: var(--text-secondary);">Transfer USDT (BEP20) to the wallet address shown on the right.</p>
                            <div class="network-details">
                                <div class="detail-item">
                                    <small><strong>Network:</strong> Binance Smart Chain (BEP20)</small>
                                </div>
                                <div class="detail-item">
                                    <small><strong>Token:</strong> USDT (BEP20)</small>
                                </div>
                                <div class="detail-item">
                                    <small><strong>Minimum:</strong> ${{ number_format($depositLimits['min_deposit'], 2) }} USDT</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step mb-4">
                        <div class="step-number" style="background: var(--bs-warning);">2</div>
                        <div class="step-content">
                            <h6 style="color: var(--text-primary);">Wait for Confirmation</h6>
                            <p class="mb-2" style="color: var(--text-secondary);">Wait for the transaction to be confirmed on the blockchain (usually 2-3 minutes).</p>
                        </div>
                    </div>
                    
                    <div class="step mb-4">
                        <div class="step-number" style="background: var(--bs-info);">3</div>
                        <div class="step-content">
                            <h6 style="color: var(--text-primary);">Copy Transaction Hash</h6>
                            <p class="mb-2" style="color: var(--text-secondary);">Copy the Transaction Hash (TXID) from your wallet after the transfer is completed.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number" style="background: var(--bs-success);">4</div>
                        <div class="step-content">
                            <h6 style="color: var(--text-primary);">Submit for Verification</h6>
                            <p class="mb-0" style="color: var(--text-secondary);">Paste the transaction hash in the form above and click "Submit Deposit Request".</p>
                        </div>
                    </div>
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
                            <code class="small" id="walletAddress" style="color: var(--text-primary); word-break: break-all;">{{ config('services.transaction_verifier.company_wallet', '0x5CE2C945eeD9FBA974363fF028D86ed641b7b185') }}</code>
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

            <!-- Recent Deposits -->
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock me-2"></i>Recent Deposits
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $recentDeposits = Auth::user()->transactions()
                            ->where('txn_type', 'deposit')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentDeposits->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentDeposits as $deposit)
                                <div class="list-group-item px-0" style="background: transparent; border-color: var(--border-color);">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1" style="color: var(--text-primary);">${{ number_format($deposit->amount, 2) }}</h6>
                                            <small style="color: var(--text-secondary);">{{ $deposit->created_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="badge 
                                            @if($deposit->status == 'completed') bg-success
                                            @elseif($deposit->status == 'pending') bg-warning
                                            @elseif($deposit->status == 'failed') bg-danger
                                            @else bg-secondary @endif">
                                            {{ ucfirst($deposit->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center mb-0" style="color: var(--text-secondary);">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            No recent deposits
                        </p>
                    @endif
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

<!-- Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--card-bg); border-color: var(--card-border);">
            <div class="modal-header" style="background: var(--bg-secondary); border-color: var(--border-color);">
                <h5 class="modal-title" id="verificationModalLabel" style="color: var(--text-primary);">
                    <i class="fas fa-search me-2"></i>Verifying Transaction
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 style="color: var(--text-primary);" id="modalTitle">Checking Blockchain</h6>
                <p class="mb-3" style="color: var(--text-secondary);" id="modalMessage">Searching for transaction on Binance Smart Chain...</p>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                </div>
                <small class="text-muted" id="modalDetails">This may take a few seconds</small>
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

/* Verification result styles */
.verification-success {
    background: rgba(var(--bs-success-rgb), 0.1) !important;
    border-color: rgba(var(--bs-success-rgb), 0.2) !important;
    color: var(--bs-success) !important;
}

.verification-error {
    background: rgba(var(--bs-danger-rgb), 0.1) !important;
    border-color: rgba(var(--bs-danger-rgb), 0.2) !important;
    color: var(--bs-danger) !important;
}

.verification-warning {
    background: rgba(var(--bs-warning-rgb), 0.1) !important;
    border-color: rgba(var(--bs-warning-rgb), 0.2) !important;
    color: var(--bs-warning) !important;
}

.verification-info {
    background: rgba(var(--bs-info-rgb), 0.1) !important;
    border-color: rgba(var(--bs-info-rgb), 0.2) !important;
    color: var(--bs-info) !important;
}

/* List group customization */
.list-group-item {
    background: var(--bg-primary) !important;
    border-color: var(--border-color) !important;
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

function verifyTransactionHash() {
    const txnHash = document.getElementById('txn_hash').value.trim();
    const amount = document.getElementById('amount').value;
    const verifyBtn = document.getElementById('verifyTxnBtn');
    
    if (!txnHash) {
        showVerificationResult('error', 'Please enter a transaction hash first');
        return;
    }

    // Validate transaction hash format
    // if (!/^0x[a-fA-F0-9]{64}$/.test(txnHash)) {
    //     showVerificationResult('error', 'Invalid transaction hash format. Must be 0x followed by 64 hexadecimal characters.');
    //     return;
    // }

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('verificationModal'));
    modal.show();

    // Update modal content
    document.getElementById('modalTitle').textContent = 'Checking Blockchain';
    document.getElementById('modalMessage').textContent = 'Searching for transaction on Binance Smart Chain...';
    document.getElementById('modalDetails').textContent = 'This may take a few seconds';

    // Disable verify button
    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Verifying...';

    // Make API call to verify transaction
    fetch('{{ route("wallet.realtime-status") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            txn_hash: txnHash,
            amount: amount
        })
    })
    .then(response => response.json())
    .then(data => {
        modal.hide();
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = '<i class="fas fa-search me-1"></i> Verify';

        if (data.success) {
            if (data.status === 'success') {
                showVerificationResult('success', 
                    `Transaction verified successfully! Block: ${data.data?.blockNumber || 'N/A'}`);
            } else if (data.status === 'not_found') {
                showVerificationResult('warning', 
                    'Transaction not found on blockchain. It may still be pending.');
            } else if (data.status === 'failed') {
                showVerificationResult('error', 
                    'Transaction failed on blockchain. Please check your wallet.');
            } else if (data.status === 'mismatch') {
                showVerificationResult('warning', 
                    'Transaction details do not match. Please verify amount and recipient.');
            } else {
                showVerificationResult('error', 
                    data.message || 'Transaction verification failed');
            }
        } else {
            showVerificationResult('error', 
                data.message || 'Failed to verify transaction');
        }
    })
    .catch(error => {
        modal.hide();
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = '<i class="fas fa-search me-1"></i> Verify';
        
        console.error('Error:', error);
        showVerificationResult('error', 
            'Network error. Please try again later.');
    });
}

function showVerificationResult(type, message) {
    const resultDiv = document.getElementById('verificationResult');
    const alertDiv = document.getElementById('verificationAlert');
    const icon = document.getElementById('verificationIcon');
    const text = document.getElementById('verificationText');

    // Reset classes
    alertDiv.className = 'alert d-flex align-items-center';
    resultDiv.classList.remove('d-none');

    switch (type) {
        case 'success':
            alertDiv.classList.add('verification-success');
            icon.className = 'fas fa-check-circle me-2';
            break;
        case 'error':
            alertDiv.classList.add('verification-error');
            icon.className = 'fas fa-times-circle me-2';
            break;
        case 'warning':
            alertDiv.classList.add('verification-warning');
            icon.className = 'fas fa-exclamation-triangle me-2';
            break;
        case 'info':
            alertDiv.classList.add('verification-info');
            icon.className = 'fas fa-info-circle me-2';
            break;
    }

    text.textContent = message;

    // Auto-hide success messages after 10 seconds
    if (type === 'success') {
        setTimeout(() => {
            resultDiv.classList.add('d-none');
        }, 10000);
    }
}

function startTransactionMonitoring(txnHash) {
    const monitor = document.getElementById('transactionMonitor');
    const title = document.getElementById('monitorTitle');
    const message = document.getElementById('monitorMessage');
    const progress = document.getElementById('verificationProgress');

    monitor.classList.remove('d-none');
    let progressValue = 0;

    const interval = setInterval(() => {
        progressValue += 10;
        if (progressValue > 90) progressValue = 90;
        progress.style.width = progressValue + '%';

        fetch('{{ route("wallet.realtime-status") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ txn_hash: txnHash })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.status === 'success') {
                clearInterval(interval);
                progress.style.width = '100%';
                title.textContent = 'Transaction Verified!';
                message.textContent = 'Your deposit has been confirmed on the blockchain.';
                monitor.classList.remove('alert-info');
                monitor.classList.add('alert-success');
                
                // Refresh page after 3 seconds to show updated balance
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            } else if (data.success && (data.status === 'failed' || data.status === 'mismatch')) {
                clearInterval(interval);
                title.textContent = 'Verification Failed';
                message.textContent = data.message || 'Transaction verification failed.';
                monitor.classList.remove('alert-info');
                monitor.classList.add('alert-danger');
            }
        })
        .catch(error => {
            console.error('Monitoring error:', error);
        });
    }, 3000);

    // Stop monitoring after 5 minutes
    setTimeout(() => {
        clearInterval(interval);
        if (monitor.classList.contains('alert-info')) {
            title.textContent = 'Monitoring Timeout';
            message.textContent = 'Transaction verification is taking longer than expected. Please check back later.';
            monitor.classList.remove('alert-info');
            monitor.classList.add('alert-warning');
        }
    }, 300000);
}

document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const txnHashInput = document.getElementById('txn_hash');
    const depositForm = document.getElementById('depositForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Form submission loading state
    depositForm.addEventListener('submit', function(e) {
        const txnHash = txnHashInput.value.trim();
        if (!txnHash) {
            e.preventDefault();
            showVerificationResult('error', 'Please enter a transaction hash');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.classList.add('btn-loading');
        submitBtn.innerHTML = 'Processing...';

        // Start monitoring if we have a transaction hash
        if (txnHash) {
            startTransactionMonitoring(txnHash);
        }
    });

    // Auto-check status when page loads if there's a transaction hash in URL
    const urlParams = new URLSearchParams(window.location.search);
    const txnHash = urlParams.get('txn_hash');
    if (txnHash) {
        txnHashInput.value = txnHash;
        startTransactionMonitoring(txnHash);
    }

    // Real-time transaction hash validation
    txnHashInput.addEventListener('input', function() {
        const hash = this.value.trim();
        if (hash.length > 0) {
            // Basic format validation
            // if (!/^0x[a-fA-F0-9]{64}$/.test(hash)) {
            //     this.classList.add('is-invalid');
            // } else {
            //     this.classList.remove('is-invalid');
            // }
        }
    });

    // Amount validation
    amountInput.addEventListener('input', function() {
        const amount = parseFloat(this.value);
        const minAmount = parseFloat(this.getAttribute('min'));
        const maxAmount = this.getAttribute('max') ? parseFloat(this.getAttribute('max')) : null;

        if (amount < minAmount) {
            this.classList.add('is-invalid');
        } else if (maxAmount && amount > maxAmount) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
});
</script>
@endpush