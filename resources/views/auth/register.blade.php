@extends('layouts.login')

@section('title', 'Register - Smart Choice MLM Platform')

@section('content')
<div class="register-container">
    <div class="row g-0">
        <div class="col-lg-6 register-image d-none d-lg-block">
            <div class="register-image-content">
                <div class="logo">
                    <i class="fas fa-gem me-2"></i>Smart Choice
                </div>
                <h2 class="mb-4">Join Smart Choice MLM Platform</h2>
                <p class="mb-4">Start your journey to financial freedom with our innovative platform that combines MLM benefits with USDT trading opportunities.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Daily Earnings</h5>
                            <p class="mb-0 small">Earn daily based on your investment level</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Referral Bonuses</h5>
                            <p class="mb-0 small">Get rewarded for bringing new members</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Leadership Rewards</h5>
                            <p class="mb-0 small">Unlock special bonuses as you grow your team</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="register-form">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Create Account</h3>
                    <p class="text-muted">Join our community today</p>
                </div>
                
                <form action="{{ route('register') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="referral_code" class="form-label">Referral Code (Optional)</label>
                        <input type="text" class="form-control" id="referral_code" name="referral_code" value="{{ $referralCode ?? old('referral_code') }}">
                        @error('referral_code')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">I agree to the Terms and Conditions</label>
                        @error('terms')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-register">Create Account</button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p>Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .register-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 100%;
        max-width: 900px;
    }
    .register-form {
        padding: 40px;
    }
    .register-image {
        background: url('https://picsum.photos/seed/mlm/600/800.jpg') center center/cover;
        position: relative;
    }
    .register-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(102, 126, 234, 0.7);
    }
    .register-image-content {
        position: relative;
        z-index: 1;
        color: white;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 40px;
    }
    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    .btn-register {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    .logo {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .features {
        margin-top: 30px;
    }
    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    .feature-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
</style>
@endpush