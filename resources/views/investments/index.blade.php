@extends('layouts.app_new')

@section('page-title', 'Investments')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Investments</h1>
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
                    
                    @if($userStats['next_level'])
                    <div class="mt-3 p-3 rounded" style="background: var(--bg-secondary); color: var(--text-primary);">
                        <h6 class="font-weight-bold">Requirements for Level {{ $userStats['next_level']->level }}:</h6>
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

    <!-- Investment Plans -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Available Investment Plans</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($plans as $plan)
                            @php
                                $canInvest = App\Services\LevelService::canUserInvestInPlan(Auth::user(), $plan);
                                $isCurrentLevel = $userStats['current_level'] == $plan->level;
                                $isUnlocked = $userStats['current_level'] >= $plan->level;
                            @endphp
                            
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 position-relative 
                                    {{ !$isUnlocked ? 'opacity-50' : '' }}"
                                    style="background: var(--card-bg); border-color: var(--card-border); border-left: 4px solid {{ $plan->level === 0 ? '#4e73df' : ($plan->level === 1 ? '#36b9cc' : ($plan->level === 2 ? '#f6c23e' : ($plan->level === 3 ? '#1cc88a' : ($plan->level === 4 ? '#858796' : ($plan->level === 5 ? '#5a5c69' : '#e74a3b'))))) }} !important;">
                                    
                                    @if($plan->is_popular)
                                        <div class="position-absolute top-0 end-0 bg-warning text-dark px-3 py-1 rounded-bl-lg" style="font-size: 0.8rem; font-weight: bold;">
                                            Popular
                                        </div>
                                    @endif
                                    
                                    @if($isCurrentLevel)
                                        <div class="position-absolute top-0 start-0 bg-primary text-white px-3 py-1 rounded-br-lg" style="font-size: 0.8rem; font-weight: bold;">
                                            Current
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
                                                <span style="color: var(--text-secondary);">Investment Range:</span>
                                                <strong style="color: var(--text-primary);">${{ number_format($plan->min_investment, 0) }} 
                                                    @if($plan->max_investment)
                                                        - ${{ number_format($plan->max_investment, 0) }}
                                                    @else
                                                        +
                                                    @endif
                                                </strong>
                                            </div>
                                            <div class="d-flex justify-content-between py-1">
                                                <span style="color: var(--text-secondary);">Asset Hold:</span>
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
                                                <span style="color: var(--text-secondary);">Duration:</span>
                                                <strong style="color: var(--text-primary);">{{ $plan->duration_days }} days</strong>
                                            </div>
                                            <div class="d-flex justify-content-between py-1">
                                                <span style="color: var(--text-secondary);">Total Return:</span>
                                                <strong class="text-success">{{ number_format($plan->daily_percentage * $plan->duration_days, 1) }}%</strong>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid mt-3">
                                            @if($canInvest['success'])
                                                <a href="{{ route('investments.create', $plan->id) }}" 
                                                   class="btn" 
                                                   style="background-color: {{ $plan->level === 0 ? '#4e73df' : ($plan->level === 1 ? '#36b9cc' : ($plan->level === 2 ? '#f6c23e' : ($plan->level === 3 ? '#1cc88a' : ($plan->level === 4 ? '#858796' : ($plan->level === 5 ? '#5a5c69' : '#e74a3b'))))) }}; border-color: {{ $plan->level === 0 ? '#4e73df' : ($plan->level === 1 ? '#36b9cc' : ($plan->level === 2 ? '#f6c23e' : ($plan->level === 3 ? '#1cc88a' : ($plan->level === 4 ? '#858796' : ($plan->level === 5 ? '#5a5c69' : '#e74a3b'))))) }}; color: white;">
                                                    Invest Now
                                                </a>
                                            @else
                                                <button class="btn btn-outline-secondary" disabled title="Requirements not met">
                                                    Locked
                                                </button>
                                                <small class="mt-1 d-block text-center" style="color: var(--text-secondary);">
                                                    Level {{ $plan->level }} Required
                                                </small>
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
                                            @if($meetsRequirements)
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

    <!-- Your Investments -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Your Active Investments</h6>
                    <span class="badge bg-primary">{{ $investments->where('status', 'active')->count() }} Active</span>
                </div>
                <div class="card-body">
                    @if($investments->where('status', 'active')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" style="background: var(--card-bg); color: var(--text-primary);">
                                <thead style="background: var(--bg-secondary);">
                                    <tr>
                                        <th style="color: var(--text-primary);">Plan</th>
                                        <th style="color: var(--text-primary);">Level</th>
                                        <th style="color: var(--text-primary);">Amount</th>
                                        <th style="color: var(--text-primary);">Daily Income</th>
                                        <th style="color: var(--text-primary);">Total Earned</th>
                                        <th style="color: var(--text-primary);">Start Date</th>
                                        <th style="color: var(--text-primary);">End Date</th>
                                        <th style="color: var(--text-primary);">Days Left</th>
                                        <th style="color: var(--text-primary);">Status</th>
                                        <th style="color: var(--text-primary);">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($investments->where('status', 'active') as $investment)
                                        <tr>
                                            <td>
                                                <strong style="color: var(--text-primary);">{{ $investment->plan->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">Level {{ $investment->plan->level }}</span>
                                            </td>
                                            <td style="color: var(--text-primary);">${{ number_format($investment->amount, 2) }}</td>
                                            <td class="text-success">
                                                <strong>${{ number_format($investment->daily_income, 2) }}</strong>
                                                <br>
                                                <small style="color: var(--text-secondary);">{{ $investment->plan->daily_percentage }}% daily</small>
                                            </td>
                                            <td class="text-success font-weight-bold">${{ number_format($investment->total_earned, 2) }}</td>
                                            <td style="color: var(--text-primary);">{{ $investment->start_date->format('M d, Y') }}</td>
                                            <td style="color: var(--text-primary);">{{ $investment->end_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $investment->days_left <= 7 ? 'warning' : 'info' }}">
                                                    {{ $investment->days_left }} days
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Active
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('investments.show', $investment->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-chart-line"></i> Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-4x text-gray-300 mb-3"></i>
                            <h5 style="color: var(--text-secondary);">No Active Investments</h5>
                            <p style="color: var(--text-secondary);" class="mb-4">Start investing to earn daily returns</p>
                            <a href="#plans" class="btn btn-primary">View Investment Plans</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Investments -->
    @if($investments->where('status', 'completed')->count() > 0)
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Completed Investments</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="background: var(--card-bg); color: var(--text-primary);">
                            <thead style="background: var(--bg-secondary);">
                                <tr>
                                    <th style="color: var(--text-primary);">Plan</th>
                                    <th style="color: var(--text-primary);">Level</th>
                                    <th style="color: var(--text-primary);">Amount</th>
                                    <th style="color: var(--text-primary);">Total Earned</th>
                                    <th style="color: var(--text-primary);">Start Date</th>
                                    <th style="color: var(--text-primary);">End Date</th>
                                    <th style="color: var(--text-primary);">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($investments->where('status', 'completed') as $investment)
                                    <tr>
                                        <td style="color: var(--text-primary);">{{ $investment->plan->name }}</td>
                                        <td>
                                            <span class="badge bg-secondary">Level {{ $investment->plan->level }}</span>
                                        </td>
                                        <td style="color: var(--text-primary);">${{ number_format($investment->amount, 2) }}</td>
                                        <td class="text-success font-weight-bold">${{ number_format($investment->total_earned, 2) }}</td>
                                        <td style="color: var(--text-primary);">{{ $investment->start_date->format('M d, Y') }}</td>
                                        <td style="color: var(--text-primary);">{{ $investment->end_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="fas fa-flag-checkered"></i> Completed
                                            </span>
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
    @endif
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
</style>
@endsection