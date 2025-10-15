<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MLM + USDT Trading Platform')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @guest
        @yield('content')
    @else
        <div class="app-container">
            <!-- Sidebar -->
            <nav class="sidebar">
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
            
            <!-- Main Content -->
            <div class="main-content">
                <!-- Top Bar -->
                <header class="top-bar">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="user-info">
                                    <span class="me-2">Welcome, {{ Auth::user()->name }}</span>
                                    <div class="user-avatar">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff" alt="User Avatar">
                                    </div>
                                </div>
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
    <script src="{{ asset('js/custom.js') }}"></script>
    @stack('scripts')
</body>
</html>