<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MLM + USDT Trading Platform')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }

        .app-container {
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #1e1e2d;
            color: white;
            min-height: 100vh;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 20px;
            background: #27293d;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.2s;
        }

        .sidebar-menu li.active a,
        .sidebar-menu li a:hover {
            background: #4e54c8;
        }

        .sidebar-menu .badge {
            font-size: 0.7rem;
        }

        /* Top Bar */
        .top-bar {
            background: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 20px;
        }

        .top-bar .user-info {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .user-avatar img {
            border-radius: 50%;
            width: 35px;
            height: 35px;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .page-content {
            padding: 20px;
        }

        /* Mobile */
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
            }
        }

        @media (min-width: 993px) {
            .menu-toggle {
                display: none;
            }
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
                        <i class="fas fa-coins me-2"></i>MLM Platform
                    </h4>
                </div>
                
                <ul class="sidebar-menu">
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('investments.*') ? 'active' : '' }}">
                        <a href="{{ route('investments.index') }}">
                            <i class="fas fa-chart-line me-2"></i>Investments
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('wallet.*') ? 'active' : '' }}">
                        <a href="{{ route('wallet.index') }}">
                            <i class="fas fa-wallet me-2"></i>Wallet
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('referrals.*') ? 'active' : '' }}">
                        <a href="{{ route('referrals.index') }}">
                            <i class="fas fa-users me-2"></i>Referrals
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('withdrawals.*') ? 'active' : '' }}">
                        <a href="{{ route('withdrawals.index') }}">
                            <i class="fas fa-money-bill-wave me-2"></i>Withdrawals
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                        <a href="{{ route('profile') }}">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('notifications') ? 'active' : '' }}">
                        <a href="{{ route('notifications') }}" class="position-relative">
                            <i class="fas fa-bell me-2"></i>Notifications
                            @if(Auth::user()->notifications()->where('is_read', false)->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ Auth::user()->notifications()->where('is_read', false)->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
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
                    <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                    <div class="user-info d-none d-md-flex">
                        <span class="me-2">Welcome, {{ Auth::user()->name }}</span>
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
    </script>
    @stack('scripts')
</body>
</html>
