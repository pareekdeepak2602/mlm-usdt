@extends('layouts.app')

@section('title', 'Reset Password - Smart Choice MLM Platform')

@section('content')
<div class="reset-password-container">
    <div class="row g-0">
        <div class="col-lg-6 reset-password-image d-none d-lg-block">
            <div class="reset-password-image-content">
                <div class="logo">
                    <i class="fas fa-gem me-2"></i>Smart Choice
                </div>
                <h2 class="mb-4">Create New Password</h2>
                <p class="mb-4">Enter your new password and confirm it to secure your account.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Strong Password</h5>
                            <p class="mb-0 small">Use a combination of letters, numbers, and symbols</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Account Security</h5>
                            <p class="mb-0 small">Keep your account secure with a strong password</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Quick Access</h5>
                            <p class="mb-0 small">Get back to managing your investments quickly</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="reset-password-form">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Reset Password</h3>
                    <p class="text-muted">Enter your new password below</p>
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
                
                <form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Password must be at least 6 characters long.
                        </small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Password Requirements</h6>
                        <ul class="mb-0 small">
                            <li>Minimum 6 characters</li>
                            <li>Use a combination of letters and numbers</li>
                            <li>Avoid using common words or personal information</li>
                            <li>Consider using a password manager</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-reset">Reset Password</button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p>Remember your password? <a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a></p>
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
    .reset-password-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 100%;
        max-width: 900px;
    }
    .reset-password-form {
        padding: 40px;
    }
    .reset-password-image {
        background: url('https://picsum.photos/seed/security/600/800.jpg') center center/cover;
        position: relative;
    }
    .reset-password-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(102, 126, 234, 0.7);
    }
    .reset-password-image-content {
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