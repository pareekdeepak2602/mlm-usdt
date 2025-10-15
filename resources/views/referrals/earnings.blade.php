@extends('layouts.app')

@section('page-title', 'Referral Earnings')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Referral Earnings</h1>
        <div>
            <a href="{{ route('referrals.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-list fa-sm text-white-50"></i> Referral List
            </a>
            <a href="{{ route('referrals.tree') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-sitemap fa-sm text-white-50"></i> Referral Tree
            </a>
        </div>
    </div>

    <!-- Earnings Summary -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Referral Earnings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format(Auth::user()->wallet->referral_balance ?? 0, 2) }}
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Level A Earnings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($levelAEarnings ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
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
                                Level B Earnings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($levelBEarnings ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
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
                                Level C Earnings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($levelCEarnings ?? 0, 2) }}
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

    <!-- Earnings History -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Referral Earnings History</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['level' => '']) }}">All Levels</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['level' => '1']) }}">Level A Only</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['level' => '2']) }}">Level B Only</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['level' => '3']) }}">Level C Only</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    @if($referralTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Transaction ID</th>
                                        <th>From User</th>
                                        <th>Level</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($referralTransactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <code>{{ $transaction->txn_id ?? 'N/A' }}</code>
                                            </td>
                                            <td>
                                                @if($transaction->meta && isset($transaction->meta['from_user']))
                                                    {{ $transaction->meta['from_user'] }}
                                                @else
                                                    <span class="text-muted">System</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->meta && isset($transaction->meta['level']))
                                                    <span class="badge bg-{{ $transaction->meta['level'] == 1 ? 'primary' : ($transaction->meta['level'] == 2 ? 'success' : 'info') }}">
                                                        Level {{ $transaction->meta['level'] == 1 ? 'A' : ($transaction->meta['level'] == 2 ? 'B' : 'C') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </td>
                                            <td class="text-success font-weight-bold">
                                                +${{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td>
                                                {{ $transaction->description ?? 'Referral Commission' }}
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $referralTransactions->firstItem() }} to {{ $referralTransactions->lastItem() }} of {{ $referralTransactions->total() }} entries
                            </div>
                            <div>
                                {{ $referralTransactions->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-dollar-sign fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-muted">No Referral Earnings Yet</h5>
                            <p class="text-muted">You haven't earned any referral commissions yet. Start referring users to earn bonuses!</p>
                            <a href="{{ route('referrals.index') }}" class="btn btn-primary">
                                <i class="fas fa-link"></i> Get Your Referral Link
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Rates -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Commission Rates</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Level A</h5>
                                    <h2 class="text-primary">10%</h2>
                                    <p class="card-text">Direct referrals</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Level B</h5>
                                    <h2 class="text-success">5%</h2>
                                    <p class="card-text">Second level referrals</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-info">Level C</h5>
                                    <h2 class="text-info">3%</h2>
                                    <p class="card-text">Third level referrals</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection