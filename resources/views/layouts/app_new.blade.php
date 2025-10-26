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
        :root {
            --bg-primary: #f8f9fc;
            --bg-secondary: #ffffff;
            --text-primary: #5a5c69;
            --text-secondary: #858796;
            --border-color: #e3e6f0;
            --sidebar-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-bg: #ffffff;
            --card-border: #e3e6f0;
        }

        [data-theme="dark"] {
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --text-primary: #e9ecef;
            --text-secondary: #adb5bd;
            --border-color: #404040;
            --sidebar-bg: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            --card-bg: #2d2d2d;
            --card-border: #404040;
        }

        body {
            overflow-x: hidden;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            color: white;
            min-height: 100vh;
            transition: transform 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
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
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
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
            cursor: pointer;
        }

        .user-info span {
            font-weight: 600;
            color: var(--text-primary);
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--bg-primary);
        }

        .page-content {
            padding: 25px;
            flex: 1;
        }

        /* Theme Toggle */
        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Profile Sidebar */
        .profile-sidebar {
            position: fixed;
            top: 0;
            right: -350px;
            width: 350px;
            height: 100vh;
            background: var(--bg-secondary);
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease;
            z-index: 1050;
            padding: 20px;
            overflow-y: auto;
        }

        .profile-sidebar.show {
            right: 0;
        }

        .profile-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            border: 3px solid #667eea;
        }

        .wallet-balance {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .balance-amount {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
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
                color: var(--text-primary);
            }

            .top-bar h4 {
                font-size: 1.2rem;
            }

            .profile-sidebar {
                width: 100%;
                right: -100%;
            }
        }

        @media (min-width: 993px) {
            .menu-toggle {
                display: none;
            }
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar,
        .profile-sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track,
        .profile-sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb,
        .profile-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover,
        .profile-sidebar::-webkit-scrollbar-thumb:hover {
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

            <!-- Profile Sidebar -->
            <div class="profile-sidebar" id="profileSidebar">
                <div class="profile-header">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff&size=80" 
                         alt="User Avatar" class="profile-avatar">
                    <h5 class="text-primary">{{ Auth::user()->name }}</h5>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                </div>

                <div class="wallet-balance">
                    <h6 class="text-muted">Available Balance</h6>
                    <div class="balance-amount" id="profileBalance">
                        ${{ number_format($wallet->available_balance ?? 0, 2) }}
                    </div>
                    <p class="text-muted mb-0">Total Income: ${{ number_format($wallet->total_income ?? 0, 2) }}</p>
                </div>

                <div class="quick-actions">
                    <h6 class="text-muted mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('investments.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>New Investment
                        </a>
                        <a href="{{ route('withdrawals.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-money-bill-wave me-2"></i>Withdraw Funds
                        </a>
                        <a href="{{ route('profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user me-2"></i>View Profile
                        </a>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-sm btn-outline-danger w-100" onclick="closeProfileSidebar()">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
            </div>

            <!-- Overlay for Mobile -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
            <div class="sidebar-overlay" id="profileOverlay"></div>
            
            <!-- Main Content -->
            <div class="main-content">
                <!-- Top Bar -->
                <header class="top-bar d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <button class="menu-toggle text-dark me-3" id="menuToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h4 class="mb-0 text-gray-800">@yield('page-title', 'Dashboard')</h4>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="theme-toggle me-3" id="themeToggle" title="Toggle Theme">
                            <i class="fas fa-moon" id="themeIcon"></i>
                        </button>
                        <div class="user-info d-flex align-items-center">
                            <span class="me-3 d-none d-md-block">Welcome, {{ Auth::user()->name }}</span>
                            <div class="user-avatar" id="userAvatar">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff" 
                                     alt="User Avatar">
                            </div>
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
        // Theme Management
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        setTheme(savedTheme);

        themeToggle.addEventListener('click', () => {
            const newTheme = body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });

        function setTheme(theme) {
            body.setAttribute('data-theme', theme);
            themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }

        // Sidebar Management
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

        // Profile Sidebar Management
        const userAvatar = document.getElementById('userAvatar');
        const profileSidebar = document.getElementById('profileSidebar');
        const profileOverlay = document.getElementById('profileOverlay');

        userAvatar.addEventListener('click', () => {
            if (window.innerWidth < 993) {
                profileSidebar.classList.add('show');
                profileOverlay.classList.add('active');
            }
        });

        function closeProfileSidebar() {
            profileSidebar.classList.remove('show');
            profileOverlay.classList.remove('active');
        }

        profileOverlay.addEventListener('click', closeProfileSidebar);

        // Close sidebars when clicking on links (mobile)
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 993) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('active');
                }
            });
        });

        // Update profile balance dynamically
        function updateProfileBalance(balance) {
            document.getElementById('profileBalance').textContent = '$' + parseFloat(balance).toFixed(2);
        }
    </script>
    @stack('scripts')
</body>
</html>