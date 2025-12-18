<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard - LIVORA')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Admin Styles -->
    <link href="{{ asset('css/admin-dashboard.css') }}" rel="stylesheet">
    
    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js for interactive components -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full">
    <div class="flex h-full">
        <!-- Sidebar -->
        <div class="hidden lg:flex lg:w-64 lg:flex-col fixed inset-y-0 z-50">
            <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto bg-white border-r border-gray-200">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-4 mb-8">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-tiket flex items-center justify-center shadow-tiket">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h1 class="text-xl font-bold bg-gradient-to-r from-tiket-primary to-tiket-secondary bg-clip-text text-transparent">LIVORA</h1>
                            <span class="text-xs text-tiket-primary font-semibold">Admin Panel</span>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="mt-5 flex-1 px-2 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.dashboard') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- User Management -->
                    <div class="space-y-1">
                        <button class="group w-full flex items-center px-3 py-2.5 text-sm font-medium text-gray-600 rounded-tiket hover:bg-tiket-light hover:text-tiket-primary transition-all duration-200" onclick="toggleSubmenu('users')">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Kelola User
                            <svg class="ml-auto h-4 w-4 transform transition-transform" id="users-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div class="hidden pl-11 space-y-1" id="users-submenu">
                            <a href="{{ route('admin.users.index') }}" class="group flex items-center py-1.5 text-sm text-gray-500 hover:text-tiket-primary">
                                Semua User
                            </a>
                            <a href="{{ route('admin.users.index', ['role' => 'tenant']) }}" class="group flex items-center py-1.5 text-sm text-gray-500 hover:text-tiket-primary">
                                Tenant
                            </a>
                            <a href="{{ route('admin.users.index', ['role' => 'owner']) }}" class="group flex items-center py-1.5 text-sm text-gray-500 hover:text-tiket-primary">
                                Mitra
                            </a>
                        </div>
                    </div>

                    <!-- Property Management -->
                    <a href="{{ route('admin.properties.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.properties.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Kelola Properti
                    </a>

                    <!-- Booking Management -->
                    <a href="{{ route('admin.bookings.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.bookings.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Kelola Booking
                    </a>

                    <!-- Payment Management -->
                    <a href="{{ route('admin.payments.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.payments.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Kelola Pembayaran
                    </a>

                    <!-- Ticket Management -->
                    <a href="{{ route('admin.tickets.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.tickets.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Support Tickets
                    </a>

                    <!-- Reports & Analytics -->
                    <div class="space-y-1">
                        <button class="group w-full flex items-center px-3 py-2.5 text-sm font-medium text-gray-600 rounded-tiket hover:bg-tiket-light hover:text-tiket-primary transition-all duration-200" onclick="toggleSubmenu('reports')">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Laporan & Analytics
                            <svg class="ml-auto h-4 w-4 transform transition-transform" id="reports-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div class="hidden pl-11 space-y-1" id="reports-submenu">
                            <a href="{{ route('admin.reports.revenue') }}" class="group flex items-center py-1.5 text-sm text-gray-500 hover:text-tiket-primary">
                                Laporan Revenue
                            </a>
                            <a href="{{ route('admin.reports.occupancy') }}" class="group flex items-center py-1.5 text-sm text-gray-500 hover:text-tiket-primary">
                                Tingkat Okupansi
                            </a>
                            <a href="{{ route('admin.reports.users') }}" class="group flex items-center py-1.5 text-sm text-gray-500 hover:text-tiket-primary">
                                Analisis User
                            </a>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <a href="{{ route('admin.settings.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.settings.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pengaturan Sistem
                    </a>
                </nav>

                <!-- User Profile -->
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <div class="flex-shrink-0 w-full group block">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-tiket flex items-center justify-center shadow-tiket">
                                <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-xs font-medium text-gray-500">System Administrator</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left text-sm text-tiket-primary hover:text-tiket-secondary font-medium transition-colors py-1.5 px-3 rounded-tiket hover:bg-tiket-light flex items-center">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
            <!-- Top Navigation Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Mobile menu button -->
                        <div class="flex items-center lg:hidden">
                            <button type="button" class="p-2 rounded-tiket bg-gray-50 border border-gray-200 text-gray-800 hover:bg-tiket-light hover:text-tiket-primary focus:outline-none focus:ring-2 focus:ring-tiket-primary shadow-sm transition-all" onclick="toggleMobileMenu()">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Page Title -->
                        <div class="flex-1 min-w-0">
                            <h1 class="text-2xl font-bold text-gray-900">
                                @yield('page-title', 'Admin Dashboard')
                            </h1>
                        </div>

                        <!-- Header Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="text-gray-400 hover:text-tiket-primary relative">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- Search -->
                            <div class="hidden md:block">
                                <input type="text" placeholder="Cari..." class="w-64 px-3 py-2 border border-gray-300 rounded-tiket focus:ring-tiket-primary focus:border-tiket-primary text-sm">
                            </div>

                            <!-- Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tiket-primary">
                                    <div class="h-8 w-8 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-tiket flex items-center justify-center shadow-tiket">
                                        <span class="text-xs font-semibold text-white">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                                    </div>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-tiket shadow-tiket-lg py-1 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? 'Admin' }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                    <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-tiket-light hover:text-tiket-primary">Profile</a>
                                    <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-tiket-light hover:text-tiket-primary">Settings</a>
                                    <div class="border-t border-gray-100">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-tiket-light hover:text-tiket-primary">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="fixed inset-0 z-50 lg:hidden hidden" id="mobile-menu-overlay">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" onclick="toggleMobileMenu()"></div>
        <div class="fixed inset-y-0 left-0 max-w-xs w-full bg-white shadow-xl">
            <!-- Mobile menu content -->
            <div class="flex flex-col h-full">
                <!-- Mobile Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-tiket flex items-center justify-center shadow-tiket">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h1 class="text-lg font-bold bg-gradient-to-r from-tiket-primary to-tiket-secondary bg-clip-text text-transparent">LIVORA</h1>
                            <span class="text-xs text-tiket-primary font-semibold">Admin Panel</span>
                        </div>
                    </div>
                    <button onclick="toggleMobileMenu()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Mobile Navigation -->
                <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.dashboard') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200" onclick="toggleMobileMenu()">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- User Management -->
                    <a href="{{ route('admin.users.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.users.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200" onclick="toggleMobileMenu()">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Kelola User
                    </a>

                    <!-- Property Management -->
                    <a href="{{ route('admin.properties.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.properties.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200" onclick="toggleMobileMenu()">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Kelola Properti
                    </a>

                    <!-- Booking Management -->
                    <a href="{{ route('admin.bookings.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.bookings.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200" onclick="toggleMobileMenu()">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Kelola Booking
                    </a>

                    <!-- Payment Management -->
                    <a href="{{ route('admin.payments.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.payments.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200" onclick="toggleMobileMenu()">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Kelola Pembayaran
                    </a>

                    <!-- Ticket Management -->
                    <a href="{{ route('admin.tickets.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.tickets.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200" onclick="toggleMobileMenu()">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Support Tickets
                    </a>

                    <!-- Reports -->
                    <a href="{{ route('admin.reports.revenue') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.reports.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200" onclick="toggleMobileMenu()">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Laporan & Analytics
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('admin.settings.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-tiket {{ request()->routeIs('admin.settings.*') ? 'bg-tiket-primary text-white shadow-tiket' : 'text-gray-600 hover:bg-tiket-light hover:text-tiket-primary' }} transition-all duration-200" onclick="toggleMobileMenu()">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pengaturan Sistem
                    </a>
                </nav>

                <!-- Mobile User Profile -->
                <div class="flex-shrink-0 border-t border-gray-200 p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-10 w-10 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-tiket flex items-center justify-center shadow-tiket">
                            <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs font-medium text-gray-500">System Administrator</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-sm text-tiket-primary hover:text-tiket-secondary font-medium transition-colors py-2 px-3 rounded-tiket hover:bg-tiket-light flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
    <script>
        function toggleSubmenu(id) {
            const submenu = document.getElementById(id + '-submenu');
            const arrow = document.getElementById(id + '-arrow');
            
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                arrow.style.transform = 'rotate(90deg)';
            } else {
                submenu.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        function toggleMobileMenu() {
            const overlay = document.getElementById('mobile-menu-overlay');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>