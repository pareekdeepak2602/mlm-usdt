@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Available Balance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($wallet->available_balance ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Income
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($wallet->total_income ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Active Investments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $investments->where('status', 'active')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Referrals
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $referrals->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Referral Link -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Your Referral Link</h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">Share this link with your friends and earn referral bonuses:</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="{{ Auth::user()->referral_link }}" readonly id="referralLink">
                        <button class="btn btn-primary" type="button" id="copyBtn">Copy</button>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        You will earn <strong>10%</strong> from direct referrals (Level A), <strong>5%</strong> from second level (Level B), and <strong>3%</strong> from third level (Level C).
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
                    <a href="{{ route('wallet.transactions') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $transaction->txn_type === 'deposit' || $transaction->txn_type === 'income' || $transaction->txn_type === 'referral' ? 'success' : 'danger' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $transaction->txn_type)) }}
                                                </span>
                                            </td>
                                            <td class="{{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                                                ${{ number_format(abs($transaction->amount), 2) }}
                                            </td>
                                            <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">No transactions yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Active Investments -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Active Investments</h6>
                    <a href="{{ route('investments.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($investments->where('status', 'active')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Daily Income</th>
                                        <th>Total Earned</th>
                                        <th>End Date</th>
                                        <th>Remaining Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($investments->where('status', 'active') as $investment)
                                        <tr>
                                            <td>{{ $investment->plan->name }}</td>
                                            <td>${{ number_format($investment->amount, 2) }}</td>
                                            <td class="text-success">${{ number_format($investment->daily_income, 2) }}</td>
                                            <td>${{ number_format($investment->total_earned, 2) }}</td>
                                            <td>{{ $investment->end_date->format('M d, Y') }}</td>
                                            <td>{{ $investment->remaining_days }} days</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">You don't have any active investments yet</p>
                            <a href="{{ route('investments.index') }}" class="btn btn-primary">Invest Now</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    @if($notifications->count() > 0)
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Notifications</h6>
                        <a href="{{ route('notifications') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        @foreach($notifications->take(3) as $notification)
                            <div class="alert alert-{{ $notification->type }} alert-dismissible fade show" role="alert">
                                <strong>{{ $notification->title }}</strong> {{ $notification->message }}
                                <a href="{{ route('notifications.read', $notification->id) }}" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('copyBtn').addEventListener('click', function() {
        var copyText = document.getElementById('referralLink');
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        var originalText = this.innerHTML;
        this.innerHTML = 'Copied!';
        var button = this;
        
        setTimeout(function() {
            button.innerHTML = originalText;
        }, 2000);
    });
</script>
@endpush