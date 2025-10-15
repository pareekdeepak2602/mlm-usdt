@extends('layouts.app')

@section('page-title', 'Referrals')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Referrals</h1>
        <div>
            <a href="{{ route('referrals.tree') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm me-2">
                <i class="fas fa-sitemap fa-sm text-white-50"></i> Referral Tree
            </a>
            <a href="{{ route('referrals.earnings') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-dollar-sign fa-sm text-white-50"></i> Referral Earnings
            </a>
        </div>
    </div>

    <!-- Referral Link -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Referral Link</h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">Share this link with your friends and earn referral bonuses:</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="{{ $referralLink }}" readonly id="referralLink">
                        <button class="btn btn-primary" type="button" id="copyBtn">Copy</button>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Level A (Direct)</h5>
                                    <p class="card-text">10% Commission</p>
                                    <p class="card-text"><strong>{{ count($referralsByLevel[1] ?? []) }} Referrals</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Level B</h5>
                                    <p class="card-text">5% Commission</p>
                                    <p class="card-text"><strong>{{ count($referralsByLevel[2] ?? []) }} Referrals</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Level C</h5>
                                    <p class="card-text">3% Commission</p>
                                    <p class="card-text"><strong>{{ count($referralsByLevel[3] ?? []) }} Referrals</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Referrals
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Direct Referrals
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($referralsByLevel[1] ?? []) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
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
                                Team Size
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ Auth::user()->team_size }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sitemap fa-2x text-gray-300"></i>
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
    </div>

    <!-- Your Referrals -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Referrals</h6>
                </div>
                <div class="card-body">
                    @if($referrals->count() > 0)
                        <ul class="nav nav-tabs" id="referralTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="level1-tab" data-bs-toggle="tab" data-bs-target="#level1" type="button" role="tab" aria-controls="level1" aria-selected="true">
                                    Level A ({{ count($referralsByLevel[1] ?? []) }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="level2-tab" data-bs-toggle="tab" data-bs-target="#level2" type="button" role="tab" aria-controls="level2" aria-selected="false">
                                    Level B ({{ count($referralsByLevel[2] ?? []) }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="level3-tab" data-bs-toggle="tab" data-bs-target="#level3" type="button" role="tab" aria-controls="level3" aria-selected="false">
                                    Level C ({{ count($referralsByLevel[3] ?? []) }})
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="referralTabsContent">
                            <div class="tab-pane fade show active" id="level1" role="tabpanel" aria-labelledby="level1-tab">
                                @if(isset($referralsByLevel[1]) && count($referralsByLevel[1]) > 0)
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Join Date</th>
                                                    <th>Status</th>
                                                    <th>Bonus Earned</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($referralsByLevel[1] as $referral)
                                                    <tr>
                                                        <td>{{ $referral->referred->name }}</td>
                                                        <td>{{ $referral->referred->email }}</td>
                                                        <td>{{ $referral->created_at->format('M d, Y') }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $referral->referred->status === 'active' ? 'success' : ($referral->referred->status === 'inactive' ? 'warning' : 'danger') }}">
                                                                {{ ucfirst($referral->referred->status) }}
                                                            </span>
                                                        </td>
                                                        <td>${{ number_format($referral->bonus_amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <p class="text-muted">No Level A referrals yet</p>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="level2" role="tabpanel" aria-labelledby="level2-tab">
                                @if(isset($referralsByLevel[2]) && count($referralsByLevel[2]) > 0)
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Join Date</th>
                                                    <th>Status</th>
                                                    <th>Bonus Earned</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($referralsByLevel[2] as $referral)
                                                    <tr>
                                                        <td>{{ $referral->referred->name }}</td>
                                                        <td>{{ $referral->referred->email }}</td>
                                                        <td>{{ $referral->created_at->format('M d, Y') }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $referral->referred->status === 'active' ? 'success' : ($referral->referred->status === 'inactive' ? 'warning' : 'danger') }}">
                                                                {{ ucfirst($referral->referred->status) }}
                                                            </span>
                                                        </td>
                                                        <td>${{ number_format($referral->bonus_amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <p class="text-muted">No Level B referrals yet</p>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="level3" role="tabpanel" aria-labelledby="level3-tab">
                                @if(isset($referralsByLevel[3]) && count($referralsByLevel[3]) > 0)
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Join Date</th>
                                                    <th>Status</th>
                                                    <th>Bonus Earned</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($referralsByLevel[3] as $referral)
                                                    <tr>
                                                        <td>{{ $referral->referred->name }}</td>
                                                        <td>{{ $referral->referred->email }}</td>
                                                        <td>{{ $referral->created_at->format('M d, Y') }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $referral->referred->status === 'active' ? 'success' : ($referral->referred->status === 'inactive' ? 'warning' : 'danger') }}">
                                                                {{ ucfirst($referral->referred->status) }}
                                                            </span>
                                                        </td>
                                                        <td>${{ number_format($referral->bonus_amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <p class="text-muted">No Level C referrals yet</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">You don't have any referrals yet</p>
                            <p>Share your referral link to start earning bonuses!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
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