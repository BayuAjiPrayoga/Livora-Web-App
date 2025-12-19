@extends('layouts.public')

@section('title', 'LIVORA - Platform Kost & Boarding House Terpercaya')

@section('content')
<!-- Modern Styles -->
<style>
    @keyframes gradientFlow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(5deg); }
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    .glass-effect {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .property-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .property-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
</style>

<!-- Hero Section -->
<section class="relative text-white overflow-hidden" style="background: linear-gradient(120deg, #ff6b35 0%, #ff8c42 25%, #ffa552 50%, #ffbe63 75%, #ffd275 100%); background-size: 200% 200%; animation: gradientFlow 15s ease infinite; min-height: 90vh; display: flex; align-items: center;">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-float" style="animation-duration: 8s;"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-orange-300/20 rounded-full blur-3xl animate-float" style="animation-duration: 12s; animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-yellow-200/10 rounded-full blur-3xl animate-float" style="animation-duration: 10s; animation-delay: 4s;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
        <div class="text-center animate-fade-in-up">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 mb-6 glass-effect rounded-full text-sm font-semibold text-white">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Platform Kost #1 di Indonesia
            </div>

            <h1 class="text-5xl md:text-7xl font-extrabold mb-8 leading-tight" style="animation-delay: 0.2s;">
                <span class="block text-white drop-shadow-2xl">Temukan Hunian</span>
                <span class="block bg-gradient-to-r from-white to-orange-100 bg-clip-text text-transparent mt-2">Ideal Anda</span>
            </h1>
            
            <p class="text-xl md:text-2xl mb-12 text-white/95 max-w-3xl mx-auto font-light leading-relaxed" style="animation-delay: 0.4s; text-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                Ribuan kost & boarding house terverifikasi dengan proses booking yang mudah dan aman
            </p>
            
            <!-- Modern Search Box -->
            <div class="max-w-3xl mx-auto" style="animation-delay: 0.6s;">
                <form action="{{ route('browse') }}" method="GET" class="glass-effect rounded-2xl p-2 shadow-2xl">
                    <div class="flex flex-col md:flex-row gap-3">
                        <div class="flex-1 relative group">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-orange-500 transition-all group-focus-within:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari nama kost, lokasi, atau area..." 
                                   class="w-full pl-12 pr-4 py-4 bg-white/95 backdrop-blur-sm rounded-xl border-2 border-transparent focus:border-orange-400 focus:bg-white outline-none transition-all text-gray-800 placeholder-gray-400 font-medium shadow-sm">
                        </div>
                        <button type="submit" 
                                class="px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari Sekarang
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Stats -->
            <div class="mt-16 flex flex-wrap justify-center gap-8 text-white/90">
                <div class="text-center">
                    <div class="text-3xl font-bold">{{ $stats['total_properties'] }}+</div>
                    <div class="text-sm font-medium text-white/80">Properti</div>
                </div>
                <div class="w-px bg-white/30"></div>
                <div class="text-center">
                    <div class="text-3xl font-bold">{{ $stats['available_rooms'] }}+</div>
                    <div class="text-sm font-medium text-white/80">Kamar Tersedia</div>
                </div>
                <div class="w-px bg-white/30"></div>
                <div class="text-center">
                    <div class="text-3xl font-bold">4.8★</div>
                    <div class="text-sm font-medium text-white/80">Rating Pengguna</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modern Wave Shape -->
    <div class="absolute bottom-0 left-0 right-0" style="transform: translateY(1px);">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="width: 100%; height: 120px;">
            <path d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z" fill="white" fill-opacity="1"/>
        </svg>
    </div>
</section>


<!-- Statistics Section - Modern Cards -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-gray-100 relative overflow-hidden">
    <!-- Decorative Background -->
    <div class="absolute inset-0 opacity-40">
        <div class="absolute top-0 left-0 w-72 h-72 bg-orange-200 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-200 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-black bg-gradient-to-r from-orange-600 to-orange-400 bg-clip-text text-transparent">
                            {{ $stats['total_properties'] }}+
                        </div>
                        <div class="text-sm text-gray-500 font-semibold mt-1">Properties</div>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Kost Terdaftar</h3>
                <p class="text-gray-600 leading-relaxed">Pilihan properti berkualitas yang telah terverifikasi</p>
            </div>
            
            <!-- Card 2 -->
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-black bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">
                            {{ $stats['total_rooms'] }}+
                        </div>
                        <div class="text-sm text-gray-500 font-semibold mt-1">Rooms</div>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Total Kamar</h3>
                <p class="text-gray-600 leading-relaxed">Berbagai tipe kamar sesuai kebutuhan Anda</p>
            </div>
            
            <!-- Card 3 -->
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-black bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent">
                            {{ $stats['available_rooms'] }}+
                        </div>
                        <div class="text-sm text-gray-500 font-semibold mt-1">Available</div>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Kamar Siap Huni</h3>
                <p class="text-gray-600 leading-relaxed">Tersedia untuk ditempati segera</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Properties Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-orange-100 rounded-full text-orange-600 font-semibold text-sm mb-4">
                Rekomendasi Terbaik
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                Kost & Boarding House <span class="bg-gradient-to-r from-orange-500 to-orange-600 bg-clip-text text-transparent">Pilihan</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Temukan hunian impian Anda dari koleksi properti terbaik yang kami tawarkan
            </p>
        </div>
        
        @if($properties->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($properties as $property)
                    <div class="property-card group bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                        <!-- Property Image -->
                        <div class="relative h-56 overflow-hidden">
                            @if($property->images && count($property->images) > 0)
                                <img src="{{ asset('storage/' . $property->images[0]) }}" 
                                     alt="{{ $property->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="flex items-center justify-center h-full bg-gradient-to-br from-orange-400 to-orange-600">
                                    <svg class="w-20 h-20 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Availability Badge -->
                            @if($property->available_rooms_count > 0)
                                <div class="absolute top-4 right-4 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Tersedia
                                </div>
                            @else
                                <div class="absolute top-4 right-4 px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg">
                                    Penuh
                                </div>
                            @endif

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        
                        <!-- Property Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-1 group-hover:text-orange-600 transition-colors">
                                {{ $property->name }}
                            </h3>
                            
                            <div class="flex items-start text-gray-600 mb-3">
                                <svg class="w-5 h-5 mr-2 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm line-clamp-2">{{ $property->address }}, {{ $property->city }}</span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                {{ $property->description }}
                            </p>
                            
                            <!-- Features -->
                            <div class="flex items-center gap-4 mb-5 text-sm">
                                <div class="flex items-center text-gray-700 bg-gray-50 px-3 py-1.5 rounded-lg">
                                    <svg class="w-4 h-4 mr-1.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <span class="font-semibold">{{ $property->rooms_count }} Kamar</span>
                                </div>
                                @if($property->available_rooms_count > 0)
                                    <div class="flex items-center text-green-700 bg-green-50 px-3 py-1.5 rounded-lg">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-semibold">{{ $property->available_rooms_count }} Tersedia</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Price & CTA -->
                            <div class="flex items-center justify-between pt-5 border-t border-gray-100">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1 font-medium">Mulai dari</div>
                                    <div class="text-2xl font-black bg-gradient-to-r from-orange-600 to-orange-500 bg-clip-text text-transparent">
                                        Rp {{ number_format($property->rooms->min('price'), 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">per bulan</div>
                                </div>
                                <a href="{{ route('properties.show', $property->id) }}" 
                                   class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                    <span>Detail</span>
                                    <svg class="w-4 h-4 ml-1.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- View All Button -->
            <div class="text-center mt-16">
                <a href="{{ route('browse') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <span>Lihat Semua Properti</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        @else
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Properti Tersedia</h3>
                <p class="text-gray-600 text-lg">Kami sedang menambahkan properti baru. Silakan cek kembali nanti.</p>
            </div>
        @endif
    </div>
</section>


<!-- Why Choose Us Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 via-white to-orange-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-gradient-to-r from-orange-100 to-orange-50 rounded-full text-orange-600 font-semibold text-sm mb-4">
                Keunggulan Kami
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                Mengapa Memilih <span class="bg-gradient-to-r from-orange-500 to-orange-600 bg-clip-text text-transparent">LIVORA?</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Kami berkomitmen memberikan pengalaman terbaik dalam mencari hunian impian Anda
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="group relative bg-white rounded-2xl p-8 shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-100 to-transparent rounded-bl-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl mb-6 shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Properti Terverifikasi</h3>
                    <p class="text-gray-600 leading-relaxed">Semua properti telah melalui proses verifikasi ketat untuk memastikan kualitas dan keamanan hunian Anda</p>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="group relative bg-white rounded-2xl p-8 shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-transparent rounded-bl-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl mb-6 shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Proses Super Cepat</h3>
                    <p class="text-gray-600 leading-relaxed">Booking online yang mudah dan instan tanpa ribet. Dapatkan konfirmasi dalam hitungan menit</p>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="group relative bg-white rounded-2xl p-8 shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-100 to-transparent rounded-bl-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl mb-6 shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Harga Transparan</h3>
                    <p class="text-gray-600 leading-relaxed">Tidak ada biaya tersembunyi. Semua harga jelas dan transparan, termasuk biaya admin dan deposit</p>
                </div>
            </div>
        </div>

        <!-- Additional Features -->
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="text-3xl font-bold text-orange-600 mb-2">24/7</div>
                <div class="text-sm text-gray-600 font-medium">Customer Support</div>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="text-3xl font-bold text-blue-600 mb-2">100%</div>
                <div class="text-sm text-gray-600 font-medium">Aman & Terpercaya</div>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="text-3xl font-bold text-green-600 mb-2">5K+</div>
                <div class="text-sm text-gray-600 font-medium">Pengguna Aktif</div>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="text-3xl font-bold text-purple-600 mb-2">99%</div>
                <div class="text-sm text-gray-600 font-medium">Kepuasan Pengguna</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="relative py-20 overflow-hidden">
    <!-- Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700"></div>
    
    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl animate-float" style="animation-duration: 10s;"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-300 rounded-full blur-3xl animate-float" style="animation-duration: 15s; animation-delay: 2s;"></div>
    </div>
    
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white z-10">
        <!-- Icon -->
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-8 animate-pulse">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
        </div>

        <h2 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight">
            Siap Menemukan Hunian<br>Impian Anda?
        </h2>
        <p class="text-xl md:text-2xl mb-10 text-white/95 max-w-3xl mx-auto font-light">
            Bergabunglah dengan ribuan pengguna yang telah menemukan kost terbaik mereka
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('register') }}" 
               class="group inline-flex items-center px-8 py-4 bg-white text-orange-600 font-bold rounded-xl shadow-2xl hover:shadow-orange-300/50 transform hover:scale-105 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Daftar Gratis Sekarang
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
            <a href="{{ route('browse') }}" 
               class="group inline-flex items-center px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-semibold rounded-xl border-2 border-white hover:bg-white hover:text-orange-600 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Jelajahi Properti
            </a>
        </div>

        <!-- Trust Indicators -->
        <div class="mt-12 flex flex-wrap justify-center gap-8 text-white/90">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">Gratis Tanpa Biaya</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">Proses Mudah & Cepat</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">Terpercaya & Aman</span>
            </div>
        </div>
    </div>
</section>
@endsection
