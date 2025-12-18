@extends('layouts.tenant')

@section('title', 'Detail Pembayaran - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Detail Pembayaran #{{ $payment->id }}
                    </h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap pembayaran Anda</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('tenant.payments.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    @if($payment->status === 'pending')
                        <a href="{{ route('tenant.payments.edit', $payment) }}" 
                           class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-white hover:bg-yellow-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Payment Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-livora-primary to-blue-600">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Informasi Pembayaran
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">ID Pembayaran</label>
                                <div class="text-xl font-bold text-gray-900">#{{ $payment->id }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status Pembayaran</label>
                                <div>
                                    @if($payment->status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($payment->status === 'verified')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Pembayaran</label>
                                <div class="text-2xl font-bold text-livora-primary">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Submit</label>
                                <div class="text-gray-900">{{ $payment->created_at->format('d F Y, H:i') }}</div>
                            </div>
                            @if($payment->verified_at)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Verifikasi</label>
                                <div class="text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $payment->verified_at->format('d F Y, H:i') }}
                                </div>
                            </div>
                            @endif
                            @if($payment->notes)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Catatan</label>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-800">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-2 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <span>{{ $payment->notes }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Booking Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-500 to-indigo-600">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Informasi Booking
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">ID Booking</label>
                                <div class="text-lg font-semibold text-gray-900">#{{ $payment->booking->id }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status Booking</label>
                                <div>
                                    @if($payment->booking->status === 'confirmed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            Confirmed
                                        </span>
                                    @elseif($payment->booking->status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                            {{ ucfirst($payment->booking->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Kamar</label>
                                <div class="text-gray-900 font-medium">{{ $payment->booking->room->name ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Kost</label>
                                <div class="text-gray-900 font-medium">{{ $payment->booking->room->boardingHouse->name ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Check-in</label>
                                <div class="text-gray-900">{{ $payment->booking->check_in_date ? \Carbon\Carbon::parse($payment->booking->check_in_date)->format('d F Y') : 'Belum ditentukan' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Check-out</label>
                                <div class="text-gray-900">{{ $payment->booking->check_out_date ? \Carbon\Carbon::parse($payment->booking->check_out_date)->format('d F Y') : 'Belum ditentukan' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Total Harga Booking</label>
                                <div class="text-2xl font-bold text-blue-600">
                                    Rp {{ number_format($payment->booking->final_amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        @if($payment->amount < $payment->booking->final_amount)
                        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <strong>Catatan:</strong> Pembayaran Anda (Rp {{ number_format($payment->amount, 0, ',', '.') }}) 
                                    kurang dari total harga booking (Rp {{ number_format($payment->booking->final_amount, 0, ',', '.') }}). 
                                    Sisa yang harus dibayar: <strong>Rp {{ number_format($payment->booking->final_amount - $payment->amount, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                        @elseif($payment->amount > $payment->booking->final_amount)
                        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <strong>Catatan:</strong> Pembayaran Anda (Rp {{ number_format($payment->amount, 0, ',', '.') }}) 
                                    lebih dari total harga booking (Rp {{ number_format($payment->booking->final_amount, 0, ',', '.') }}). 
                                    Kelebihan: <strong>Rp {{ number_format($payment->amount - $payment->booking->final_amount, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-green-800">
                                    <strong>Perfect!</strong> Jumlah pembayaran sesuai dengan total harga booking.
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Payment Proof Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Bukti Pembayaran
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($payment->proof_image)
                            <div class="relative group cursor-pointer" onclick="showImageModal('{{ Storage::url($payment->proof_image) }}')">
                                <img src="{{ Storage::url($payment->proof_image) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="w-full rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 rounded-lg flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-lg">
                                <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500 mt-2">Bukti pembayaran tidak tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Aksi Cepat
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($payment->status === 'verified')
                            <button type="button" data-payment-id="{{ $payment->id }}" onclick="downloadReceipt(this.dataset.paymentId)" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-500 border border-transparent rounded-lg font-medium text-white hover:bg-green-600 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                Download Kwitansi
                            </button>
                        @endif
                        
                        @if($payment->status === 'pending')
                            <a href="{{ route('tenant.payments.edit', $payment) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-yellow-500 border border-transparent rounded-lg font-medium text-white hover:bg-yellow-600 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Pembayaran
                            </a>
                            <button type="button" data-payment-id="{{ $payment->id }}" onclick="deletePayment(this.dataset.paymentId)" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-500 border border-transparent rounded-lg font-medium text-white hover:bg-red-600 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus Pembayaran
                            </button>
                        @endif
                        
                        <a href="{{ route('tenant.payments.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Lihat Semua Pembayaran
                        </a>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Timeline Pembayaran
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Pembayaran Dibuat -->
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 pt-1">
                                    <h4 class="text-sm font-semibold text-gray-900">Pembayaran Dibuat</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Anda mengirim bukti pembayaran sebesar Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            @if($payment->status === 'verified')
                            <!-- Pembayaran Diverifikasi -->
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 pt-1">
                                    <h4 class="text-sm font-semibold text-gray-900">Pembayaran Diverifikasi</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $payment->verified_at ? $payment->verified_at->format('d M Y, H:i') : '-' }}</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Pembayaran Anda telah diverifikasi dan disetujui oleh pemilik kost.
                                    </p>
                                </div>
                            </div>
                            @elseif($payment->status === 'rejected')
                            <!-- Pembayaran Ditolak -->
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 pt-1">
                                    <h4 class="text-sm font-semibold text-gray-900">Pembayaran Ditolak</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $payment->updated_at->format('d M Y, H:i') }}</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $payment->notes ?? 'Tidak ada alasan yang diberikan.' }}
                                    </p>
                                </div>
                            </div>
                            @else
                            <!-- Menunggu Verifikasi -->
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center animate-pulse">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 pt-1">
                                    <h4 class="text-sm font-semibold text-gray-900">Menunggu Verifikasi</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Pembayaran Anda sedang menunggu untuk diverifikasi oleh pemilik kost.
                                    </p>
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

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl w-full">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="Bukti Pembayaran" class="w-full rounded-lg shadow-2xl">
    </div>
</div>

@push('scripts')
<script>
function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}

function deletePayment(paymentId) {
    if (confirm('Yakin ingin menghapus pembayaran ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tenant/payments/${paymentId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function downloadReceipt(paymentId) {
    window.open(`/tenant/payments/${paymentId}/download-receipt`, '_blank');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endpush
@endsection