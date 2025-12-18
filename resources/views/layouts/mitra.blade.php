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
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full">
    <div class="flex h-full">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col fixed inset-y-0 z-50">
            <div class="sidebar flex flex-col flex-grow pt-5 pb-4 overflow-y-auto scrollbar-thin">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-4 mb-8">
                    <h1 class="text-2xl font-bold gradient-text">LIVORA</h1>
                    <span class="ml-2 px-2.5 py-0.5 text-xs bg-gradient-to-r from-orange-50 to-amber-50 text-[#ff6900] font-semibold rounded-lg">Mitra</span>
                </div>
                
                <!-- Navigation -->
                <nav class="mt-5 flex-1 px-3 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('mitra.dashboard') }}" class="nav-link {{ request()->routeIs('mitra.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Properti -->
                    <a href="{{ route('mitra.properties.index') }}" class="nav-link {{ request()->routeIs('mitra.properties.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Properti Saya</span>
                    </a>

                    <!-- Booking -->
                    <a href="{{ route('mitra.bookings.index') }}" class="nav-link {{ request()->routeIs('mitra.bookings.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span>Booking</span>
                    </a>

                    <!-- CRM Tickets -->
                    <a href="{{ route('mitra.tickets.index') }}" class="nav-link {{ request()->routeIs('mitra.tickets.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <span>Kelola Tiket</span>
                    </a>

                    <!-- Payment Management -->
                    <a href="{{ route('mitra.payments.index') }}" class="nav-link {{ request()->routeIs('mitra.payments.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Kelola Pembayaran</span>
                    </a>

                    <!-- Laporan -->
                    <a href="{{ route('mitra.reports.index') }}" class="nav-link {{ request()->routeIs('mitra.reports.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Laporan</span>
                    </a>
                </nav>

                <!-- User Menu -->
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <div class="flex-shrink-0 w-full group block">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-tiket flex items-center justify-center shadow-tiket">
                                <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                            </div>1 w-11 bg-gradient-to-br from-[#ff6900] to-[#ff8533] rounded-xl flex items-center justify-center shadow-glow-orange">
                                <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name ?? 'User' }}</p>
                                <p class="text-xs font-medium text-gray-500">Mitra LIVORA</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left text-sm text-gray-600 hover:text-[#ff6900] font-medium transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Logout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Contentopnav">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button type="button" class="md:hidden -ml-2 mr-2 p-2 rounded-xl text-gray-600 hover:text-[#ff6900] hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition-all">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center space-x-3">
                            <!-- Notifications -->
                            <button class="p-2 rounded-xl text-gray-400 hover:text-[#ff6900] hover:bg-orange-50 transition-all relative">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- Profile dropdown -->
                            <div class="relative">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto focus:outline-none bg-gray-50 scrollbar-thin

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto focus:outline-none bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>