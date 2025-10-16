@extends('layouts.app')

@section('page-title', 'Request Withdrawal')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Request Withdrawal</h1>
        <div>
            <a href="{{ route('withdrawals.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Withdrawals
            </a>
            <a href="{{ route('wallet.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-wallet fa-sm text-white-50"></i> My Wallet
            </a>
        </div>
    </div>

    <!-- Withdrawal Information -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Withdrawal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-primary">Available Balance</h5>
                                <h3>${{ number_format($balance['available_balance'] ?? 0, 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-warning">Minimum Withdrawal</h5>
                                <h3>${{ number_format($minWithdrawal, 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-danger">Withdrawal Fee</h5>
                                <h3>{{ $withdrawalFee }}%</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-success">Net Amount Example</h5>
                                <h3>${{ number_format(100 - ($withdrawalFee / 100 * 100), 2) }}</h3>
                                <small class="text-muted">For $100 withdrawal</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Withdrawal Request Form</h6>
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

                    <form method="POST" action="{{ route('withdraw.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Withdrawal Amount (USDT)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" 
                                               name="amount" 
                                               value="{{ old('amount') }}" 
                                               step="0.01" 
                                               min="{{ $minWithdrawal }}" 
                                               max="{{ $balance['available_balance'] ?? 0 }}"
                                               placeholder="Enter amount"
                                               required>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Minimum: ${{ number_format($minWithdrawal, 2) }} | 
                                        Available: ${{ number_format($balance['available_balance'] ?? 0, 2) }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fee Calculation</label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div id="feeCalculation">
                                                <small class="text-muted">Enter amount to see fee calculation</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="usdt_address" class="form-label">Your USDT BEP20 Address</label>
                            <input type="text" 
                                   class="form-control @error('usdt_address') is-invalid @enderror" 
                                   id="usdt_address" 
                                   name="usdt_address" 
                                   value="{{ old('usdt_address', Auth::user()->usdt_wallet_address) }}" 
                                   placeholder="Enter your BEP20 USDT wallet address"
                                   required>
                            @error('usdt_address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Ensure this is a valid BEP20 USDT wallet address. Withdrawals cannot be reversed.
                            </small>
                        </div>

                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Important Withdrawal Information</h6>
                            <ul class="mb-0">
                                <li>Withdrawals are processed within 24-48 hours</li>
                                <li>Withdrawal fee: {{ $withdrawalFee }}% will be deducted</li>
                                <li>Minimum withdrawal amount: ${{ number_format($minWithdrawal, 2) }}</li>
                                <li>Ensure your USDT BEP20 address is correct</li>
                                <li>Withdrawals are sent via Binance Smart Chain (BEP20)</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Submit Withdrawal Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Withdrawal Guide -->
        <div class="col-lg-4">
            <!-- Balance Breakdown -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Balance Breakdown</h6>
                </div>
                <div class="card-body">
                    <div class="balance-item mb-2">
                        <strong>Deposit Balance:</strong> 
                        <span class="float-end">${{ number_format($balance['deposit_balance'] ?? 0, 2) }}</span>
                    </div>
                    <div class="balance-item mb-2">
                        <strong>Earning Balance:</strong> 
                        <span class="float-end">${{ number_format($balance['earning_balance'] ?? 0, 2) }}</span>
                    </div>
                    <div class="balance-item mb-2">
                        <strong>Referral Balance:</strong> 
                        <span class="float-end">${{ number_format($balance['referral_balance'] ?? 0, 2) }}</span>
                    </div>
                    <hr>
                    <div class="balance-item mb-2">
                        <strong class="text-primary">Available for Withdrawal:</strong> 
                        <span class="float-end text-primary">${{ number_format($balance['available_balance'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Withdrawal Steps -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Withdrawal Process</h6>
                </div>
                <div class="card-body">
                    <div class="steps">
                        <div class="step mb-3">
                            <div class="step-number bg-warning">1</div>
                            <div class="step-content">
                                <strong>Submit Request</strong>
                                <p class="mb-0 small">Fill out the withdrawal form</p>
                            </div>
                        </div>
                        <div class="step mb-3">
                            <div class="step-number bg-warning">2</div>
                            <div class="step-content">
                                <strong>Admin Review</strong>
                                <p class="mb-0 small">We review your request (24-48 hours)</p>
                            </div>
                        </div>
                        <div class="step mb-3">
                            <div class="step-number bg-warning">3</div>
                            <div class="step-content">
                                <strong>Processing</strong>
                                <p class="mb-0 small">We process your withdrawal</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number bg-warning">4</div>
                            <div class="step-content">
                                <strong>Completed</strong>
                                <p class="mb-0 small">Funds sent to your BEP20 wallet</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Need Help?</h6>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-headset fa-2x text-primary mb-3"></i>
                    <p class="mb-3">If you have questions about withdrawals, contact our support team.</p>
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
.balance-item {
    padding: 5px 0;
    border-bottom: 1px solid #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('amount').addEventListener('input', function() {
    const amount = parseFloat(this.value) || 0;
    const feePercentage = {{ $withdrawalFee }};
    const fee = (amount * feePercentage) / 100;
    const netAmount = amount - fee;
    
    const feeCalculation = document.getElementById('feeCalculation');
    
    if (amount > 0) {
        feeCalculation.innerHTML = `
            <div class="row small">
                <div class="col-6">Amount:</div>
                <div class="col-6 text-end">$${amount.toFixed(2)}</div>
                <div class="col-6">Fee (${feePercentage}%):</div>
                <div class="col-6 text-end text-danger">-$${fee.toFixed(2)}</div>
                <div class="col-6"><strong>You Receive:</strong></div>
                <div class="col-6 text-end text-success"><strong>$${netAmount.toFixed(2)}</strong></div>
            </div>
        `;
    } else {
        feeCalculation.innerHTML = '<small class="text-muted">Enter amount to see fee calculation</small>';
    }
});

// Initialize calculation if there's a value
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    if (amountInput.value) {
        amountInput.dispatchEvent(new Event('input'));
    }
});
</script>
@endpush