<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-livora-background">
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
            <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto bg-white border-r border-gray-200">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-4 mb-8">
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-tiket-primary to-tiket-secondary bg-clip-text text-transparent">LIVORA</h1>
                    <span class="ml-2 text-sm text-tiket-primary font-semibold">Mitra</span>
                </div>
                
                <!-- Navigation -->
                <nav class="mt-5 flex-1 px-2 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('mitra.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('mitra.dashboard') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Properti -->
                    <a href="{{ route('mitra.properties.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('mitra.properties.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Properti Saya
                    </a>

                    <!-- Booking -->
                    <a href="{{ route('mitra.bookings.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('mitra.bookings.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Booking
                    </a>

                    <!-- CRM Tickets -->
                    <a href="{{ route('mitra.tickets.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('mitra.tickets.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Kelola Tiket
                    </a>

                    <!-- Payment Management -->
                    <a href="{{ route('mitra.payments.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('mitra.payments.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Kelola Pembayaran
                    </a>



                    <!-- Laporan -->
                    <a href="{{ route('mitra.reports.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('mitra.reports.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Laporan
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
                                <p class="text-xs font-medium text-gray-500">Mitra LIVORA</p>
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
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button type="button" class="md:hidden -ml-2 mr-2 p-2 rounded-tiket text-gray-600 hover:text-tiket-primary hover:bg-tiket-light focus:outline-none focus:ring-2 focus:ring-inset focus:ring-tiket-primary">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="p-1 rounded-full text-gray-400 hover:text-tiket-primary">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </button>
                            
                            <!-- Profile dropdown -->
                            <div class="relative">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-600 hover:text-tiket-primary font-medium">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto focus:outline-none bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>