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
                        <div class="col-md-2">
                            <div class="text-center">
                                <h5 class="text-success">Total Balance</h5>
                                <h3>${{ number_format($balance['total_balance'] ?? 0, 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h5 class="text-info">Your Level</h5>
                                <h3>Level {{ Auth::user()->current_level }}</h3>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h5 class="text-warning">Asset Hold</h5>
                                <h3>${{ number_format($balance['asset_hold'] ?? 0, 2) }}</h3>
                                <small class="text-muted">Level Requirement</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h5 class="text-primary">Available for Withdrawal</h5>
                                <h3>${{ number_format($balance['withdrawable_balance'] ?? 0, 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h5 class="text-warning">Minimum Withdrawal</h5>
                                <h3>${{ number_format($minWithdrawal, 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h5 class="text-danger">Withdrawal Fee</h5>
                                <h3>{{ $withdrawalFee }}%</h3>
                                <small class="text-muted">Net: ${{ number_format(100 - ($withdrawalFee / 100 * 100), 2) }} for $100</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profit & Asset Hold Status -->
                    @if($balance['asset_hold'] > 0)
                    <div class="alert alert-warning mt-3">
                        <h6 class="alert-heading"><i class="fas fa-chart-line me-2"></i>Profit & Asset Hold Status</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Current Profit:</strong> 
                                    <span class="{{ $balance['profit_percentage'] >= 50 ? 'text-success' : 'text-info' }}">
                                        {{ number_format($balance['profit_percentage'], 2) }}%
                                    </span>
                                </p>
                                <p class="mb-2">
                                    <strong>Asset Hold Status:</strong> 
                                    @if($balance['is_asset_hold_locked'])
                                        <span class="text-danger"><i class="fas fa-lock me-1"></i>LOCKED</span>
                                    @else
                                        <span class="text-success"><i class="fas fa-lock-open me-1"></i>UNLOCKED</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    @if($balance['is_asset_hold_locked'])
                                        <i class="fas fa-info-circle me-1 text-danger"></i>
                                        <strong>Asset hold of ${{ number_format($balance['asset_hold'], 2) }} is LOCKED.</strong><br>
                                        You can only withdraw amounts above this requirement.
                                    @else
                                        <i class="fas fa-info-circle me-1 text-success"></i>
                                        <strong>Asset hold is UNLOCKED.</strong><br>
                                        You can withdraw your entire balance until you reach 50% profit.
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 20px;">
                            <div class="progress-bar 
                                @if($balance['profit_percentage'] < 25) bg-info
                                @elseif($balance['profit_percentage'] < 50) bg-warning
                                @else bg-success
                                @endif" 
                                role="progressbar" 
                                style="width: {{ min($balance['profit_percentage'], 100) }}%"
                                aria-valuenow="{{ $balance['profit_percentage'] }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ number_format($balance['profit_percentage'], 1) }}% Profit
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            Asset hold locks at 50% profit. Current progress: {{ number_format($balance['profit_percentage'], 2) }}% / 50%
                        </small>
                    </div>
                    @endif

                    <!-- Asset Hold Explanation -->
                    @if($balance['asset_hold'] > 0)
                    <div class="alert alert-info mt-3">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Asset Hold Information</h6>
                        <p class="mb-2">As a <strong>Level {{ Auth::user()->current_level }}</strong> member, you need to maintain <strong>${{ number_format($balance['asset_hold'], 2) }}</strong> in your account to preserve your current level benefits.</p>
                        <p class="mb-0">
                            <strong>New Rule:</strong> Asset hold only locks when you reach <strong>50% profit</strong>. 
                            Until then, you can withdraw your entire balance.
                        </p>
                    </div>
                    @endif

                    <!-- Level Benefits Information -->
                    <div class="alert alert-success mt-3">
                        <h6 class="alert-heading"><i class="fas fa-star me-2"></i>Level {{ Auth::user()->current_level }} Benefits</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Daily Earnings:</strong> 
                                @php
                                    $investmentPlan = \App\Models\InvestmentPlan::where('level', Auth::user()->current_level)->first();
                                    $dailyPercentage = $investmentPlan ? $investmentPlan->daily_percentage : 0;
                                @endphp
                                {{ $dailyPercentage }}%
                            </div>
                            <div class="col-md-6">
                                <strong>Referral Commissions:</strong> Up to 3 levels
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
                                               max="{{ $balance['withdrawable_balance'] ?? 0 }}"
                                               placeholder="Enter amount"
                                               required>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Minimum: ${{ number_format($minWithdrawal, 2) }} | 
                                        Maximum: ${{ number_format($balance['withdrawable_balance'] ?? 0, 2) }}
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

                        <!-- Quick Amount Buttons -->
                        @if($balance['withdrawable_balance'] > $minWithdrawal)
                        <div class="mb-3">
                            <label class="form-label">Quick Amount Selection</label>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                    $quickAmounts = [
                                        $minWithdrawal,
                                        min(100, $balance['withdrawable_balance']),
                                        min(200, $balance['withdrawable_balance']),
                                        min(500, $balance['withdrawable_balance']),
                                        $balance['withdrawable_balance']
                                    ];
                                    $quickAmounts = array_unique($quickAmounts);
                                    sort($quickAmounts);
                                @endphp
                                
                                @foreach($quickAmounts as $quickAmount)
                                    @if($quickAmount >= $minWithdrawal && $quickAmount <= $balance['withdrawable_balance'])
                                        <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="{{ $quickAmount }}">
                                            ${{ number_format($quickAmount, 0) }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Important Withdrawal Information</h6>
                            <ul class="mb-0">
                                <li>Withdrawals are processed within 24-48 hours</li>
                                <li>Withdrawal fee: {{ $withdrawalFee }}% will be deducted</li>
                                <li>Minimum withdrawal amount: ${{ number_format($minWithdrawal, 2) }}</li>
                                <li>
                                    Asset hold: ${{ number_format($balance['asset_hold'] ?? 0, 2) }} for Level {{ Auth::user()->current_level }}
                                    @if($balance['is_asset_hold_locked'])
                                        <span class="badge bg-danger">LOCKED</span>
                                    @else
                                        <span class="badge bg-success">UNLOCKED</span>
                                    @endif
                                </li>
                                <li>Maximum withdrawable amount: ${{ number_format($balance['withdrawable_balance'] ?? 0, 2) }}</li>
                                <li>Current profit: {{ number_format($balance['profit_percentage'] ?? 0, 2) }}% (Locks at 50%)</li>
                                <li>Ensure your USDT BEP20 address is correct</li>
                                <li>Withdrawals are sent via Binance Smart Chain (BEP20)</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i> Submit Withdrawal Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Withdrawal Guide & Information -->
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
                        <strong class="text-success">Total Balance:</strong> 
                        <span class="float-end text-success">${{ number_format($balance['total_balance'] ?? 0, 2) }}</span>
                    </div>
                    
                    @if($balance['asset_hold'] > 0)
                        <div class="balance-item mb-2">
                            <strong class="{{ $balance['is_asset_hold_locked'] ? 'text-danger' : 'text-warning' }}">
                                Asset Hold (Level {{ Auth::user()->current_level }}):
                            </strong> 
                            <span class="float-end {{ $balance['is_asset_hold_locked'] ? 'text-danger' : 'text-warning' }}">
                                ${{ number_format($balance['asset_hold'], 2) }}
                                @if($balance['is_asset_hold_locked'])
                                    <i class="fas fa-lock ms-1"></i>
                                @else
                                    <i class="fas fa-lock-open ms-1"></i>
                                @endif
                            </span>
                        </div>
                    @endif
                    
                    <div class="balance-item mb-2">
                        <strong class="text-primary">Available for Withdrawal:</strong> 
                        <span class="float-end text-primary">${{ number_format($balance['withdrawable_balance'] ?? 0, 2) }}</span>
                    </div>
                    
                    <!-- Profit Information -->
                    <div class="balance-item mb-2">
                        <strong class="text-info">Current Profit:</strong> 
                        <span class="float-end text-info">{{ number_format($balance['profit_percentage'] ?? 0, 2) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Level Requirements -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Level {{ Auth::user()->current_level }} Requirements</h6>
                </div>
                <div class="card-body">
                    @php
                        $currentPlan = \App\Models\InvestmentPlan::where('level', Auth::user()->current_level)->first();
                    @endphp
                    @if($currentPlan)
                    <div class="level-info">
                        <div class="info-item mb-2">
                            <strong>Level Name:</strong> 
                            <span class="float-end">{{ $currentPlan->name }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <strong>Asset Hold:</strong> 
                            <span class="float-end">${{ number_format($currentPlan->asset_hold, 2) }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <strong>Daily Earnings:</strong> 
                            <span class="float-end">{{ $currentPlan->daily_percentage }}%</span>
                        </div>
                        @if($currentPlan->direct_referrals_required)
                        <div class="info-item mb-2">
                            <strong>Direct Referrals:</strong> 
                            <span class="float-end">{{ $currentPlan->direct_referrals_required }}</span>
                        </div>
                        @endif
                        @if($currentPlan->indirect_referrals_required)
                        <div class="info-item mb-2">
                            <strong>Indirect Referrals:</strong> 
                            <span class="float-end">{{ $currentPlan->indirect_referrals_required }}</span>
                        </div>
                        @endif
                        <div class="info-item mb-2">
                            <strong>Asset Hold Rule:</strong> 
                            <span class="float-end text-success">Locks at 50% Profit</span>
                        </div>
                    </div>
                    @else
                    <p class="text-muted mb-0">Level information not available.</p>
                    @endif
                </div>
            </div>

            <!-- Profit Rules Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">New Profit Rules</h6>
                </div>
                <div class="card-body">
                    <div class="rules">
                        <div class="rule-item mb-3">
                            <div class="rule-icon bg-success text-white">
                                <i class="fas fa-lock-open"></i>
                            </div>
                            <div class="rule-content">
                                <strong>Before 50% Profit</strong>
                                <p class="mb-0 small">Asset hold is UNLOCKED. You can withdraw your entire balance.</p>
                            </div>
                        </div>
                        <div class="rule-item mb-3">
                            <div class="rule-icon bg-danger text-white">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="rule-content">
                                <strong>After 50% Profit</strong>
                                <p class="mb-0 small">Asset hold LOCKS. You can only withdraw amounts above asset hold.</p>
                            </div>
                        </div>
                        <div class="rule-item">
                            <div class="rule-icon bg-info text-white">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="rule-content">
                                <strong>Current Status</strong>
                                <p class="mb-0 small">
                                    Profit: {{ number_format($balance['profit_percentage'] ?? 0, 2) }}%<br>
                                    Status: 
                                    @if($balance['is_asset_hold_locked'])
                                        <span class="text-danger">LOCKED</span>
                                    @else
                                        <span class="text-success">UNLOCKED</span>
                                    @endif
                                </p>
                            </div>
                        </div>
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
                            <div class="step-number bg-primary">1</div>
                            <div class="step-content">
                                <strong>Submit Request</strong>
                                <p class="mb-0 small">Fill and submit withdrawal form</p>
                            </div>
                        </div>
                        <div class="step mb-3">
                            <div class="step-number bg-info">2</div>
                            <div class="step-content">
                                <strong>Admin Review</strong>
                                <p class="mb-0 small">Request reviewed within 24-48 hours</p>
                            </div>
                        </div>
                        <div class="step mb-3">
                            <div class="step-number bg-warning">3</div>
                            <div class="step-content">
                                <strong>Processing</strong>
                                <p class="mb-0 small">Funds prepared for transfer</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number bg-success">4</div>
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
                    <a href="{{ route('support.index') }}" class="btn btn-outline-primary btn-sm">
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
    font-size: 14px;
}
.step-content {
    flex: 1;
}
.balance-item, .info-item {
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}
.balance-item:last-child, .info-item:last-child {
    border-bottom: none;
}
.quick-amount {
    transition: all 0.3s ease;
}
.quick-amount:hover {
    transform: translateY(-2px);
}
.rule-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px;
}
.rule-icon {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
    font-size: 16px;
}
.rule-content {
    flex: 1;
}
.progress {
    border-radius: 10px;
}
.progress-bar {
    border-radius: 10px;
    font-weight: bold;
    font-size: 12px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const feeCalculation = document.getElementById('feeCalculation');
    const submitBtn = document.getElementById('submitBtn');
    const feePercentage = {{ $withdrawalFee }};
    const minWithdrawal = {{ $minWithdrawal }};
    const maxWithdrawal = {{ $balance['withdrawable_balance'] ?? 0 }};
    const isAssetHoldLocked = {{ $balance['is_asset_hold_locked'] ? 'true' : 'false' }};
    const assetHold = {{ $balance['asset_hold'] ?? 0 }};
    const profitPercentage = {{ $balance['profit_percentage'] ?? 0 }};

    // Fee calculation function
    function calculateFee(amount) {
        const fee = (amount * feePercentage) / 100;
        const netAmount = amount - fee;
        
        if (amount > 0) {
            let statusInfo = '';
            if (isAssetHoldLocked) {
                statusInfo = `<small class="text-danger d-block mt-1"><i class="fas fa-lock me-1"></i>Asset Hold: $${assetHold.toFixed(2)} LOCKED</small>`;
            } else {
                statusInfo = `<small class="text-success d-block mt-1"><i class="fas fa-lock-open me-1"></i>Asset Hold UNLOCKED (${profitPercentage.toFixed(2)}% profit)</small>`;
            }
            
            feeCalculation.innerHTML = `
                <div class="row small">
                    <div class="col-6">Withdrawal Amount:</div>
                    <div class="col-6 text-end">$${amount.toFixed(2)}</div>
                    <div class="col-6">Fee (${feePercentage}%):</div>
                    <div class="col-6 text-end text-danger">-$${fee.toFixed(2)}</div>
                    <div class="col-12"><hr class="my-1"></div>
                    <div class="col-6"><strong>You Will Receive:</strong></div>
                    <div class="col-6 text-end text-success"><strong>$${netAmount.toFixed(2)}</strong></div>
                    <div class="col-12">${statusInfo}</div>
                </div>
            `;
        } else {
            feeCalculation.innerHTML = '<small class="text-muted">Enter amount to see fee calculation</small>';
        }
    }

    // Input event listener
    amountInput.addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        calculateFee(amount);
        
        // Validate amount range
        if (amount > 0) {
            if (amount < minWithdrawal) {
                this.setCustomValidity(`Minimum withdrawal amount is $${minWithdrawal}`);
            } else if (amount > maxWithdrawal) {
                this.setCustomValidity(`Maximum withdrawal amount is $${maxWithdrawal}`);
            } else {
                this.setCustomValidity('');
            }
        } else {
            this.setCustomValidity('');
        }
    });

    // Quick amount buttons
    document.querySelectorAll('.quick-amount').forEach(button => {
        button.addEventListener('click', function() {
            const amount = parseFloat(this.getAttribute('data-amount'));
            amountInput.value = amount;
            calculateFee(amount);
            amountInput.focus();
        });
    });

    // Form submission validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const amount = parseFloat(amountInput.value);
        
        if (amount < minWithdrawal) {
            e.preventDefault();
            alert(`Minimum withdrawal amount is $${minWithdrawal}`);
            return;
        }
        
        if (amount > maxWithdrawal) {
            e.preventDefault();
            alert(`Maximum withdrawal amount is $${maxWithdrawal}`);
            return;
        }

        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
        submitBtn.disabled = true;
    });

    // Initialize calculation if there's a value
    if (amountInput.value) {
        calculateFee(parseFloat(amountInput.value));
    }
});
</script>
@endpush