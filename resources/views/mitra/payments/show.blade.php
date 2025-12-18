@extends('layouts.mitra')

@section('title', 'Detail Pembayaran - LIVORA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button & Header -->
    <div class="mb-6">
        <a href="{{ route('mitra.payments.index') }}" class="inline-flex items-center text-gray-600 hover:text-orange-600 transition mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Pembayaran
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pembayaran #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap transaksi pembayaran</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($payment->status === 'verified')
                    <button type="button" data-payment-id="{{ $payment->id }}" class="download-receipt inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Kwitansi
                    </button>
                @endif
                @if($payment->status === 'pending')
                    <button type="button" data-payment-id="{{ $payment->id }}" class="verify-payment inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Verifikasi
                    </button>
                    <button type="button" data-payment-id="{{ $payment->id }}" class="reject-payment inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tolak
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content - Left Side (2 columns) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                    <h2 class="text-white font-semibold text-lg">Status Pembayaran</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            @if($payment->status === 'pending')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Menunggu Verifikasi
                                </span>
                            @elseif($payment->status === 'verified')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Terverifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ditolak
                                </span>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                            <p class="text-3xl font-bold text-orange-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Informasi Pembayaran
                </h3>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-500">Tanggal Submit</p>
                        <p class="text-base text-gray-900">{{ $payment->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    @if($payment->verified_at)
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-500">Tanggal Verifikasi</p>
                        <p class="text-base text-gray-900">{{ $payment->verified_at->format('d F Y, H:i') }}</p>
                    </div>
                    @endif
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-500">ID Transaksi</p>
                        <p class="text-base text-gray-900">#{{ str_pad($payment->id, 8, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                @if($payment->notes)
                <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-yellow-800">Catatan</p>
                            <p class="text-sm text-yellow-700 mt-1">{{ $payment->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Booking Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Informasi Booking
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">ID Booking</span>
                        <span class="text-base font-semibold text-gray-900">#{{ str_pad($payment->booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Status Booking</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                            {{ $payment->booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($payment->booking->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Nama Tenant</span>
                        <span class="text-base font-semibold text-gray-900">{{ $payment->booking->user->name ?? $payment->booking->tenant_name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Nomor Telepon</span>
                        <span class="text-base text-gray-900">{{ $payment->booking->tenant_phone }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Nama Kamar</span>
                        <span class="text-base font-semibold text-gray-900">{{ $payment->booking->room->name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Kost</span>
                        <span class="text-base text-gray-900">{{ $payment->booking->room->boardingHouse->name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Check In</span>
                        <span class="text-base text-gray-900">{{ \Carbon\Carbon::parse($payment->booking->start_date)->format('d F Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Check Out</span>
                        <span class="text-base text-gray-900">{{ \Carbon\Carbon::parse($payment->booking->check_out_date)->format('d F Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm font-medium text-gray-500">Total Harga Booking</span>
                        <span class="text-lg font-bold text-orange-600">Rp {{ number_format($payment->booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('mitra.bookings.show', $payment->booking) }}" 
                       class="inline-flex items-center justify-center w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Detail Booking Lengkap
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-6">
            <!-- Proof Image Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                    <h3 class="text-white font-semibold text-lg">Bukti Pembayaran</h3>
                </div>
                <div class="p-6">
                    @if($payment->proof_image)
                        <div class="mb-4">
                            <div class="relative group cursor-pointer" onclick="showImageModal('{{ Storage::url($payment->proof_image) }}')">
                                <img src="{{ Storage::url($payment->proof_image) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="w-full h-64 object-cover rounded-xl shadow-md transition group-hover:shadow-xl">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center rounded-xl">
                                    <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('mitra.payments.download-proof', $payment) }}" 
                           target="_blank"
                           class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl hover:from-orange-600 hover:to-orange-700 transition font-medium shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Lihat Bukti Lengkap
                        </a>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Bukti pembayaran tidak tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Timeline
                </h3>
                <div class="space-y-4">
                    <!-- Created -->
                    <div class="flex">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div class="w-0.5 h-full bg-gray-200"></div>
                        </div>
                        <div class="pb-4">
                            <p class="font-semibold text-gray-900">Pembayaran Dibuat</p>
                            <p class="text-sm text-gray-500">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-sm text-gray-600 mt-1">Tenant mengirim bukti pembayaran</p>
                        </div>
                    </div>

                    @if($payment->status === 'verified')
                    <!-- Verified -->
                    <div class="flex">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Diverifikasi</p>
                            <p class="text-sm text-gray-500">{{ $payment->verified_at->format('d M Y, H:i') }}</p>
                            <p class="text-sm text-gray-600 mt-1">Pembayaran telah disetujui</p>
                        </div>
                    </div>
                    @elseif($payment->status === 'rejected')
                    <!-- Rejected -->
                    <div class="flex">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Ditolak</p>
                            <p class="text-sm text-gray-500">{{ $payment->updated_at->format('d M Y, H:i') }}</p>
                            @if($payment->notes)
                            <p class="text-sm text-red-600 mt-1">{{ $payment->notes }}</p>
                            @endif
                        </div>
                    </div>
                    @else
                    <!-- Pending -->
                    <div class="flex">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Menunggu Verifikasi</p>
                            <p class="text-sm text-gray-600 mt-1">Harap verifikasi pembayaran ini</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            @if($payment->status === 'pending')
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 border border-orange-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <button type="button" data-payment-id="{{ $payment->id }}" class="verify-payment w-full px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-medium shadow-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Verifikasi Pembayaran
                    </button>
                    <button type="button" data-payment-id="{{ $payment->id }}" class="reject-payment w-full px-4 py-3 bg-white text-red-600 border-2 border-red-600 rounded-xl hover:bg-red-50 transition font-medium flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tolak Pembayaran
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl w-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="Bukti Pembayaran" class="w-full h-auto rounded-xl shadow-2xl">
    </div>
</div>

<script>
// Functions need to be global for onclick handlers
function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });

    // Close modal on background click
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    }

    // Event listeners for verify payment buttons
    document.querySelectorAll('.verify-payment').forEach(button => {
        button.addEventListener('click', function() {
            const paymentId = this.getAttribute('data-payment-id');
            if (confirm('Yakin ingin memverifikasi pembayaran ini? Booking akan otomatis dikonfirmasi dan kamar menjadi tidak tersedia.')) {
                // Disable button
                this.disabled = true;
                this.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
                
                // Use fetch API with proper redirect handling
                fetch(`/mitra/payments/${paymentId}/verify`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message and redirect
                        alert(data.message);
                        window.location.href = '/mitra/payments';
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memverifikasi pembayaran');
                    location.reload();
                });
            }
        });
    });

    // Event listeners for reject payment buttons
    document.querySelectorAll('.reject-payment').forEach(button => {
        button.addEventListener('click', function() {
            const paymentId = this.getAttribute('data-payment-id');
            const reason = prompt('Masukkan alasan penolakan pembayaran:');
            if (reason !== null && reason.trim() !== '') {
                // Disable button
                this.disabled = true;
                this.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
                
                // Use fetch API with proper redirect handling
                fetch(`/mitra/payments/${paymentId}/reject`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ notes: reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message and redirect
                        alert(data.message);
                        window.location.href = '/mitra/payments';
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menolak pembayaran');
                    location.reload();
                });
            }
        });
    });

    // Event listeners for download receipt buttons
    document.querySelectorAll('.download-receipt').forEach(button => {
        button.addEventListener('click', function() {
            const paymentId = this.getAttribute('data-payment-id');
            window.open(`/mitra/payments/${paymentId}/download-receipt`, '_blank');
        });
    });
});
</script>
@endsection