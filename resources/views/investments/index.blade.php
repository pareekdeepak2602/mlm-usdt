@extends('layouts.app')

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
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Current Level: 
                        <span class="badge bg-primary">Level {{ $userStats['current_level'] }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h5 class="text-primary">{{ $userStats['direct_referrals'] }}</h5>
                                <small class="text-muted">Direct Referrals (A)</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h5 class="text-success">{{ $userStats['indirect_referrals'] }}</h5>
                                <small class="text-muted">Indirect Referrals (B+C)</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h5 class="text-info">${{ number_format($userStats['total_asset_hold'], 2) }}</h5>
                                <small class="text-muted">Total Asset Hold</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h5 class="text-warning">
                                    @if($userStats['next_level'])
                                        Level {{ $userStats['next_level']->level }}
                                    @else
                                        Max Level
                                    @endif
                                </h5>
                                <small class="text-muted">Next Level</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($userStats['next_level'])
                    <div class="mt-3 p-3 bg-light rounded">
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
            <div class="card shadow">
                <div class="card-header py-3">
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
                                <div class="card h-100 border-left-{{ $plan->level === 0 ? 'primary' : ($plan->level === 1 ? 'info' : ($plan->level === 2 ? 'warning' : ($plan->level === 3 ? 'success' : ($plan->level === 4 ? 'secondary' : ($plan->level === 5 ? 'dark' : 'danger'))))) }} position-relative 
                                    {{ !$isUnlocked ? 'opacity-50' : '' }}">
                                    
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
                                            <h5 class="card-title mb-0">{{ $plan->name }}</h5>
                                            <span class="badge bg-{{ $plan->level === 0 ? 'primary' : ($plan->level === 1 ? 'info' : ($plan->level === 2 ? 'warning' : ($plan->level === 3 ? 'success' : ($plan->level === 4 ? 'secondary' : ($plan->level === 5 ? 'dark' : 'danger'))))) }}">
                                                Level {{ $plan->level }}
                                            </span>
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <h3 class="text-{{ $plan->level === 0 ? 'primary' : ($plan->level === 1 ? 'info' : ($plan->level === 2 ? 'warning' : ($plan->level === 3 ? 'success' : ($plan->level === 4 ? 'secondary' : ($plan->level === 5 ? 'dark' : 'danger'))))) }} mb-1">
                                                {{ $plan->daily_percentage }}%
                                            </h3>
                                            <small class="text-muted">Daily Return</small>
                                        </div>
                                        
                                        <div class="plan-details">
                                            <div class="d-flex justify-content-between py-1">
                                                <span class="text-muted">Investment Range:</span>
                                                <strong>${{ number_format($plan->min_investment, 0) }} 
                                                    @if($plan->max_investment)
                                                        - ${{ number_format($plan->max_investment, 0) }}
                                                    @else
                                                        +
                                                    @endif
                                                </strong>
                                            </div>
                                            <div class="d-flex justify-content-between py-1">
                                                <span class="text-muted">Asset Hold:</span>
                                                <strong>${{ number_format($plan->asset_hold, 0) }}</strong>
                                            </div>
                                            
                                            @if($plan->direct_referrals_required || $plan->indirect_referrals_required)
                                                <div class="referral-requirements mt-3 p-2 bg-light rounded">
                                                    <small class="text-muted d-block mb-1">Requirements:</small>
                                                    @if($plan->direct_referrals_required)
                                                        <div class="d-flex justify-content-between">
                                                            <span>Direct (A):</span>
                                                            <strong>{{ $plan->direct_referrals_required }}</strong>
                                                        </div>
                                                    @endif
                                                    @if($plan->indirect_referrals_required)
                                                        <div class="d-flex justify-content-between">
                                                            <span>Indirect (B+C):</span>
                                                            <strong>{{ $plan->indirect_referrals_required }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between py-1">
                                                <span class="text-muted">Duration:</span>
                                                <strong>{{ $plan->duration_days }} days</strong>
                                            </div>
                                            <div class="d-flex justify-content-between py-1">
                                                <span class="text-muted">Total Return:</span>
                                                <strong class="text-success">{{ number_format($plan->daily_percentage * $plan->duration_days, 1) }}%</strong>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid mt-3">
                                            @if($canInvest['success'])
                                                <a href="{{ route('investments.create', $plan->id) }}" class="btn btn-{{ $plan->level === 0 ? 'primary' : ($plan->level === 1 ? 'info' : ($plan->level === 2 ? 'warning' : ($plan->level === 3 ? 'success' : ($plan->level === 4 ? 'secondary' : ($plan->level === 5 ? 'dark' : 'danger'))))) }}">
                                                    Invest Now
                                                </a>
                                            @else
                                                <button class="btn btn-outline-secondary" disabled title="Requirements not met">
                                                    Locked
                                                </button>
                                                <small class="text-muted mt-1 d-block text-center">
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
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Level Requirements & Daily Income</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-bold text-dark">Level</th>
                                    <th class="font-weight-bold text-dark">Daily %</th>
                                    <th class="font-weight-bold text-dark">A (Direct)</th>
                                    <th class="font-weight-bold text-dark">B + C (Indirect)</th>
                                    <th class="font-weight-bold text-dark">Asset Hold ($)</th>
                                    <th class="font-weight-bold text-dark">Your Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plans as $plan)
                                    @php
                                        $meetsRequirements = App\Services\LevelService::meetsPlanRequirements(Auth::user(), $plan);
                                        $isCurrentLevel = $userStats['current_level'] == $plan->level;
                                    @endphp
                                    <tr class="{{ $isCurrentLevel ? 'table-primary' : '' }}">
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
                                                <span class="badge bg-warning">Requirements Needed</span>
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
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Your Active Investments</h6>
                    <span class="badge bg-primary">{{ $investments->where('status', 'active')->count() }} Active</span>
                </div>
                <div class="card-body">
                    @if($investments->where('status', 'active')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Plan</th>
                                        <th>Level</th>
                                        <th>Amount</th>
                                        <th>Daily Income</th>
                                        <th>Total Earned</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Days Left</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($investments->where('status', 'active') as $investment)
                                        <tr>
                                            <td>
                                                <strong>{{ $investment->plan->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">Level {{ $investment->plan->level }}</span>
                                            </td>
                                            <td>${{ number_format($investment->amount, 2) }}</td>
                                            <td class="text-success">
                                                <strong>${{ number_format($investment->daily_income, 2) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $investment->plan->daily_percentage }}% daily</small>
                                            </td>
                                            <td class="text-success font-weight-bold">${{ number_format($investment->total_earned, 2) }}</td>
                                            <td>{{ $investment->start_date->format('M d, Y') }}</td>
                                            <td>{{ $investment->end_date->format('M d, Y') }}</td>
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
                            <h5 class="text-muted">No Active Investments</h5>
                            <p class="text-muted mb-4">Start investing to earn daily returns</p>
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
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Completed Investments</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Plan</th>
                                    <th>Level</th>
                                    <th>Amount</th>
                                    <th>Total Earned</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($investments->where('status', 'completed') as $investment)
                                    <tr>
                                        <td>{{ $investment->plan->name }}</td>
                                        <td>
                                            <span class="badge bg-secondary">Level {{ $investment->plan->level }}</span>
                                        </td>
                                        <td>${{ number_format($investment->amount, 2) }}</td>
                                        <td class="text-success font-weight-bold">${{ number_format($investment->total_earned, 2) }}</td>
                                        <td>{{ $investment->start_date->format('M d, Y') }}</td>
                                        <td>{{ $investment->end_date->format('M d, Y') }}</td>
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
}

.card:hover {
    transform: translateY(-5px);
}

.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-info { border-left: 4px solid #36b9cc !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }
.border-left-secondary { border-left: 4px solid #858796 !important; }
.border-left-dark { border-left: 4px solid #5a5c69 !important; }
.border-left-danger { border-left: 4px solid #e74a3b !important; }

.referral-requirements {
    border-left: 3px solid #f6c23e;
}

.opacity-50 {
    opacity: 0.5;
}
</style>
@endsection