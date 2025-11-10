<div id="sidebar" class="bg-gray-800 text-white w-64 space-y-6 py-4 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-300 ease-in-out z-30 shadow-xl">
    
    <div class="flex items-center justify-between px-4 pb-2 border-b border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="fas fa-chart-line text-2xl text-blue-400"></i>
            <span class="text-xl font-extrabold tracking-wider">MLM Admin</span>
        </div>
        <button id="sidebar-close-button" class="text-gray-400 hover:text-white md:hidden p-1">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <nav class="px-2 pt-2">
        
        <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
            <i class="fas fa-tachometer-alt mr-3 w-5"></i>Dashboard
        </a>
        
        <a href="{{ route('admin.users.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
            <i class="fas fa-users mr-3 w-5"></i>User Management
        </a>
        
        <a href="{{ route('admin.plans.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.plans.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
            <i class="fas fa-chart-pie mr-3 w-5"></i>Investment Plans
        </a>
        
        <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.withdrawals.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
            <i class="fas fa-money-bill-wave mr-3 w-5"></i>Withdrawal Requests
        </a>
        
        <a href="{{ route('admin.transactions.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.transactions.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
            <i class="fas fa-exchange-alt mr-3 w-5"></i>Transactions
        </a>
        
        <a href="{{ route('admin.kyc.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.kyc.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
            <i class="fas fa-id-card mr-3 w-5"></i>KYC Verification
        </a>
          <!-- Support Management Link -->
        <a href="{{ route('admin.support.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.support.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
            <i class="fas fa-headset mr-3 w-5"></i>Support Management
        </a>
        <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.contact-messages.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
            <i class="fas fa-envelope mr-3 w-5"></i>Contact Messages
        </a>
        
        <a href="{{ route('admin.reports.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.reports.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
            <i class="fas fa-chart-bar mr-3 w-5"></i>Reports
        </a>
        <!-- Add this in the sidebar menu after the existing items -->
<a href="{{ route('admin.level-commissions.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.level-commissions.*') ? 'bg-gray-700' : '' }}">
    <i class="fas fa-sitemap mr-2"></i>Level Commissions
</a>
 <a href="{{ route('admin.profile') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.profile') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
                <i class="fas fa-user-edit mr-3 w-5"></i>My Profile
            </a>
        <a href="{{ route('admin.settings.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300' }}">
            <i class="fas fa-cog mr-3 w-5"></i>System Settings
        </a>
        
    </nav>
</div>