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
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 border-left-{{ $plan->name === 'Starter' ? 'primary' : ($plan->name === 'Bronze' ? 'warning' : ($plan->name === 'Silver' ? 'info' : ($plan->name === 'Gold' ? 'warning' : ($plan->name === 'Platinum' ? 'success' : 'danger')))) }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $plan->name }}</h5>
                                        <p class="card-text">
                                            <strong>Min Investment:</strong> ${{ number_format($plan->min_investment, 2) }}<br>
                                            @if($plan->max_investment)
                                                <strong>Max Investment:</strong> ${{ number_format($plan->max_investment, 2) }}<br>
                                            @endif
                                            <strong>Daily Return:</strong> {{ $plan->daily_percentage }}%<br>
                                            <strong>Duration:</strong> {{ $plan->duration_days }} days
                                        </p>
                                        <div class="d-grid">
                                            <a href="{{ route('investments.create', $plan->id) }}" class="btn btn-{{ $plan->name === 'Starter' ? 'primary' : ($plan->name === 'Bronze' ? 'warning' : ($plan->name === 'Silver' ? 'info' : ($plan->name === 'Gold' ? 'warning' : ($plan->name === 'Platinum' ? 'success' : 'danger')))) }}">
                                                Invest Now
                                            </a>
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

    <!-- Your Investments -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Investments</h6>
                </div>
                <div class="card-body">
                    @if($investments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Daily Income</th>
                                        <th>Total Earned</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($investments as $investment)
                                        <tr>
                                            <td>{{ $investment->plan->name }}</td>
                                            <td>${{ number_format($investment->amount, 2) }}</td>
                                            <td class="text-success">${{ number_format($investment->daily_income, 2) }}</td>
                                            <td>${{ number_format($investment->total_earned, 2) }}</td>
                                            <td>{{ $investment->start_date->format('M d, Y') }}</td>
                                            <td>{{ $investment->end_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $investment->status === 'active' ? 'success' : ($investment->status === 'completed' ? 'info' : 'danger') }}">
                                                    {{ ucfirst($investment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('investments.show', $investment->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">You don't have any investments yet</p>
                            <a href="{{ route('wallet.deposit') }}" class="btn btn-primary">Deposit Funds</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection