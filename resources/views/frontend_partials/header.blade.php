<!-- Navigation -->
<nav class="fixed top-0 w-full bg-white shadow-md z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800">Smart Choice</span>
                </div>
            </div>
            
            <div class="hidden md:flex space-x-8">
                <a href="#home" class="nav-link text-gray-700 hover:text-purple-600 transition">Home</a>
                <a href="#investment-plans" class="nav-link text-gray-700 hover:text-purple-600 transition">Investment Plans</a>
                <a href="#calculator" class="nav-link text-gray-700 hover:text-purple-600 transition">Calculator</a>
                <a href="#referral-program" class="nav-link text-gray-700 hover:text-purple-600 transition">Referral Program</a>
                <a href="#contact" class="nav-link text-gray-700 hover:text-purple-600 transition">Contact</a>
            </div>
            
            <div class="flex space-x-4">
                <a href="{{ route('login') }}" class="px-4 py-2 text-purple-600 border border-purple-600 rounded-lg hover:bg-purple-50 transition">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Register</a>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-700 hover:text-purple-600 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
        <div class="px-4 py-2 space-y-2">
            <a href="#home" class="block py-2 text-gray-700 hover:text-purple-600">Home</a>
            <a href="#investment-plans" class="block py-2 text-gray-700 hover:text-purple-600">Investment Plans</a>
            <a href="#calculator" class="block py-2 text-gray-700 hover:text-purple-600">Calculator</a>
            <a href="#referral-program" class="block py-2 text-gray-700 hover:text-purple-600">Referral Program</a>
            <a href="#contact" class="block py-2 text-gray-700 hover:text-purple-600">Contact</a>
        </div>
    </div>
</nav>