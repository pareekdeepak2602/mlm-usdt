@extends('layouts.app_new')

@section('page-title', 'Investment Levels')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Investment Levels</h1>
        <a href="{{ route('wallet.deposit') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Deposit Funds
        </a>
    </div>

    <!-- User Level Status -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Your Current Level: 
                        <span class="badge bg-primary">Level {{ $userStats['current_level'] }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                <h5 class="text-primary">{{ $userStats['direct_referrals'] }}</h5>
                                <small style="color: var(--text-secondary);">Direct Referrals (A)</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                <h5 class="text-success">{{ $userStats['indirect_referrals'] }}</h5>
                                <small style="color: var(--text-secondary);">Indirect Referrals (B+C)</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                <h5 class="text-info">${{ number_format($userStats['total_asset_hold'], 2) }}</h5>
                                <small style="color: var(--text-secondary);">Total Asset Hold</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="border-color: var(--border-color) !important;">
                                <h5 class="text-warning">
                                    @if($userStats['next_level'])
                                        Level {{ $userStats['next_level']->level }}
                                    @else
                                        Max Level
                                    @endif
                                </h5>
                                <small style="color: var(--text-secondary);">Next Level</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Level Benefits -->
                    <div class="mt-3 p-3 rounded" style="background: var(--bg-secondary); color: var(--text-primary);">
                        <h6 class="font-weight-bold">Your Current Level {{ $userStats['current_level'] }} Benefits:</h6>
                        @php
                            $currentPlan = $plans->where('level', $userStats['current_level'])->first();
                        @endphp
                        @if($currentPlan)
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <strong>Daily Income Rate:</strong> {{ $currentPlan->daily_percentage }}%
                                </div>
                                <div class="col-md-6">
                                    <strong>Daily Earnings:</strong> ${{ number_format(($user->wallet->deposit_balance ?? 0) * ($currentPlan->daily_percentage / 100), 2) }}
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    @if($userStats['next_level'])
                    <div class="mt-3 p-3 rounded" style="background: rgba(255,193,7,0.1); border-left: 4px solid #ffc107;">
                        <h6 class="font-weight-bold text-warning">Requirements for Level {{ $userStats['next_level']->level }}:</h6>
                        <ul class="mb-0">
                            @if($userStats['next_level']->direct_referrals_required)
                                <li>Direct Referrals (A): {{ $userStats['next_level']->direct_referrals_required }} 
                                    (you have {{ $userStats['direct_referrals'] }})</li>
                            @endif
                            @if($userStats['next_level']->indirect_referrals_required)
                                <li>Indirect Referrals (B+C): {{ $userStats['next_level']->indirect_referrals_required }} 
                                    (you have {{ $userStats['indirect_referrals'] }})</li>
                            @endif
                            <li>Asset Hold: ${{ number_format($userStats['next_level']->asset_hold, 2) }} 
                                (you have ${{ number_format($userStats['total_asset_hold'], 2) }})</li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Investment Levels Overview -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Investment Levels & Daily Returns</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($plans as $plan)
                            @php
                                $isCurrentLevel = $userStats['current_level'] == $plan->level;
                                $isUnlocked = $userStats['current_level'] >= $plan->level;
                                $isLocked = $userStats['current_level'] < $plan->level;
                            @endphp
                            
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 position-relative 
                                    {{ $isLocked ? 'opacity-50' : '' }}"
                                    style="background: var(--card-bg); border-color: var(--card-border); border-left: 4px solid {{ $plan->level === 0 ? '#4e73df' : ($plan->level === 1 ? '#36b9cc' : ($plan->level === 2 ? '#f6c23e' : ($plan->level === 3 ? '#1cc88a' : ($plan->level === 4 ? '#858796' : ($plan->level === 5 ? '#5a5c69' : '#e74a3b'))))) }} !important;">
                                    
                                    @if($plan->is_popular)
                                        <div class="position-absolute top-0 end-0 bg-warning text-dark px-3 py-1 rounded-bl-lg" style="font-size: 0.8rem; font-weight: bold;">
                                            Popular
                                        </div>
                                    @endif
                                    
                                    @if($isCurrentLevel)
                                        <div class="position-absolute top-0 start-0 bg-primary text-white px-3 py-1 rounded-br-lg" style="font-size: 0.8rem; font-weight: bold;">
                                            Current Level
                                        </div>
                                    @endif

                                    @if($isLocked)
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="fas fa-lock fa-2x text-secondary"></i>
                                        </div>
                                    @endif

                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="card-title mb-0" style="color: var(--text-primary);">{{ $plan->name }}</h5>
                                            <span class="badge" style="background-color: {{ $plan->level === 0 ? '#4e73df' : ($plan->level === 1 ? '#36b9cc' : ($plan->level === 2 ? '#f6c23e' : ($plan->level === 3 ? '#1cc88a' : ($plan->level === 4 ? '#858796' : ($plan->level === 5 ? '#5a5c69' : '#e74a3b'))))) }};">
                                                Level {{ $plan->level }}
                                            </span>
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <h3 class="mb-1" style="color: {{ $plan->level === 0 ? '#4e73df' : ($plan->level === 1 ? '#36b9cc' : ($plan->level === 2 ? '#f6c23e' : ($plan->level === 3 ? '#1cc88a' : ($plan->level === 4 ? '#858796' : ($plan->level === 5 ? '#5a5c69' : '#e74a3b'))))) }};">
                                                {{ $plan->daily_percentage }}%
                                            </h3>
                                            <small style="color: var(--text-secondary);">Daily Return</small>
                                        </div>
                                        
                                        <div class="plan-details">
                                            <div class="d-flex justify-content-between py-1">
                                                <span style="color: var(--text-secondary);">Min. Asset Hold:</span>
                                                <strong style="color: var(--text-primary);">${{ number_format($plan->asset_hold, 0) }}</strong>
                                            </div>
                                            
                                            @if($plan->direct_referrals_required || $plan->indirect_referrals_required)
                                                <div class="referral-requirements mt-3 p-2 rounded" style="background: var(--bg-secondary); border-left: 3px solid #f6c23e;">
                                                    <small class="d-block mb-1" style="color: var(--text-secondary);">Requirements:</small>
                                                    @if($plan->direct_referrals_required)
                                                        <div class="d-flex justify-content-between">
                                                            <span style="color: var(--text-primary);">Direct (A):</span>
                                                            <strong style="color: var(--text-primary);">{{ $plan->direct_referrals_required }}</strong>
                                                        </div>
                                                    @endif
                                                    @if($plan->indirect_referrals_required)
                                                        <div class="d-flex justify-content-between">
                                                            <span style="color: var(--text-primary);">Indirect (B+C):</span>
                                                            <strong style="color: var(--text-primary);">{{ $plan->indirect_referrals_required }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between py-1">
                                                <span style="color: var(--text-secondary);">Annual Return:</span>
                                                <strong class="text-success">{{ number_format($plan->daily_percentage * 365, 1) }}%</strong>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid mt-3">
                                            @if($isCurrentLevel)
                                                <div class="text-center p-2 rounded" style="background: rgba(78, 115, 223, 0.1);">
                                                    <small class="text-primary">
                                                        <i class="fas fa-check-circle"></i> Your Current Level
                                                    </small>
                                                    <br>
                                                    <small style="color: var(--text-secondary);">
                                                        Earning {{ $plan->daily_percentage }}% daily
                                                    </small>
                                                </div>
                                            @elseif($isUnlocked)
                                                <div class="text-center p-2 rounded" style="background: rgba(40, 167, 69, 0.1);">
                                                    <small class="text-success">
                                                        <i class="fas fa-unlock"></i> Unlocked
                                                    </small>
                                                    <br>
                                                    <small style="color: var(--text-secondary);">
                                                        Upgrade to activate
                                                    </small>
                                                </div>
                                            @else
                                                <div class="text-center p-2 rounded" style="background: rgba(108, 117, 125, 0.1);">
                                                    <small class="text-secondary">
                                                        <i class="fas fa-lock"></i> Locked
                                                    </small>
                                                    <br>
                                                    <small style="color: var(--text-secondary);">
                                                        Meet requirements to unlock
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">How It Works</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-wallet text-white fa-lg"></i>
                                </div>
                                <h5 style="color: var(--text-primary);">1. Deposit Funds</h5>
                                <p style="color: var(--text-secondary);">Deposit USDT to your wallet. Your deposit balance determines your daily earnings.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-chart-line text-white fa-lg"></i>
                                </div>
                                <h5 style="color: var(--text-primary);">2. Earn Daily Income</h5>
                                <p style="color: var(--text-secondary);">Earn daily returns based on your current level percentage and deposit balance.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-warning d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-trophy text-white fa-lg"></i>
                                </div>
                                <h5 style="color: var(--text-primary);">3. Level Up</h5>
                                <p style="color: var(--text-secondary);">Meet referral and asset requirements to level up and increase your daily percentage.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Level Requirements Table -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Level Requirements & Daily Income</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="background: var(--card-bg); color: var(--text-primary);">
                            <thead style="background: var(--bg-secondary);">
                                <tr>
                                    <th class="font-weight-bold">Level</th>
                                    <th class="font-weight-bold">Daily %</th>
                                    <th class="font-weight-bold">A (Direct)</th>
                                    <th class="font-weight-bold">B + C (Indirect)</th>
                                    <th class="font-weight-bold">Asset Hold ($)</th>
                                    <th class="font-weight-bold">Your Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plans as $plan)
                                    @php
                                        $meetsRequirements = App\Services\LevelService::meetsPlanRequirements(Auth::user(), $plan);
                                        $isCurrentLevel = $userStats['current_level'] == $plan->level;
                                    @endphp
                                    <tr style="{{ $isCurrentLevel ? 'background: rgba(78, 115, 223, 0.1) !important;' : '' }}">
                                        <td class="font-weight-bold">
                                            Level {{ $plan->level }}
                                            @if($isCurrentLevel)
                                                <span class="badge bg-primary ml-1">Current</span>
                                            @endif
                                        </td>
                                        <td class="text-success font-weight-bold">{{ $plan->daily_percentage }}%</td>
                                        <td>{{ $plan->direct_referrals_required ?: '-' }}</td>
                                        <td>{{ $plan->indirect_referrals_required ?: '-' }}</td>
                                        <td>${{ number_format($plan->asset_hold, 0) }}</td>
                                        <td>
                                            @if($isCurrentLevel)
                                                <span class="badge bg-primary">Active</span>
                                            @elseif($meetsRequirements)
                                                <span class="badge bg-success">Eligible</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Requirements Needed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add this section to your investments.index blade file -->

<!-- Level Referral Commissions -->
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
            <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                <h6 class="m-0 font-weight-bold text-primary">Level Referral Commissions</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" style="background: var(--card-bg); color: var(--text-primary);">
                        <thead style="background: var(--bg-secondary);">
                            <tr>
                                <th class="font-weight-bold">Level</th>
                                <th class="font-weight-bold text-success">A (Direct)</th>
                                <th class="font-weight-bold text-info">B</th>
                                <th class="font-weight-bold text-warning">C</th>
                                <th class="font-weight-bold">Your Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                                @php
                                    $commissionRates = App\Services\LevelReferralService::getCommissionRates($plan->level);
                                    $isCurrentLevel = $userStats['current_level'] == $plan->level;
                                    $isEligible = $commissionRates && ($commissionRates->direct_percentage > 0 || $commissionRates->level_b_percentage > 0 || $commissionRates->level_c_percentage > 0);
                                @endphp
                                <tr style="{{ $isCurrentLevel ? 'background: rgba(78, 115, 223, 0.1) !important;' : '' }}">
                                    <td class="font-weight-bold">
                                        Level {{ $plan->level }}
                                        @if($isCurrentLevel)
                                            <span class="badge bg-primary ml-1">Current</span>
                                        @endif
                                    </td>
                                    <td class="text-success font-weight-bold">
                                        {{ $commissionRates && $commissionRates->direct_percentage > 0 ? $commissionRates->direct_percentage . '%' : '-' }}
                                    </td>
                                    <td class="text-info font-weight-bold">
                                        {{ $commissionRates && $commissionRates->level_b_percentage > 0 ? $commissionRates->level_b_percentage . '%' : '-' }}
                                    </td>
                                    <td class="text-warning font-weight-bold">
                                        {{ $commissionRates && $commissionRates->level_c_percentage > 0 ? $commissionRates->level_c_percentage . '%' : '-' }}
                                    </td>
                                    <td>
                                        @if($isCurrentLevel && $isEligible)
                                            <span class="badge bg-success">Earning Commissions</span>
                                        @elseif($isEligible)
                                            <span class="badge bg-info">Eligible</span>
                                        @else
                                            <span class="badge bg-secondary">No Commissions</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 p-3 rounded" style="background: var(--bg-secondary);">
                    <small class="text-muted">
                        <strong>How it works:</strong> When your referrals deposit funds, you earn commissions based on your current level. 
                        Level 0-1 don't earn referral commissions. From Level 2 onwards, you earn percentage-based commissions from your downline deposits.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
    background: var(--card-bg) !important;
    border-color: var(--card-border) !important;
}

.card:hover {
    transform: translateY(-5px);
}

.opacity-50 {
    opacity: 0.5;
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

.text-muted {
    color: var(--text-secondary) !important;
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

/* Lock icon positioning */
.position-absolute.top-50.start-50 {
    z-index: 1;
}
</style>
@endsection