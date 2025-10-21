<!-- Sidebar -->
<div id="sidebar" class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
    <!-- Logo -->
    <div class="flex items-center space-x-2 px-4">
        <i class="fas fa-chart-line text-2xl"></i>
        <span class="text-2xl font-extrabold">MLM Admin</span>
    </div>
    
    <!-- Nav -->
    <nav>
        <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
        </a>
        
        <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-users mr-2"></i>User Management
        </a>
        
        <a href="{{ route('admin.plans.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.plans.*') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-chart-pie mr-2"></i>Investment Plans
        </a>
        
        <a href="{{ route('admin.withdrawals.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.withdrawals.*') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-money-bill-wave mr-2"></i>Withdrawal Requests
        </a>
        
        <a href="{{ route('admin.transactions.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.transactions.*') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-exchange-alt mr-2"></i>Transactions
        </a>
        
        <a href="{{ route('admin.kyc.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.kyc.*') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-id-card mr-2"></i>KYC Verification
        </a>
        
        <a href="{{ route('admin.contact-messages.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.contact-messages.*') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-envelope mr-2"></i>Contact Messages
        </a>
        
        <a href="{{ route('admin.reports.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.reports.*') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-chart-bar mr-2"></i>Reports
        </a>
        
        <a href="{{ route('admin.settings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-cog mr-2"></i>System Settings
        </a>
    </nav>
</div>

<!-- Mobile menu button -->
<div class="md:hidden">
    <button id="mobile-menu-button" class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
        <i class="fas fa-bars"></i>
    </button>
</div>