<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - MLM USDT Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-20 hidden md:hidden"></div>
    
    <div class="flex h-screen">
        
        @include('admin.layouts.sidebar')
        
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="bg-white shadow-md z-30 sticky top-0">
                <div class="flex items-center justify-between px-4 py-3 md:px-6 md:py-4">
                    
                    <button id="mobile-menu-button" class="p-2 mr-4 rounded-md text-gray-700 hover:text-gray-900 focus:outline-none md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <div>
                        <h1 class="text-xl md:text-2xl font-semibold text-gray-900 truncate">
                            @yield('title', 'Admin Dashboard')
                        </h1>
                    </div>
                    
                    <div class="flex items-center space-x-3 md:space-x-4">
                        <span class="text-sm md:text-base text-gray-700 hidden sm:inline">
                            Welcome, {{ Auth::user()->name }}
                        </span>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-full transition duration-150" title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                                <span class="hidden md:inline ml-1">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 md:p-6">
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 shadow-sm" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebar = document.getElementById('sidebar');
            const sidebarCloseButton = document.getElementById('sidebar-close-button');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden'); // Prevent scrolling body when menu is open
            }

            mobileMenuButton.addEventListener('click', toggleSidebar);
            sidebarCloseButton.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);

            // Close sidebar on desktop on resize if it's open
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768 && !sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>