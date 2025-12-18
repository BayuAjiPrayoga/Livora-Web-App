@extends('layouts.mitra')

@section('title', 'Daftar Properti - LIVORA')

@section('content')
<div class="bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-livora-text">Properti Saya</h1>
                <p class="text-gray-600 mt-1">Kelola semua properti kost Anda</p>
            </div>
            <a href="{{ route('mitra.properties.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Properti
            </a>
        </div>
    </div>

    <div class="p-6">
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if($properties->count() > 0)
        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($properties as $property)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <!-- Property Image -->
                <div class="h-48 bg-gray-200 relative">
                    @if($property->images && count($property->images) > 0)
                        <img src="{{ Storage::url($property->images[0]) }}" alt="{{ $property->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $property->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $property->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <!-- Property Info -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-2">{{ $property->name }}</h3>
                    <p class="text-sm text-gray-600 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $property->city }}
                    </p>
                    <p class="text-sm text-gray-500 mb-4">{{ Str::limit($property->address, 60) }}</p>
                    
                    <!-- Room Count -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium text-orange-600">{{ $property->rooms_count }}</span> kamar
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $property->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('mitra.properties.show', $property->id) }}" class="flex-1 text-center btn btn-primary text-sm">
                            Detail
                        </a>
                        <a href="{{ route('mitra.properties.edit', $property->id) }}" class="flex-1 text-center btn btn-outline text-sm">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $properties->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto h-24 w-24 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-full w-full">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-medium text-livora-text">Belum ada properti</h3>
            <p class="mt-2 text-gray-500">Mulai tambahkan properti kost pertama Anda untuk mengelola bisnis boarding house.</p>
            <div class="mt-6">
                <a href="{{ route('mitra.properties.create') }}" class="btn btn-primary btn-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Properti Pertama
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection