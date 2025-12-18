@extends('layouts.tenant')

@section('title', 'Detail Tiket - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('tenant.dashboard') }}" class="hover:text-[#ff6900]">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('tenant.tickets.index') }}" class="hover:text-[#ff6900]">Tiket Saya</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#ff6900] font-medium">Detail Tiket #{{ $ticket->id }}</span>
            </div>
            <h1 class="text-2xl font-bold text-livora-text">Detail Tiket #{{ $ticket->id }}</h1>
        </div>
    </div>

    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            <!-- Ticket Header Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $ticket->subject }}</h2>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $ticket->created_at->format('d M Y, H:i') }}
                                </span>
                                <span>Tiket #{{ $ticket->id }}</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <!-- Priority Badge -->
                            @php
                                $priorityClass = match($ticket->priority) {
                                    'urgent' => 'bg-red-100 text-red-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'low' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $priorityText = match($ticket->priority) {
                                    'urgent' => 'Urgent',
                                    'high' => 'Tinggi',
                                    'medium' => 'Sedang',
                                    'low' => 'Rendah',
                                    default => 'Normal'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $priorityClass }}">
                                {{ $priorityText }}
                            </span>

                            <!-- Status Badge -->
                            @php
                                $statusClass = match($ticket->status) {
                                    'open' => 'bg-red-100 text-red-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusText = match($ticket->status) {
                                    'open' => 'Belum Diproses',
                                    'in_progress' => 'Sedang Diproses',
                                    'resolved' => 'Selesai',
                                    default => 'Unknown'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Ticket Info -->
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($ticket->room && $ticket->room->boardingHouse)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Properti</p>
                            <p class="font-medium text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ $ticket->room->boardingHouse->name }}
                            </p>
                        </div>
                        @endif

                        @if($ticket->room)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kamar</p>
                            <p class="font-medium text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10.5 3L12 2l1.5 1H21v6H3V3h7.5z"></path>
                                </svg>
                                {{ $ticket->room->name }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Message -->
                <div class="px-6 py-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Pesan Keluhan/Permintaan:</h3>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->message }}</p>
                    </div>
                </div>

                <!-- Response -->
                @if($ticket->response)
                <div class="px-6 py-6 bg-blue-50 border-t border-blue-100">
                    <h3 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Respon dari Mitra:
                    </h3>
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->response }}</p>
                        @if($ticket->resolved_at)
                        <p class="text-sm text-gray-600 mt-3 pt-3 border-t">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Diselesaikan pada {{ $ticket->resolved_at->format('d M Y, H:i') }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-between items-center">
                    <a href="{{ route('tenant.tickets.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar Tiket
                    </a>

                    @if($ticket->status === 'open')
                    <div class="flex space-x-2">
                        <a href="{{ route('tenant.tickets.edit', $ticket) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Tiket
                        </a>
                        <form action="{{ route('tenant.tickets.destroy', $ticket) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus Tiket
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline/History (optional future enhancement) -->
            @if($ticket->status === 'resolved')
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center">
                    <svg class="w-12 h-12 text-green-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-green-900">Tiket Telah Diselesaikan</h3>
                        <p class="text-green-700">Terima kasih telah menggunakan layanan kami. Jika masih ada masalah, silakan buat tiket baru.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
