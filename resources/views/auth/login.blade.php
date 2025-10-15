@extends('layouts.app')

@section('title', 'Login - MLM + USDT Trading Platform')

@section('content')
<div class="login-container">
    <div class="row g-0">
        <div class="col-lg-6 login-image d-none d-lg-block">
            <div class="login-image-content">
                <div class="logo">
                    <i class="fas fa-coins me-2"></i>MLM Platform
                </div>
                <h2 class="mb-4">Welcome Back</h2>
                <p class="mb-4">Sign in to access your account and continue your journey to financial freedom.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Secure Platform</h5>
                            <p class="mb-0 small">Your funds and data are protected with advanced security</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Track Earnings</h5>
                            <p class="mb-0 small">Monitor your investments and referral earnings in real-time</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">24/7 Support</h5>
                            <p class="mb-0 small">Our team is always here to help you succeed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="login-form">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Sign In</h3>
                    <p class="text-muted">Enter your credentials to access your account</p>
                </div>
                
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
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
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login">Sign In</button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p>Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register</a></p>
                    <p><a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a></p>
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
    .login-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 100%;
        max-width: 900px;
    }
    .login-form {
        padding: 40px;
    }
    .login-image {
        background: url('https://picsum.photos/seed/login/600/800.jpg') center center/cover;
        position: relative;
    }
    .login-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(102, 126, 234, 0.7);
    }
    .login-image-content {
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
    .btn-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-login:hover {
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