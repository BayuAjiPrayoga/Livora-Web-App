@extends('layouts.public')

@section('title', $property->name . ' - LIVORA')

@section('content')
<!-- Property Images Gallery -->
<section class="bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($property->images && count($property->images) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 rounded-xl overflow-hidden">
                <!-- Main Image -->
                <div class="md:row-span-2 relative h-96 md:h-full bg-gray-800">
                    <img src="{{ asset('storage/' . $property->images[0]) }}" 
                         alt="{{ $property->name }}"
                         class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity image-gallery"
                         data-image="{{ asset('storage/' . $property->images[0]) }}">
                </div>
                
                <!-- Additional Images -->
                <div class="grid grid-cols-2 gap-2">
                    @foreach(array_slice($property->images, 1, 4) as $index => $image)
                        <div class="relative h-48 bg-gray-800 {{ $index >= 2 ? 'hidden md:block' : '' }}">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $property->name }}"
                                 class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity image-gallery"
                                 data-image="{{ asset('storage/' . $image) }}">
                            
                            @if($index == 3 && count($property->images) > 5)
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center cursor-pointer image-gallery"
                                     data-image="{{ asset('storage/' . $image) }}">
                                    <span class="text-white text-2xl font-bold">+{{ count($property->images) - 5 }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="h-96 bg-gradient-to-br from-livora-primary to-blue-700 rounded-xl flex items-center justify-center">
                <svg class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        @endif
    </div>
</section>

<!-- Property Info -->
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header -->
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        @if($property->rooms->where('is_available', true)->count() > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $property->rooms->where('is_available', true)->count() }} Kamar Tersedia
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">{{ $property->name }}</h1>
                    
                    <div class="flex items-start text-gray-600 mb-2">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $property->address }}, {{ $property->city }}</span>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Deskripsi</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $property->description }}</p>
                </div>
                
                <!-- Available Rooms -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Kamar Tersedia</h2>
                    
                    @php
                        $availableRooms = $property->rooms->where('is_available', true);
                    @endphp
                    
                    @if($availableRooms->count() > 0)
                        <div class="space-y-4">
                            @foreach($availableRooms as $room)
                                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-lg font-bold text-gray-900 cursor-pointer hover:text-[#ff6900] transition-colors room-name" 
                                                    data-room-id="{{ $room->id }}">
                                                    {{ $room->name }}
                                                </h3>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Tersedia
                                                </span>
                                            </div>
                                            
                                            @if($room->description)
                                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($room->description, 100) }}</p>
                                            @endif
                                            
                                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                                                @if($room->size)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                                        </svg>
                                                        {{ $room->size }} m²
                                                    </div>
                                                @endif
                                                
                                                @if($room->capacity)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                        {{ $room->capacity }} orang
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Facilities Preview -->
                                            @if($room->facilities && $room->facilities->count() > 0)
                                                <div class="mb-3">
                                                    <p class="text-xs font-medium text-gray-700 mb-2">Fasilitas:</p>
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($room->facilities->take(5) as $facility)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-green-50 text-green-700 text-xs">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                {{ $facility->name }}
                                                            </span>
                                                        @endforeach
                                                        @if($room->facilities->count() > 5)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-gray-600 text-xs">
                                                                +{{ $room->facilities->count() - 5 }} more
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- View Details Button -->
                                            <button type="button" 
                                                    class="text-[#ff6900] hover:text-blue-700 text-sm font-medium flex items-center view-details-btn"
                                                    data-room-id="{{ $room->id }}">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat Detail Kamar
                                            </button>

                                            <!-- Hidden Detail Section -->
                                            <div class="room-details mt-4 hidden" id="room-details-{{ $room->id }}">
                                                <div class="border-t pt-4 space-y-3">
                                                    @if($room->description)
                                                        <div>
                                                            <h4 class="font-semibold text-gray-900 mb-2">Deskripsi Lengkap:</h4>
                                                            <p class="text-gray-600 text-sm">{{ $room->description }}</p>
                                                        </div>
                                                    @endif

                                                    @if($room->facilities && $room->facilities->count() > 0)
                                                        <div>
                                                            <h4 class="font-semibold text-gray-900 mb-2">Semua Fasilitas:</h4>
                                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                                                @foreach($room->facilities as $facility)
                                                                    <div class="flex items-center text-sm text-gray-700">
                                                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                        </svg>
                                                                        {{ $facility->name }}
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($room->images && count($room->images) > 0)
                                                        <div>
                                                            <h4 class="font-semibold text-gray-900 mb-2">Foto Kamar:</h4>
                                                            <div class="grid grid-cols-3 gap-2">
                                                                @foreach($room->images as $image)
                                                                    <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden">
                                                                        <img src="{{ Storage::url($image) }}" 
                                                                             alt="{{ $room->name }}" 
                                                                             class="w-full h-full object-cover hover:scale-105 transition-transform cursor-pointer">
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col items-end justify-center gap-2">
                                            <div class="text-right">
                                                <div class="text-2xl font-bold text-[#ff6900]">
                                                    Rp {{ number_format($room->price, 0, ',', '.') }}
                                                </div>
                                                <div class="text-sm text-gray-500">per bulan</div>
                                            </div>
                                            
                                            @auth
                                                @if(auth()->user()->role === 'tenant')
                                                    <a href="{{ route('tenant.bookings.create', ['room' => $room->id]) }}" 
                                                       class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#ff6900] to-[#ff8533] hover:opacity-90 text-gray-900 font-semibold rounded-lg transition-colors duration-200">
                                                        Book Sekarang
                                                    </a>
                                                @else
                                                    <span class="text-sm text-gray-500">Hanya untuk tenant</span>
                                                @endif
                                            @else
                                                <a href="{{ route('register') }}" 
                                                   class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#ff6900] to-[#ff8533] hover:opacity-90 text-gray-900 font-semibold rounded-lg transition-colors duration-200">
                                                    Daftar Untuk Book
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-xl">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Kamar Tersedia</h3>
                            <p class="text-gray-600 mb-4">Semua kamar saat ini sudah terisi penuh</p>
                            <a href="{{ route('browse') }}" 
                               class="inline-flex items-center text-[#ff6900] hover:text-blue-700 font-medium">
                                Cari Kost Lainnya
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
                
                <!-- Other Properties from Same Owner -->
                @if($otherProperties->count() > 0)
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Kost Lainnya dari Owner yang Sama</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($otherProperties as $otherProperty)
                                <a href="{{ route('properties.show', $otherProperty->id) }}" 
                                   class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                                    <div class="flex">
                                        <div class="w-32 h-32 bg-gray-200 flex-shrink-0">
                                            @if($otherProperty->images && count($otherProperty->images) > 0)
                                                <img src="{{ asset('storage/' . $otherProperty->images[0]) }}" 
                                                     alt="{{ $otherProperty->name }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center h-full bg-gradient-to-br from-livora-primary to-blue-700">
                                                    <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="p-4 flex-1 min-w-0">
                                            <h3 class="font-bold text-gray-900 mb-1 truncate">{{ $otherProperty->name }}</h3>
                                            <p class="text-sm text-gray-600 mb-2 truncate">{{ $otherProperty->city }}</p>
                                            <div class="flex items-center justify-between">
                                                <div class="text-sm">
                                                    <span class="font-bold text-[#ff6900]">
                                                        Rp {{ number_format($otherProperty->rooms->min('price'), 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-gray-500 text-xs">/bulan</span>
                                                </div>
                                                @if($otherProperty->rooms->where('is_available', true)->count() > 0)
                                                    <span class="text-xs text-green-600 font-medium">Tersedia</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Kontak</h3>
                    
                    <div class="space-y-4">
                        <!-- Owner Info -->
                        <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-[#ff6900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Pemilik</div>
                                <div class="font-semibold text-gray-900">{{ $property->user->name }}</div>
                            </div>
                        </div>
                        
                        <!-- Contact Buttons -->
                        <div class="space-y-2">
                            @if($property->user->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->user->phone) }}" 
                                   target="_blank"
                                   class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                    Hubungi via WhatsApp
                                </a>
                            @endif
                            
                            @if($property->user->email)
                                <a href="mailto:{{ $property->user->email }}" 
                                   class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-[#ff6900] hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Kirim Email
                                </a>
                            @endif
                        </div>
                        
                        <!-- Info Text -->
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 leading-relaxed">
                                <svg class="w-5 h-5 text-orange-600 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Hubungi pemilik untuk informasi lebih lanjut atau jadwalkan kunjungan
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
    <button class="absolute top-4 right-4 text-white hover:text-gray-300" onclick="closeImageModal()">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain" onclick="event.stopPropagation()">
</div>
@endsection

@push('scripts')
<script>
    // Event delegation for image gallery
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('image-gallery') || e.target.closest('.image-gallery')) {
            const element = e.target.classList.contains('image-gallery') ? e.target : e.target.closest('.image-gallery');
            const imageSrc = element.dataset.image;
            if (imageSrc) {
                openImageModal(imageSrc);
            }
        }
    });
    
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('imageModal').classList.remove('flex');
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageModal();
        }
    });

    // Toggle room details
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('view-details-btn') || e.target.closest('.view-details-btn')) {
            const btn = e.target.classList.contains('view-details-btn') ? e.target : e.target.closest('.view-details-btn');
            const roomId = btn.getAttribute('data-room-id');
            const detailsDiv = document.getElementById('room-details-' + roomId);
            
            if (detailsDiv) {
                if (detailsDiv.classList.contains('hidden')) {
                    detailsDiv.classList.remove('hidden');
                    btn.innerHTML = `
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                        Sembunyikan Detail
                    `;
                } else {
                    detailsDiv.classList.add('hidden');
                    btn.innerHTML = `
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Detail Kamar
                    `;
                }
            }
        }
    });

    // Click room name to toggle details
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('room-name')) {
            const roomId = e.target.getAttribute('data-room-id');
            const btn = document.querySelector('.view-details-btn[data-room-id="' + roomId + '"]');
            if (btn) {
                btn.click();
            }
        }
    });
</script>
@endpush
