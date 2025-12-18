@extends('layouts.public')

@section('title', 'Cari Kost - LIVORA')

@section('content')
<!-- Page Header -->
<section style="background: linear-gradient(135deg, #ff6900 0%, #ff8533 50%, #ffb366 100%); color: white; padding: 3rem 0;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Cari Kost & Boarding House</h1>
        <p class="text-orange-100 text-lg">Temukan hunian yang sempurna untuk Anda</p>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="bg-white shadow-sm sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form action="{{ route('browse') }}" method="GET" class="space-y-4">
            <!-- Search Bar -->
            <div class="relative">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama kost, lokasi, atau alamat..." 
                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>
            
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- City Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                    <select name="city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Semua Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Price Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Maksimal</label>
                    <select name="max_price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Semua Harga</option>
                        <option value="500000" {{ request('max_price') == '500000' ? 'selected' : '' }}>≤ Rp 500.000</option>
                        <option value="1000000" {{ request('max_price') == '1000000' ? 'selected' : '' }}>≤ Rp 1.000.000</option>
                        <option value="1500000" {{ request('max_price') == '1500000' ? 'selected' : '' }}>≤ Rp 1.500.000</option>
                        <option value="2000000" {{ request('max_price') == '2000000' ? 'selected' : '' }}>≤ Rp 2.000.000</option>
                        <option value="3000000" {{ request('max_price') == '3000000' ? 'selected' : '' }}>≤ Rp 3.000.000</option>
                    </select>
                </div>
                
                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                    </select>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors duration-200">
                    Terapkan Filter
                </button>
                @if(request()->anyFilled(['search', 'city', 'type', 'max_price', 'sort']))
                    <a href="{{ route('browse') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</section>

<!-- Results Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Info -->
        <div class="mb-6 flex items-center justify-between">
            <div class="text-gray-600">
                <span class="font-medium text-gray-900">{{ $properties->total() }}</span> kost ditemukan
                @if(request('search'))
                    untuk "<span class="font-medium text-gray-900">{{ request('search') }}</span>"
                @endif
            </div>
        </div>
        
        @if($properties->count() > 0)
            <!-- Properties Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($properties as $property)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <!-- Property Image -->
                        <div class="relative h-56 bg-gray-200 overflow-hidden group">
                            @if($property->images && count($property->images) > 0)
                                <img src="{{ asset('storage/' . $property->images[0]) }}" 
                                     alt="{{ $property->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="flex items-center justify-center h-full bg-gradient-to-br from-orange-600 to-orange-500">
                                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Availability Badge -->
                            @if($property->available_rooms_count > 0)
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500 text-white">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Tersedia
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Property Info -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">
                                {{ $property->name }}
                            </h3>
                            
                            <div class="flex items-start text-gray-600 mb-3">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm line-clamp-1">{{ $property->city }}</span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 h-10">
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
                                    <div class="text-xs text-gray-500">Mulai dari</div>
                                    <div class="text-xl font-bold text-orange-600">
                                        Rp {{ number_format($property->rooms->min('price'), 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">per bulan</div>
                                </div>
                                <a href="{{ route('properties.show', $property->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    Detail
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $properties->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-white rounded-xl shadow-sm">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Tidak Ada Hasil Ditemukan</h3>
                <p class="text-gray-600 mb-6">Coba ubah kata kunci pencarian atau filter yang Anda gunakan</p>
                <a href="{{ route('browse') }}" 
                   class="inline-flex items-center px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors duration-200">
                    Lihat Semua Kost
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
