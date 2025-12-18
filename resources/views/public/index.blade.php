@extends('layouts.public')

@section('title', 'LIVORA - Platform Kost & Boarding House Terpercaya')

@section('content')
<!-- Hero Section -->
<section class="relative text-white overflow-hidden" style="background: linear-gradient(135deg, #ff6900 0%, #ff8533 50%, #ffb366 100%); min-height: 80vh;">
    <div class="absolute inset-0" style="background: rgba(0,0,0,0.05); backdrop-filter: blur(1px);"></div>
    <div class="absolute inset-0">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(255,105,0,0.9) 0%, rgba(255,133,51,0.9) 50%, rgba(255,179,102,0.85) 100%);"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Temukan Kost Impian Anda
                <br>
                <span class="text-livora-accent">Dengan Mudah & Cepat</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-orange-100 max-w-3xl mx-auto">
                Platform terpercaya untuk menemukan kost dan boarding house terbaik di seluruh Indonesia. Proses booking yang mudah, harga transparan.
            </p>
            
            <!-- Search Box -->
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('browse') }}" method="GET" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(15px); border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); padding: 0.5rem; display: flex; flex-direction: column; gap: 0.5rem;" class="md:flex-row">
                    <div class="flex-1 relative">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5" style="color: #ff6900;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" 
                               name="search" 
                               placeholder="Cari nama kost atau lokasi..." 
                               style="width: 100%; padding: 1rem 1rem 1rem 3rem; border: 2px solid #ffeee6; border-radius: 15px; font-size: 1rem; background: white; box-shadow: 0 4px 6px rgba(255,105,0,0.1); transition: all 0.3s; outline: none;"
                               onfocus="this.style.borderColor='#ff6900'; this.style.boxShadow='0 0 0 3px rgba(255,105,0,0.1)';" 
                               onblur="this.style.borderColor='#ffeee6'; this.style.boxShadow='0 4px 6px rgba(255,105,0,0.1)';">
                    </div>
                    <button type="submit" style="background: linear-gradient(135deg, #ff6900, #ff8533); color: white; font-weight: 600; padding: 1rem 2rem; border: none; border-radius: 15px; cursor: pointer; box-shadow: 0 8px 20px rgba(255,105,0,0.3); transition: all 0.3s;"
                            onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 12px 25px rgba(255,105,0,0.4)';" 
                            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 20px rgba(255,105,0,0.3)';">
                        Cari Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Wave Shape -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="#f7fafc"/>
        </svg>
    </div>
    
    <!-- Add keyframes for animations -->
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes backgroundMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
    </style>
</section>

<!-- Statistics Section -->
<section style="padding: 4rem 1rem; background: linear-gradient(135deg, #fff4e6 0%, #ffe0b3 50%, #ffcc80 100%); position: relative;">
    <!-- Animated background shapes -->
    <div style="position: absolute; top: 10%; left: 10%; width: 100px; height: 100px; background: rgba(255,105,0,0.1); border-radius: 50%; animation: float 6s ease-in-out infinite;"></div>
    <div style="position: absolute; bottom: 10%; right: 15%; width: 80px; height: 80px; background: rgba(255,133,51,0.1); border-radius: 50%; animation: float 8s ease-in-out infinite reverse;"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="position: relative; z-index: 1;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div style="padding: 2rem; background: rgba(255,255,255,0.95); backdrop-filter: blur(15px); border-radius: 20px; box-shadow: 0 15px 35px rgba(255,105,0,0.15); border: 1px solid rgba(255,179,102,0.2); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(255,105,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(255,105,0,0.15)';">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 4rem; height: 4rem; background: linear-gradient(135deg, #ff6900, #ff8533); border-radius: 50%; margin-bottom: 1rem; box-shadow: 0 8px 20px rgba(255,105,0,0.3);">
                    <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #ff6900; margin-bottom: 0.5rem;">{{ $stats['total_properties'] }}+</div>
                <div style="color: #8b5a2b; font-weight: 600;">Kost Terdaftar</div>
            </div>
            
            <div style="padding: 2rem; background: rgba(255,255,255,0.95); backdrop-filter: blur(15px); border-radius: 20px; box-shadow: 0 15px 35px rgba(255,133,51,0.15); border: 1px solid rgba(255,179,102,0.2); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(255,133,51,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(255,133,51,0.15)';">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 4rem; height: 4rem; background: linear-gradient(135deg, #ff8533, #ffb366); border-radius: 50%; margin-bottom: 1rem; box-shadow: 0 8px 20px rgba(255,133,51,0.3);">
                    <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #ff8533; margin-bottom: 0.5rem;">{{ $stats['total_rooms'] }}+</div>
                <div style="color: #8b5a2b; font-weight: 600;">Kamar Tersedia</div>
            </div>
            
            <div style="padding: 2rem; background: rgba(255,255,255,0.95); backdrop-filter: blur(15px); border-radius: 20px; box-shadow: 0 15px 35px rgba(255,179,102,0.15); border: 1px solid rgba(255,204,128,0.2); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(255,179,102,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(255,179,102,0.15)';">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 4rem; height: 4rem; background: linear-gradient(135deg, #ffb366, #ffd699); border-radius: 50%; margin-bottom: 1rem; box-shadow: 0 8px 20px rgba(255,179,102,0.3);">
                    <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #e67300; margin-bottom: 0.5rem;">{{ $stats['available_rooms'] }}+</div>
                <div style="color: #8b5a2b; font-weight: 600;">Kamar Siap Huni</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Properties Section -->
<section style="padding: 4rem 1rem; background: linear-gradient(135deg, #ff6900 0%, #ff8533 50%, #ffb366 100%); position: relative; overflow: hidden;">
    <!-- Background Pattern -->
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px); background-size: 60px 60px;"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="position: relative; z-index: 1;">
        <div class="text-center mb-12">
            <h2 style="font-size: 3rem; font-weight: 800; color: white; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                Kost & Boarding House Pilihan
            </h2>
            <p style="font-size: 1.2rem; color: rgba(255,255,255,0.95); max-width: 600px; margin: 0 auto; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
                Temukan kost terbaik yang sesuai dengan kebutuhan dan budget Anda
            </p>
        </div>
        
        @if($properties->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($properties as $property)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <!-- Property Image -->
                        <div class="relative h-56 bg-gray-200 overflow-hidden group">
                            @if($property->images && count($property->images) > 0)
                                <img src="{{ asset('storage/' . $property->images[0]) }}" 
                                     alt="{{ $property->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="flex items-center justify-center h-full bg-gradient-to-br from-livora-primary to-blue-700">
                                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                            
                        </div>
                        
                        <!-- Property Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1">
                                {{ $property->name }}
                            </h3>
                            
                            <div class="flex items-start text-gray-600 mb-4">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm line-clamp-2">{{ $property->address }}, {{ $property->city }}</span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $property->description }}
                            </p>
                            
                            <!-- Features -->
                            <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    {{ $property->rooms_count }} Kamar
                                </div>
                                @if($property->available_rooms_count > 0)
                                    <div class="flex items-center text-green-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $property->available_rooms_count }} Tersedia
                                    </div>
                                @else
                                    <div class="flex items-center text-red-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Penuh
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Price & CTA -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    <div class="text-sm text-gray-500">Mulai dari</div>
                                    <div class="text-2xl font-bold text-livora-primary">
                                        Rp {{ number_format($property->rooms->min('price'), 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">per bulan</div>
                                </div>
                                <a href="{{ route('properties.show', $property->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-livora-primary hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    Lihat Detail
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- View All Button -->
            <div class="text-center mt-12">
                <a href="{{ route('browse') }}" 
                   class="inline-flex items-center px-8 py-3 bg-livora-accent hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-colors duration-200">
                    Lihat Semua Kost
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Kost Tersedia</h3>
                <p class="text-gray-600">Kami sedang menambahkan properti baru. Silakan cek kembali nanti.</p>
            </div>
        @endif
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Mengapa Memilih LIVORA?
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Kami berkomitmen memberikan pengalaman terbaik dalam mencari hunian impian Anda
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-livora-primary/10 rounded-full mb-4">
                    <svg class="w-8 h-8 text-livora-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Terverifikasi</h3>
                <p class="text-gray-600">Semua properti telah diverifikasi dan dijamin aman</p>
            </div>
            
            <div class="text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-livora-accent/10 rounded-full mb-4">
                    <svg class="w-8 h-8 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Proses Cepat</h3>
                <p class="text-gray-600">Booking online yang mudah dan cepat tanpa ribet</p>
            </div>
            
            <div class="text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Harga Transparan</h3>
                <p class="text-gray-600">Tidak ada biaya tersembunyi, semua jelas dan transparan</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-br from-livora-primary to-blue-700 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Siap Menemukan Kost Impian Anda?
        </h2>
        <p class="text-xl mb-8 text-blue-100">
            Daftar sekarang dan dapatkan akses ke ribuan pilihan kost terbaik
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="inline-flex items-center justify-center px-8 py-3 bg-livora-accent hover:bg-yellow-500 text-gray-900 font-bold rounded-lg transition-colors duration-200">
                Daftar Sekarang
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
            <a href="{{ route('browse') }}" 
               class="inline-flex items-center justify-center px-8 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white font-semibold rounded-lg border-2 border-white transition-colors duration-200">
                Jelajahi Kost
            </a>
        </div>
    </div>
</section>
@endsection
