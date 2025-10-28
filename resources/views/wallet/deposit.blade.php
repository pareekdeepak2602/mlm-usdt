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

                    <form method="POST" action="{{ route('wallet.process-deposit') }}">
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
                                               value="{{ old('amount') }}" 
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
                                   value="{{ old('txn_hash') }}" 
                                   placeholder="Enter your BSC transaction hash"
                                   required
                                   style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                            @error('txn_hash')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text" style="color: var(--text-secondary);">
                                Copy the transaction hash from your wallet after making the BEP20 transfer
                            </small>
                        </div>

                        <div class="alert alert-warning" style="background: rgba(255, 193, 7, 0.1); border-color: rgba(255, 193, 7, 0.2); color: var(--text-primary);">
                            <h6 class="alert-heading" style="color: var(--text-primary);"><i class="fas fa-exclamation-triangle me-2"></i>Important BEP20 Instructions</h6>
                            <ul class="mb-0" style="color: var(--text-primary);">
                                <li>Send <strong>USDT BEP20</strong> only to our wallet address</li>
                                <li>Ensure you are on <strong>Binance Smart Chain (BSC)</strong> network</li>
                                <li>Copy and paste the BSC transaction hash correctly</li>
                                <li>Minimum deposit amount is ${{ number_format($depositLimits['min_deposit'], 2) }} USDT</li>
                                <li>Keep sufficient BNB for transaction fees</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Submit Deposit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Level Upgrade Information -->
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-primary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-level-up-alt me-2"></i>Upgrade Your Level for Higher Deposit Limits
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $nextLevel = $user->current_level + 1;
                        $nextPlan = App\Models\InvestmentPlan::where('level', $nextLevel)
                                                            ->where('status', 'active')
                                                            ->first();
                    @endphp
                    
                    @if($nextPlan)
                        <h6 class="font-weight-bold text-primary mb-3">Requirements for Level {{ $nextLevel }}:</h6>
                        <div class="row text-center">
                            @if($nextPlan->direct_referrals_required)
                                <div class="col-md-4 mb-3">
                                    <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                        <h5 class="text-primary">{{ $user->direct_referrals_count }} / {{ $nextPlan->direct_referrals_required }}</h5>
                                        <small style="color: var(--text-secondary);">Direct Referrals (A)</small>
                                        <div class="progress mt-2" style="height: 5px; background-color: var(--bg-secondary);">
                                            <div class="progress-bar bg-primary" style="width: {{ min(100, ($user->direct_referrals_count / $nextPlan->direct_referrals_required) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($nextPlan->indirect_referrals_required)
                                <div class="col-md-4 mb-3">
                                    <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                        <h5 class="text-success">{{ $user->indirect_referrals_count }} / {{ $nextPlan->indirect_referrals_required }}</h5>
                                        <small style="color: var(--text-secondary);">Indirect Referrals (B+C)</small>
                                        <div class="progress mt-2" style="height: 5px; background-color: var(--bg-secondary);">
                                            <div class="progress-bar bg-success" style="width: {{ min(100, ($user->indirect_referrals_count / $nextPlan->indirect_referrals_required) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                    <h5 class="text-info">${{ number_format($user->total_asset_hold, 2) }} / ${{ number_format($nextPlan->asset_hold, 2) }}</h5>
                                    <small style="color: var(--text-secondary);">Asset Hold Required</small>
                                    <div class="progress mt-2" style="height: 5px; background-color: var(--bg-secondary);">
                                        <div class="progress-bar bg-info" style="width: {{ min(100, ($user->total_asset_hold / $nextPlan->asset_hold) * 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 p-3 rounded" style="background: var(--bg-secondary); color: var(--text-primary);">
                            <h6 class="font-weight-bold text-warning">Level {{ $nextLevel }} Benefits:</h6>
                            <ul class="mb-0">
                                <li>Daily Return: <strong>{{ $nextPlan->daily_percentage }}%</strong></li>
                                <li>Deposit Range: <strong>${{ number_format($nextPlan->min_investment, 2) }} 
                                    @if($nextPlan->max_investment)
                                        - ${{ number_format($nextPlan->max_investment, 2) }}
                                    @else
                                        and above
                                    @endif
                                </strong></li>
                                <li>Higher referral commissions</li>
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                            <h6 style="color: var(--text-secondary);">You've reached the maximum level!</h6>
                        </div>
                    @endif
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
                            <code class="small" id="walletAddress" style="color: var(--text-primary);">0x742E4D6c4C8B6C4D8E6F7C5A3B2C1D0E9F8A7B6C</code>
                            <button class="btn btn-sm btn-outline-warning ms-2" onclick="copyWalletAddress()">
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

            <!-- Network Information -->
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">BSC Network Details</h6>
                </div>
                <div class="card-body">
                    <div class="network-details">
                        <div class="detail-item mb-2" style="color: var(--text-primary);">
                            <strong>Network:</strong> Binance Smart Chain (BSC)
                        </div>
                        <div class="detail-item mb-2" style="color: var(--text-primary);">
                            <strong>Token Type:</strong> USDT BEP20
                        </div>
                        <div class="detail-item mb-2" style="color: var(--text-primary);">
                            <strong>Chain ID:</strong> 56 (Mainnet)
                        </div>
                        <div class="detail-item" style="color: var(--text-primary);">
                            <strong>RPC URL:</strong> https://bsc-dataseed.binance.org/
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deposit Steps -->
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">How to Deposit (BEP20)</h6>
                </div>
                <div class="card-body">
                    <div class="steps">
                        <div class="step mb-3">
                            <div class="step-number bg-warning">1</div>
                            <div class="step-content">
                                <strong style="color: var(--text-primary);">Switch to BSC Network</strong>
                                <p class="mb-0 small" style="color: var(--text-secondary);">Ensure your wallet is connected to Binance Smart Chain</p>
                            </div>
                        </div>
                        <div class="step mb-3">
                            <div class="step-number bg-warning">2</div>
                            <div class="step-content">
                                <strong style="color: var(--text-primary);">Send USDT BEP20</strong>
                                <p class="mb-0 small" style="color: var(--text-secondary);">Send USDT BEP20 to our wallet address</p>
                            </div>
                        </div>
                        <div class="step mb-3">
                            <div class="step-number bg-warning">3</div>
                            <div class="step-content">
                                <strong style="color: var(--text-primary);">Copy BSC Transaction Hash</strong>
                                <p class="mb-0 small" style="color: var(--text-secondary);">Copy the transaction hash from BSC Scan</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number bg-warning">4</div>
                            <div class="step-content">
                                <strong style="color: var(--text-primary);">Submit Details</strong>
                                <p class="mb-0 small" style="color: var(--text-secondary);">Enter amount and transaction hash in the form</p>
                            </div>
                        </div>
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

// Update deposit limits based on level
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const userLevel = {{ Auth::user()->current_level }};
    
    // You can add dynamic validation here if needed
    amountInput.addEventListener('input', function() {
        const amount = parseFloat(this.value);
        const minDeposit = parseFloat(this.min);
        const maxDeposit = this.max ? parseFloat(this.max) : null;
        
        if (amount < minDeposit) {
            this.setCustomValidity(`Minimum deposit for Level ${userLevel} is $${minDeposit}`);
        } else if (maxDeposit && amount > maxDeposit) {
            this.setCustomValidity(`Maximum deposit for Level ${userLevel} is $${maxDeposit}`);
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endpush