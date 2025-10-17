@extends('layouts.app')

@section('title', 'Forgot Password - Smart Choice MLM Platform')

@section('content')
<div class="forgot-password-container">
    <div class="row g-0">
        <div class="col-lg-6 forgot-password-image d-none d-lg-block">
            <div class="forgot-password-image-content">
                <div class="logo">
                    <i class="fas fa-gem me-2"></i>Smart Choice
                </div>
                <h2 class="mb-4">Reset Your Password</h2>
                <p class="mb-4">Enter your email address and we'll send you a link to reset your password.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Secure Process</h5>
                            <p class="mb-0 small">Your account security is our top priority</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Email Verification</h5>
                            <p class="mb-0 small">We'll send a secure link to your email</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Quick Process</h5>
                            <p class="mb-0 small">Reset your password in just a few minutes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="forgot-password-form">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Forgot Password</h3>
                    <p class="text-muted">Enter your email to reset your password</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Please fix the following errors:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('password.email') }}" method="post">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-reset">Send Reset Link</button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p>Remember your password? <a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a></p>
                    <p>Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Create Account</a></p>
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
    .forgot-password-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 100%;
        max-width: 900px;
    }
    .forgot-password-form {
        padding: 40px;
    }
    .forgot-password-image {
        background: url('https://picsum.photos/seed/password/600/800.jpg') center center/cover;
        position: relative;
    }
    .forgot-password-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(102, 126, 234, 0.7);
    }
    .forgot-password-image-content {
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
    .btn-reset {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-reset:hover {
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