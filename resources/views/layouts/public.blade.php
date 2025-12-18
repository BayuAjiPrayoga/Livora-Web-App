<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LIVORA - Platform Kost & Boarding House Terpercaya')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background: linear-gradient(135deg, #fff4e6 0%, #ffe0b3 50%, #ffcc80 100%); min-height: 100vh; font-family: system-ui, sans-serif;">
    <!-- Navigation -->
    <nav style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); box-shadow: 0 4px 20px rgba(255,105,0,0.1); position: sticky; top: 0; z-index: 50; border-bottom: 1px solid rgba(255,179,102,0.2);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 lg:h-18">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center group">
                        <div class="relative">
                            <div class="w-10 h-10 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-xl flex items-center justify-center shadow-tiket group-hover:shadow-tiket-hover transition-all duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <span class="text-2xl font-bold text-tiket-dark tracking-tight">LIVORA</span>
                            <div class="text-xs text-tiket-primary font-medium">Find Your Home</div>
                        </div>
                    </a>
                    
                    <!-- Navigation Links -->
                    <div class="hidden md:ml-12 md:flex md:space-x-2">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('home') ? 'bg-tiket-primary text-white shadow-md' : 'text-tiket-dark hover:bg-gray-100 hover:text-tiket-primary' }}">
                            Beranda
                        </a>
                        <a href="{{ route('browse') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('browse') || request()->routeIs('properties.show') ? 'bg-tiket-primary text-white shadow-md' : 'text-tiket-dark hover:bg-gray-100 hover:text-tiket-primary' }}">
                            Cari Kost
                        </a>
                        <a href="{{ route('about') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('about') ? 'bg-tiket-primary text-white shadow-md' : 'text-tiket-dark hover:bg-gray-100 hover:text-tiket-primary' }}">
                            Tentang Kami
                        </a>
                        <a href="{{ route('contact') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-tiket-primary text-white shadow-md' : 'text-tiket-dark hover:bg-gray-100 hover:text-tiket-primary' }}">
                            Kontak
                        </a>
                    </div>
                </div>
                
                <!-- Right Side -->
                <div class="flex items-center space-x-2 lg:space-x-3">
                    @auth
                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition-all duration-200">
                                <div class="w-8 h-8 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-lg flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="text-sm font-semibold text-tiket-dark">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-tiket-lg border border-tiket-border py-2">
                                @if(auth()->user()->role === 'tenant')
                                    <a href="{{ route('tenant.dashboard') }}" class="block px-4 py-2 text-sm text-tiket-dark hover:bg-gray-50 transition-colors">
                                        Dashboard Saya
                                    </a>
                                @elseif(auth()->user()->role === 'owner')
                                    <a href="{{ route('mitra.dashboard') }}" class="block px-4 py-2 text-sm text-tiket-dark hover:bg-gray-50 transition-colors">
                                        Dashboard Mitra
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-tiket-error hover:bg-red-50 transition-colors">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-orange-700 hover:text-white border-2 border-orange-500 rounded-xl hover:bg-orange-500 transition-all duration-200 bg-white">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-600 to-orange-500 text-white font-bold rounded-xl hover:from-orange-700 hover:to-orange-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Daftar Sekarang
                        </a>
                    @endauth
                    
                    <!-- Mobile menu button -->
                    <button type="button" class="md:hidden inline-flex items-center justify-center p-2 ml-2 rounded-xl text-orange-600 hover:bg-orange-50 focus:outline-none transition-colors duration-200" onclick="toggleMobileMenu()">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="hidden md:hidden bg-tiket-surface border-t border-tiket-border" id="mobile-menu">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="{{ route('home') }}" class="block px-4 py-3 text-base font-semibold rounded-xl {{ request()->routeIs('home') ? 'bg-tiket-primary text-white' : 'text-tiket-dark hover:bg-gray-100' }} transition-colors">
                    Beranda
                </a>
                <a href="{{ route('browse') }}" class="block px-4 py-3 text-base font-semibold rounded-xl {{ request()->routeIs('browse') ? 'bg-tiket-primary text-white' : 'text-tiket-dark hover:bg-gray-100' }} transition-colors">
                    Cari Kost
                </a>
                <a href="{{ route('about') }}" class="block px-4 py-3 text-base font-semibold rounded-xl {{ request()->routeIs('about') ? 'bg-tiket-primary text-white' : 'text-tiket-dark hover:bg-gray-100' }} transition-colors">
                    Tentang Kami
                </a>
                <a href="{{ route('contact') }}" class="block px-4 py-3 text-base font-semibold rounded-xl {{ request()->routeIs('contact') ? 'bg-tiket-primary text-white' : 'text-tiket-dark hover:bg-gray-100' }} transition-colors">
                    Kontak
                </a>
                
                @guest
                    <div class="border-t border-tiket-border pt-4 mt-4 space-y-2">
                        <a href="{{ route('login') }}" class="block px-4 py-3 text-base font-semibold text-orange-700 hover:bg-orange-50 border-2 border-orange-500 rounded-xl transition-colors text-center">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-3 bg-gradient-to-r from-orange-600 to-orange-500 text-white font-bold rounded-xl text-center hover:from-orange-700 hover:to-orange-600">
                            Daftar Sekarang
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-tiket-dark text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-tiket-primary to-tiket-secondary rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <span class="text-2xl font-bold">LIVORA</span>
                            <div class="text-sm text-tiket-primary font-medium">Find Your Home</div>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-6 max-w-md leading-relaxed">
                        Platform terpercaya untuk menemukan kost dan boarding house impian Anda. Kami menghubungkan penyewa dengan pemilik properti terbaik di seluruh Indonesia.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Instagram">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="WhatsApp">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('browse') }}" class="text-gray-400 hover:text-white transition-colors">Cari Kost</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Hubungi Kami</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Jakarta, Indonesia</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>info@livora.com</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>+62 812 3456 7890</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} LIVORA. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
