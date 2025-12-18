@extends('layouts.mitra')

@section('title', $property->name . ' - LIVORA')

@section('content')
<div class="bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('mitra.properties.index') }}" class="hover:text-livora-primary">Properti Saya</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-livora-primary font-medium">{{ $property->name }}</span>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-livora-text">{{ $property->name }}</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $property->city }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('mitra.properties.edit', $property->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-livora-primary text-livora-primary rounded-md hover:bg-livora-primary hover:text-white transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Properti
                    </a>
                    <form action="{{ route('mitra.properties.destroy', $property->id) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus properti ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-6">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Property Images -->
                @if($property->images && count($property->images) > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-livora-text mb-4">Galeri Foto</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($property->images as $image)
                            <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden">
                                <img src="{{ Storage::url($image) }}" alt="{{ $property->name }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform cursor-pointer">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Property Description -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Deskripsi Properti</h3>
                    @if($property->description)
                        <div class="text-gray-700 whitespace-pre-wrap">{{ $property->description }}</div>
                    @else
                        <p class="text-gray-500 italic">Belum ada deskripsi untuk properti ini.</p>
                    @endif
                </div>

                <!-- Rooms Section -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-livora-text">Daftar Kamar ({{ $property->rooms->count() }})</h3>
                        <div class="flex space-x-2">
                            @if($property->rooms->count() > 0)
                            <a href="{{ route('mitra.rooms.index', $property->id) }}" class="inline-flex items-center px-3 py-2 border border-livora-primary text-livora-primary text-sm rounded hover:bg-livora-primary hover:text-white transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Kelola Semua
                            </a>
                            @endif
                            <a href="{{ route('mitra.rooms.create', $property->id) }}" class="inline-flex items-center px-3 py-2 bg-livora-accent text-white text-sm rounded hover:bg-livora-primary transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Kamar
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        @if($property->rooms->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($property->rooms as $room)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-livora-accent transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-livora-text">{{ $room->name }}</h4>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $room->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $room->is_available ? 'Tersedia' : 'Terisi' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Rp {{ number_format($room->price, 0, ',', '.') }}/bulan</p>
                                @if($room->description)
                                <p class="text-xs text-gray-500 mb-2">{{ Str::limit($room->description, 80) }}</p>
                                @endif
                                <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                                    <span>{{ $room->capacity }} orang</span>
                                    @if($room->size)
                                    <span>{{ $room->size }}m²</span>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('mitra.rooms.show', ['property' => $property->id, 'room' => $room->id]) }}" 
                                       class="flex-1 text-center px-2 py-1 text-xs border border-livora-accent text-livora-accent rounded hover:bg-livora-accent hover:text-white transition-colors">
                                        Detail
                                    </a>
                                    <a href="{{ route('mitra.rooms.edit', ['property' => $property->id, 'room' => $room->id]) }}" 
                                       class="flex-1 text-center px-2 py-1 text-xs bg-livora-primary text-white rounded hover:bg-blue-800 transition-colors">
                                        Edit
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada kamar</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai tambahkan kamar untuk properti ini.</p>
                            <div class="mt-4">
                                <a href="{{ route('mitra.rooms.create', $property->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-livora-accent text-white text-sm rounded hover:bg-livora-primary transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Kamar Pertama
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Property Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Informasi Properti</h3>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $property->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $property->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Slug URL</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $property->slug }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kota</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->city }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Kamar</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->rooms->count() }} kamar</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        
                        @if($property->updated_at != $property->created_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->updated_at->format('d M Y, H:i') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Location Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Lokasi</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Alamat Lengkap</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->address }}</dd>
                        </div>
                        
                        @if($property->latitude && $property->longitude)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Koordinat</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">
                                {{ $property->latitude }}, {{ $property->longitude }}
                            </dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Statistik Cepat</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Kamar Tersedia</span>
                            <span class="text-sm font-medium text-green-600">
                                {{ $property->rooms->where('is_available', true)->count() }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Kamar Terisi</span>
                            <span class="text-sm font-medium text-red-600">
                                {{ $property->rooms->where('is_available', false)->count() }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Booking</span>
                            <span class="text-sm font-medium text-livora-accent">
                                {{ $property->rooms->sum('bookings_count') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection