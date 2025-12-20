<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'LIVORA - Live Better, Stay Better')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="flex h-full">
        <!-- Mobile Sidebar Overlay -->
        <div class="fixed inset-0 z-40 md:hidden hidden" id="sidebar-overlay">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" onclick="toggleMobileSidebar()"></div>
        </div>

        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col fixed inset-y-0 z-50" id="desktop-sidebar">
            <div class="sidebar flex flex-col flex-grow pt-5 pb-4 overflow-y-auto scrollbar-thin">
                <!-- Logo -->
                <div class="flex items-center justify-between flex-shrink-0 px-4 mb-8">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold gradient-text">LIVORA</h1>
                        <span class="ml-2 px-2.5 py-0.5 text-xs bg-gradient-to-r from-orange-50 to-amber-50 text-[#ff6900] font-semibold rounded-lg">Tenant</span>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="mt-5 flex-1 px-3 space-y-1">
                    <!-- Browse Kost -->
                    <a href="{{ route('browse') }}" class="nav-link">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Cari Kost</span>
                    </a>

                    <!-- Divider -->
                    <hr class="divider !my-2">

                    <!-- Dashboard -->
                    <a href="{{ route('tenant.dashboard') }}" class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- My Bookings -->
                    <a href="{{ route('tenant.bookings.index') }}" class="nav-link {{ request()->routeIs('tenant.bookings.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span>Booking Saya</span>
                    </a>

                    <!-- My Tickets -->
                    <a href="{{ route('tenant.tickets.index') }}" class="nav-link {{ request()->routeIs('tenant.tickets.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <span>Tiket Saya</span>
                    </a>

                    <!-- Payments -->
                    <a href="{{ route('tenant.payments.index') }}" class="nav-link {{ request()->routeIs('tenant.payments.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Pembayaran Saya</span>
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('tenant.profile') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('tenant.profile*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profil Saya
                    </a>
                </nav>

                <!-- User Menu -->
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <div class="flex-shrink-0 w-full group block">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-tiket flex items-center justify-center shadow-tiket">
                                <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'User' }}</p>
                                <p class="text-xs font-medium text-gray-500">Tenant LIVORA</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-tiket-primary hover:text-tiket-secondary font-medium transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 transform -translate-x-full md:hidden transition-transform duration-300 ease-in-out bg-white" id="mobile-sidebar">
            <div class="flex flex-col h-full pt-5 pb-4 overflow-y-auto">
                <!-- Logo with Close Button -->
                <div class="flex items-center justify-between px-4 mb-8">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-tiket-primary to-tiket-secondary bg-clip-text text-transparent">LIVORA</h1>
                        <span class="ml-2 text-sm text-tiket-primary font-semibold">Tenant</span>
                    </div>
                    <button onclick="toggleMobileSidebar()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Navigation (Same as desktop) -->
                <nav class="mt-5 flex-1 px-2 space-y-1">
                    <a href="{{ route('browse') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket text-gray-600 hover:bg-tiket-light hover:text-tiket-primary transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari Kost
                    </a>
                    <div class="border-t border-gray-200 my-3"></div>
                    <a href="{{ route('tenant.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('tenant.dashboard') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('tenant.bookings.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('tenant.bookings.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Booking Saya
                    </a>
                    <a href="{{ route('tenant.tickets.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('tenant.tickets.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Tiket Saya
                    </a>
                    <a href="{{ route('tenant.payments.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('tenant.payments.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Pembayaran Saya
                    </a>
                    <a href="{{ route('tenant.profile') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('tenant.profile*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profil Saya
                    </a>
                </nav>

                <!-- User Menu (Mobile) -->
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <div class="flex-shrink-0 w-full group block">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-tiket flex items-center justify-center shadow-tiket">
                                <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'User' }}</p>
                                <p class="text-xs font-medium text-gray-500">Tenant LIVORA</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-tiket-primary hover:text-tiket-secondary font-medium transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden md:ml-64">
            <!-- Mobile Top Bar -->
            <div class="md:hidden bg-white border-b border-gray-200 shadow-sm sticky top-0 z-30">
                <div class="px-4 py-3 flex justify-between items-center">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold bg-gradient-to-r from-tiket-primary to-tiket-secondary bg-clip-text text-transparent">LIVORA</h1>
                        <span class="ml-2 text-xs text-tiket-primary font-semibold">Tenant</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-tiket-primary" title="Beranda">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </a>
                        <button type="button" onclick="toggleMobileSidebar()" class="text-gray-600 hover:text-tiket-primary">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-livora-background">
                <!-- Flash Messages -->
                <div class="p-4 md:px-6">
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4 rounded-r shadow-sm" role="alert">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                                <button type="button" class="ml-3 inline-flex text-green-400 hover:text-green-600 focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded-r shadow-sm" role="alert">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                                <button type="button" class="ml-3 inline-flex text-red-400 hover:text-red-600 focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded-r shadow-sm" role="alert">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-semibold text-red-800 mb-2">Terjadi kesalahan:</p>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="ml-3 inline-flex text-red-400 hover:text-red-600 focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
    
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isHamburgerButton = event.target.closest('button[onclick="toggleMobileSidebar()"]');
            
            if (!isClickInsideSidebar && !isHamburgerButton && !sidebar.classList.contains('-translate-x-full')) {
                toggleMobileSidebar();
            }
        });
    </script>
</body>
</html>