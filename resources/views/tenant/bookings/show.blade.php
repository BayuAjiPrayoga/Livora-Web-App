@extends('layouts.tenant')

@section('title', 'Detail Booking - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('tenant.bookings.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Booking</h1>
                        <p class="text-gray-600 mt-1">ID: {{ $booking->id }} • Dibuat {{ $booking->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @php
                    $statusConfig = [
                        'pending' => ['bg-yellow-100', 'text-yellow-800', 'Menunggu Konfirmasi'],
                        'confirmed' => ['bg-green-100', 'text-green-800', 'Dikonfirmasi'],
                        'checked_in' => ['bg-blue-100', 'text-blue-800', 'Check-in'],
                        'checked_out' => ['bg-gray-100', 'text-gray-800', 'Check-out'],
                        'cancelled' => ['bg-red-100', 'text-red-800', 'Dibatalkan'],
                        'rejected' => ['bg-red-100', 'text-red-800', 'Ditolak'],
                    ];
                    $status = $statusConfig[$booking->status] ?? ['bg-gray-100', 'text-gray-800', ucfirst($booking->status)];
                @endphp
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $status[0] }} {{ $status[1] }}">
                    {{ $status[2] }}
                </span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Booking Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Booking</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Property Info -->
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $booking->room->boardingHouse->name }}</h3>
                                    <p class="text-gray-600">{{ $booking->room->boardingHouse->address }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Nama Kamar</p>
                                    <p class="font-semibold text-gray-900">{{ $booking->room->name }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Kapasitas</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->room->capacity }} orang</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Ukuran Kamar</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->room->size ? number_format($booking->room->size, 0) . ' m²' : '-' }}</p>
                                    </div>
                                </div>

                                @if($booking->room->description)
                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Deskripsi & Fasilitas</p>
                                    <p class="text-sm text-gray-700">{{ $booking->room->description }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Booking Details -->
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Check-in</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->check_in_date->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->check_in_date->format('l') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Check-out</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->check_out_date->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->check_out_date->format('l') }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Durasi</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->duration_months }} bulan</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Harga per bulan</p>
                                        <p class="font-semibold text-gray-900">Rp {{ number_format($booking->room->price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                @if($booking->notes)
                                    <div>
                                        <p class="text-sm text-gray-600">Catatan</p>
                                        <p class="text-sm text-gray-900">{{ $booking->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KTP Information -->
                @if($booking->tenant_identity_number || $booking->ktp_image)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Data KTP</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Nomor KTP</p>
                                <p class="font-semibold text-gray-900">{{ $booking->tenant_identity_number ?: '-' }}</p>
                            </div>
                            @if($booking->ktp_image)
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Foto KTP</p>
                                <a href="{{ Storage::url($booking->ktp_image) }}" target="_blank" class="inline-block">
                                    <img src="{{ Storage::url($booking->ktp_image) }}" alt="KTP" 
                                         class="max-h-40 rounded-lg border border-gray-300 hover:opacity-80 transition-opacity cursor-pointer">
                                </a>
                                <p class="text-xs text-gray-500 mt-1">Klik untuk melihat ukuran penuh</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Payment History -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h2>
                            @if($booking->status === 'confirmed' && $booking->payments->where('status', 'verified')->isEmpty())
                                <a href="{{ route('tenant.payments.create', ['booking_id' => $booking->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-livora-primary border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        @if($booking->payments->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($booking->payments->sortByDesc('created_at') as $payment)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3">
                                                    <h4 class="font-medium text-gray-900">
                                                        Pembayaran #{{ $payment->id }}
                                                    </h4>
                                                    @php
                                                        $paymentStatusConfig = [
                                                            'pending' => ['bg-yellow-100', 'text-yellow-800', 'Menunggu Verifikasi'],
                                                            'verified' => ['bg-green-100', 'text-green-800', 'Terverifikasi'],
                                                            'rejected' => ['bg-red-100', 'text-red-800', 'Ditolak'],
                                                        ];
                                                        $paymentStatus = $paymentStatusConfig[$payment->status] ?? ['bg-gray-100', 'text-gray-800', ucfirst($payment->status)];
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $paymentStatus[0] }} {{ $paymentStatus[1] }}">
                                                        {{ $paymentStatus[2] }}
                                                    </span>
                                                </div>
                                                
                                                <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <p class="text-gray-600">Jumlah</p>
                                                        <p class="font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-600">Metode</p>
                                                        <p class="font-medium text-gray-900">{{ $payment->payment_method }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-600">Tanggal</p>
                                                        <p class="font-medium text-gray-900">{{ $payment->created_at->format('d M Y') }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-600">Waktu</p>
                                                        <p class="font-medium text-gray-900">{{ $payment->created_at->format('H:i') }}</p>
                                                    </div>
                                                </div>

                                                @if($payment->notes)
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-600">Catatan: {{ $payment->notes }}</p>
                                                    </div>
                                                @endif

                                                @if($payment->status === 'rejected' && $payment->rejection_reason)
                                                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                        <p class="text-sm text-red-800">
                                                            <strong>Alasan Penolakan:</strong> {{ $payment->rejection_reason }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="ml-4">
                                                <a href="{{ route('tenant.payments.show', $payment) }}" 
                                                   class="text-livora-primary hover:text-blue-700 text-sm font-medium">
                                                    Lihat Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pembayaran</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai proses pembayaran untuk booking ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <!-- Price Summary -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Ringkasan Biaya</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Harga per bulan</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($booking->room->price ?? 0, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Durasi</span>
                                <span class="font-medium text-gray-900">{{ $booking->duration_months }} bulan</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-livora-primary">Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @php
                                $totalPaid = $booking->payments->where('status', 'verified')->sum('amount');
                                $remaining = $booking->final_amount - $totalPaid;
                            @endphp

                            @if($totalPaid > 0)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-green-800">Sudah Dibayar</span>
                                        <span class="font-medium text-green-900">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                                    </div>
                                    @if($remaining > 0)
                                        <div class="flex justify-between text-sm mt-1">
                                            <span class="text-green-800">Sisa</span>
                                            <span class="font-medium text-green-900">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Aksi</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            @if($booking->status === 'pending')
                                <a href="{{ route('tenant.bookings.edit', $booking) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-livora-primary transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    Edit Booking
                                </a>
                            @endif

                            @if($booking->status === 'confirmed' && $remaining > 0)
                                <a href="{{ route('tenant.payments.create', ['booking_id' => $booking->id]) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-livora-primary border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Bayar Sekarang
                                </a>
                            @endif

                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                <button data-booking-id="{{ $booking->id }}" onclick="cancelBooking(this.dataset.bookingId)"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Batalkan Booking
                                </button>
                            @endif                            <a href="{{ route('tenant.tickets.create', ['booking_id' => $booking->id]) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-livora-primary transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                                Buat Tiket
                            </a>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Kontak</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($booking->room->boardingHouse->phone)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">Telepon</p>
                                        <p class="font-medium text-gray-900">{{ $booking->room->boardingHouse->phone }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($booking->room->boardingHouse->email)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">Email</p>
                                        <p class="font-medium text-gray-900">{{ $booking->room->boardingHouse->email }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div id="cancelBookingModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="cancelBookingForm" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L5.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Batalkan Booking
                            </h3>
                            <div class="mt-4">
                                <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan pembatalan:</label>
                                <textarea name="cancellation_reason" id="cancellation_reason" rows="3" 
                                          class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" 
                                          placeholder="Berikan alasan pembatalan booking..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Batalkan Booking
                    </button>
                    <button type="button" onclick="closeCancelModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function cancelBooking(bookingId) {
    const modal = document.getElementById('cancelBookingModal');
    const form = document.getElementById('cancelBookingForm');
    
    form.action = `/tenant/bookings/${bookingId}/cancel`;
    modal.classList.remove('hidden');
}

function closeCancelModal() {
    const modal = document.getElementById('cancelBookingModal');
    modal.classList.add('hidden');
    
    // Reset form
    document.getElementById('cancellation_reason').value = '';
}

// Close modal when clicking outside
document.getElementById('cancelBookingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelModal();
    }
});
</script>
@endpush
@endsection