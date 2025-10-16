@extends('layouts.app')

@section('page-title', 'My Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
        <div>
            <a href="{{ route('dashboard') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
            </a>
            <a href="{{ route('password.change') }}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm me-2">
                <i class="fas fa-key fa-sm text-white-50"></i> Change Password
            </a>
            {{-- <a href="{{ route('user.kyc') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-id-card fa-sm text-white-50"></i> KYC Verification
            </a> --}}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            Please fix the following errors:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           placeholder="Enter your full name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           placeholder="Enter your email address"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}" 
                                           placeholder="Enter your phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usdt_wallet_address" class="form-label">USDT BEP20 Wallet Address</label>
                                    <input type="text" 
                                           class="form-control @error('usdt_wallet_address') is-invalid @enderror" 
                                           id="usdt_wallet_address" 
                                           name="usdt_wallet_address" 
                                           value="{{ old('usdt_wallet_address', $user->usdt_wallet_address) }}" 
                                           placeholder="Enter your BEP20 USDT wallet address">
                                    @error('usdt_wallet_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        This address will be used for withdrawal payments
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Profile
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Summary -->
        <div class="col-lg-4">
            <!-- User Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Summary</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mb-3">
                            <i class="fas fa-user fa-3x text-primary"></i>
                        </div>
                        <h5 class="font-weight-bold">{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    
                    <div class="account-info">
                        <div class="info-item d-flex justify-content-between mb-2">
                            <span class="text-muted">Member Since:</span>
                            <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                        </div>
                        <div class="info-item d-flex justify-content-between mb-2">
                            <span class="text-muted">Account Status:</span>
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'inactive' ? 'warning' : 'danger') }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                        <div class="info-item d-flex justify-content-between mb-2">
                            <span class="text-muted">KYC Status:</span>
                            <span class="badge bg-{{ $user->kyc_status === 'approved' ? 'success' : ($user->kyc_status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($user->kyc_status) }}
                            </span>
                        </div>
                        <div class="info-item d-flex justify-content-between mb-2">
                            <span class="text-muted">Referral Code:</span>
                            <strong class="text-primary">{{ $user->referral_code }}</strong>
                        </div>
                        <div class="info-item d-flex justify-content-between">
                            <span class="text-muted">Last Login:</span>
                            <strong>{{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('password.change') }}" class="btn btn-outline-warning btn-block text-start">
                            <i class="fas fa-key me-2"></i> Change Password
                        </a>
                        {{-- <a href="{{ route('user.kyc') }}" class="btn btn-outline-info btn-block text-start">
                            <i class="fas fa-id-card me-2"></i> KYC Verification
                        </a> --}}
                        <a href="{{ route('referrals.index') }}" class="btn btn-outline-success btn-block text-start">
                            <i class="fas fa-users me-2"></i> My Referrals
                        </a>
                        <a href="{{ route('notifications') }}" class="btn btn-outline-primary btn-block text-start">
                            <i class="fas fa-bell me-2"></i> Notifications
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card shadow">
                <div class="card-header py-3 bg-warning">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-shield-alt me-2"></i>Security Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Use a strong, unique password
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Enable 2FA if available
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Keep your wallet address secure
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Never share your login details
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            Log out after each session
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Statistics -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-wallet fa-2x"></i>
                                    </div>
                                    <h5 class="text-primary">Total Balance</h5>
                                    <h3 class="text-primary">${{ number_format($user->wallet->deposit_balance + $user->wallet->earning_balance + $user->wallet->referral_balance, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <div class="text-success mb-2">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                    <h5 class="text-success">Total Referrals</h5>
                                    <h3 class="text-success">{{ $user->referrals()->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <div class="text-info mb-2">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                    <h5 class="text-info">Active Investments</h5>
                                    <h3 class="text-info">{{ $user->investments()->where('status', 'active')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-warning h-100">
                                <div class="card-body">
                                    <div class="text-warning mb-2">
                                        <i class="fas fa-history fa-2x"></i>
                                    </div>
                                    <h5 class="text-warning">Total Withdrawals</h5>
                                    <h3 class="text-warning">${{ number_format($user->wallet->total_withdrawn, 2) }}</h3>
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

@push('styles')
<style>
.avatar-circle {
    width: 80px;
    height: 80px;
    background-color: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.account-info {
    border-top: 1px solid #e3e6f0;
    padding-top: 15px;
}
.info-item {
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}
.info-item:last-child {
    border-bottom: none;
}
</style>
@endpush