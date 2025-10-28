<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Choice MLM Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px; /* Reduced for mobile */
        }

        /* Mobile Styles */
        @media (max-width: 991.98px) {
            .register-container {
                max-width: 100%;
                margin: 0 auto;
            }

            .mobile-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 20px;
                text-align: center;
            }

            .mobile-header .logo {
                font-size: 24px;
                font-weight: bold;
                margin: 0;
            }

            .register-form {
                padding: 30px 25px;
            }
        }

        /* Desktop Styles */
        @media (min-width: 992px) {
            .register-container {
                max-width: 900px; /* Larger for desktop */
            }

            .register-form {
                padding: 50px;
            }

            .desktop-logo .logo {
                font-size: 28px;
                font-weight: bold;
                color: #667eea;
                margin-bottom: 10px;
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
                padding: 50px;
            }

            .features {
                margin-top: 30px;
            }

            .feature-item {
                display: flex;
                align-items: center;
                margin-bottom: 20px;
            }

            .feature-icon {
                width: 45px;
                height: 45px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 15px;
                flex-shrink: 0;
            }
        }

        /* Common Styles */
        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            font-size: 16px; /* Better for mobile */
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
            font-size: 16px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        a {
            color: #667eea;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #764ba2;
        }

        /* Password Toggle Styles */
        .password-input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: #495057;
        }

        .password-toggle:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.25);
            border-radius: 4px;
        }

        /* Better spacing for mobile */
        @media (max-width: 576px) {
            body {
                padding: 15px;
                align-items: flex-start;
                padding-top: 40px;
            }

            .register-container {
                border-radius: 12px;
            }

            .register-form {
                padding: 25px 20px;
            }

            .text-center h3 {
                font-size: 1.5rem;
            }

            .form-control {
                padding: 14px 15px; /* Larger touch targets */
            }

            .btn-register {
                padding: 14px;
            }
            
            .password-toggle {
                right: 15px;
                width: 28px;
                height: 28px;
            }
        }

        /* Extra small devices */
        @media (max-width: 375px) {
            .register-form {
                padding: 20px 15px;
            }

            .text-center h3 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
<div class="register-container">
    <!-- Mobile Header -->
    <div class="mobile-header d-block d-lg-none">
        <div class="logo">
            <i class="fas fa-gem me-2"></i>Smart Choice
        </div>
    </div>

    <div class="row g-0">
        <!-- Left Side - Image & Features (Hidden on mobile) -->
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

        <!-- Right Side - Register Form -->
        <div class="col-lg-6">
            <div class="register-form">
                <!-- Desktop Logo -->
                <div class="desktop-logo d-none d-lg-block text-center mb-4">
                    <div class="logo">
                        <i class="fas fa-gem me-2"></i>Smart Choice
                    </div>
                </div>

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
                        <div class="password-input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" class="password-toggle" id="passwordToggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="password-input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <button type="button" class="password-toggle" id="confirmPasswordToggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle functionality
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');
        const passwordIcon = passwordToggle.querySelector('i');
        
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
        const confirmPasswordIcon = confirmPasswordToggle.querySelector('i');
        
        // Function to toggle password visibility
        function setupPasswordToggle(input, toggle, icon) {
            toggle.addEventListener('click', function() {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
            
            // Add focus style when input is focused
            input.addEventListener('focus', function() {
                toggle.style.color = '#667eea';
            });
            
            input.addEventListener('blur', function() {
                toggle.style.color = '#6c757d';
            });
        }
        
        // Setup both password toggles
        setupPasswordToggle(passwordInput, passwordToggle, passwordIcon);
        setupPasswordToggle(confirmPasswordInput, confirmPasswordToggle, confirmPasswordIcon);
    });
</script>
</body>
</html>