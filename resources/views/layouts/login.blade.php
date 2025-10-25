<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Choice - MLM Platform')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
            background-color: #f8f9fc;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
            transition: transform 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h4 {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu li:last-child {
            border-bottom: none;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-menu li a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 25px;
        }

        .sidebar-menu li.active a {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-left: 4px solid #fff;
        }

        .sidebar-menu .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 10px 0;
        }

        .sidebar-menu .badge {
            font-size: 0.7rem;
            margin-left: auto;
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        /* Top Bar */
        .top-bar {
            background: #ffffff;
            border-bottom: 1px solid #e3e6f0;
            padding: 15px 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .top-bar .user-info {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .user-avatar img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            border: 2px solid #667eea;
        }

        .user-info span {
            font-weight: 600;
            color: #5a5c69;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8f9fc;
        }

        .page-content {
            padding: 25px;
            flex: 1;
        }

        /* Mobile Styles */
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                transform: translateX(-100%);
                z-index: 1050;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
            }

            .sidebar-overlay.active {
                display: block;
            }

            .menu-toggle {
                display: inline-block;
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: #5a5c69;
            }

            .top-bar h4 {
                font-size: 1.2rem;
            }
        }

        @media (min-width: 993px) {
            .menu-toggle {
                display: none;
            }
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    @stack('styles')
</head>
<body>
    @guest
        @yield('content')
    @else
        <div class="app-container">
            <!-- Sidebar -->
            <nav class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <h4 class="text-white">
                        <i class="fas fa-gem me-2"></i>Smart Choice
                    </h4>
                    <small class="text-white-50">MLM Platform</small>
                </div>
                
                <ul class="sidebar-menu">
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>Dashboard
                        </a>
                    </li>
                    
                    <li class="{{ request()->routeIs('investments.*') ? 'active' : '' }}">
                        <a href="{{ route('investments.index') }}">
                            <i class="fas fa-chart-line"></i>Investments
                        </a>
                    </li>
                    
                    <li class="{{ request()->routeIs('wallet.*') ? 'active' : '' }}">
                        <a href="{{ route('wallet.index') }}">
                            <i class="fas fa-wallet"></i>Wallet
                        </a>
                    </li>
                    
                    <li class="{{ request()->routeIs('referrals.*') ? 'active' : '' }}">
                        <a href="{{ route('referrals.index') }}">
                            <i class="fas fa-users"></i>Referrals
                        </a>
                    </li>
                    
                    <li class="{{ request()->routeIs('withdrawals.*') ? 'active' : '' }}">
                        <a href="{{ route('withdrawals.index') }}">
                            <i class="fas fa-money-bill-wave"></i>Withdrawals
                        </a>
                    </li>
                    
                    <li class="divider"></li>
                    
                    <li class="{{ request()->routeIs('profile') || request()->routeIs('password.change') ? 'active' : '' }}">
                        <a href="{{ route('profile') }}">
                            <i class="fas fa-user"></i>Profile
                        </a>
                    </li>
                    
                        {{-- <li class="{{ request()->routeIs('user.kyc') ? 'active' : '' }}">
                            <a href="{{ route('user.kyc') }}">
                                <i class="fas fa-id-card"></i>KYC Verification
                            </a>
                        </li> --}}
                    
                    <li class="{{ request()->routeIs('notifications') ? 'active' : '' }}">
                        <a href="{{ route('notifications') }}" class="position-relative">
                            <i class="fas fa-bell"></i>Notifications
                            @php
                                $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge bg-danger ms-auto">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    <li class="divider"></li>
                    
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>

            <!-- Overlay for Mobile -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
            
            <!-- Main Content -->
            <div class="main-content">
                <!-- Top Bar -->
                <header class="top-bar d-flex justify-content-between align-items-center">
                    <button class="menu-toggle text-dark" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="mb-0 text-gray-800">@yield('page-title', 'Dashboard')</h4>
                    <div class="user-info d-none d-md-flex align-items-center">
                        <span class="me-3">Welcome, {{ Auth::user()->name }}</span>
                        <div class="user-avatar">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff" alt="User Avatar">
                        </div>
                    </div>
                </header>
                
                <!-- Page Content -->
                <div class="page-content">
                    @include('components.messages')
                    @yield('content')
                </div>
            </div>
        </div>
    @endguest

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('active');
        });

        // Close sidebar when clicking on a link (mobile)
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 993) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>