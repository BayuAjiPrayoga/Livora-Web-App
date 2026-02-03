@extends('layouts.mitra')

@section('title', $room->name . ' - ' . $property->name . ' - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('mitra.properties.index') }}" class="hover:text-[#ff6900]">Properti Saya</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('mitra.properties.show', $property->id) }}" class="hover:text-[#ff6900]">{{ $property->name }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('mitra.rooms.index', $property->id) }}" class="hover:text-[#ff6900]">Kelola Kamar</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#ff6900] font-medium">{{ $room->name }}</span>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-livora-text">{{ $room->name }}</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $room->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $room->is_available ? 'Tersedia' : 'Terisi' }}
                        </span>
                        <span class="mx-2 text-gray-400">•</span>
                        <span class="font-semibold text-[#ff6900]">Rp {{ number_format($room->price, 0, ',', '.') }}/bulan</span>
                    </p>
                </div>
                <div class="flex space-x-3">
                    <form action="{{ route('mitra.rooms.toggle-availability', ['property' => $property->id, 'room' => $room->id]) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 {{ $room->is_available ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($room->is_available)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                @endif
                            </svg>
                            {{ $room->is_available ? 'Tandai Terisi' : 'Tandai Tersedia' }}
                        </button>
                    </form>
                    <a href="{{ route('mitra.rooms.edit', ['property' => $property->id, 'room' => $room->id]) }}" 
                       class="btn btn-outline">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Kamar
                    </a>
                    <form action="{{ route('mitra.rooms.destroy', ['property' => $property->id, 'room' => $room->id]) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 transition-colors">
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
                <!-- Room Images -->
                @if($room->images && count($room->images) > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-livora-text mb-4">Galeri Foto Kamar</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($room->images as $image)
                            <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden">
                                <img src="{{ Storage::url($image) }}" alt="{{ $room->name }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform cursor-pointer">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Room Description -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Deskripsi Kamar</h3>
                    @if($room->description)
                        <div class="text-gray-700 whitespace-pre-wrap">{{ $room->description }}</div>
                    @else
                        <p class="text-gray-500 italic">Belum ada deskripsi untuk kamar ini.</p>
                    @endif
                </div>

                <!-- Room Facilities -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Fasilitas Kamar</h3>
                    @if($room->facilities && $room->facilities->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($room->facilities as $facility)
                            <div class="flex items-center space-x-2 text-sm">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">{{ $facility->name }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">Belum ada fasilitas untuk kamar ini.</p>
                    @endif
                </div>

                <!-- Booking History -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-livora-text">Riwayat Booking ({{ $room->bookings->count() }})</h3>
                    </div>
                    
                    <div class="p-6">
                        @if($room->bookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($room->bookings->take(5) as $booking)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-medium text-livora-text">{{ $booking->user->name ?? 'N/A' }}</h4>
                                        <p class="text-sm text-gray-600">{{ $booking->user->email ?? 'N/A' }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                                        @elseif($booking->status === 'checked_out') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <p>Check-in: {{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') : 'N/A' }}</p>
                                    <p>Check-out: {{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') : 'N/A' }}</p>
                                    <p>Total: Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada booking</h3>
                            <p class="mt-1 text-sm text-gray-500">Kamar ini belum pernah dibooking.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Room Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Informasi Kamar</h3>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Harga per Bulan</dt>
                            <dd class="mt-1 text-lg font-semibold text-[#ff6900]">Rp {{ number_format($room->price, 0, ',', '.') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $room->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $room->is_available ? 'Tersedia' : 'Terisi' }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kapasitas</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $room->capacity }} orang</dd>
                        </div>
                        
                        @if($room->size)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ukuran</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $room->size }}m²</dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Booking</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $room->bookings->count() }} booking</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $room->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        
                        @if($room->updated_at != $room->created_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $room->updated_at->format('d M Y, H:i') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Room Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Statistik</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Booking Pending</span>
                            <span class="text-sm font-medium text-yellow-600">
                                {{ $room->bookings->where('status', 'pending')->count() }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Booking Confirmed</span>
                            <span class="text-sm font-medium text-green-600">
                                {{ $room->bookings->where('status', 'confirmed')->count() }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Sedang Check-in</span>
                            <span class="text-sm font-medium text-blue-600">
                                {{ $room->bookings->where('status', 'checked_in')->count() }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Pendapatan</span>
                            <span class="text-sm font-medium text-orange-600">
                                Rp {{ number_format($room->bookings->where('status', '!=', 'cancelled')->sum('total_amount'), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Aksi Cepat</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('mitra.rooms.edit', ['property' => $property->id, 'room' => $room->id]) }}" 
                           class="w-full btn btn-outline text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Kamar
                        </a>
                        
                        <button onclick="window.print()" 
                                class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Cetak Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection