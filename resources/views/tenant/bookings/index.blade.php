@extends('layouts.tenant')

@section('title', 'Booking Saya - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Booking Saya</h1>
                    <p class="text-gray-600 mt-1">Kelola semua booking dan reservasi Anda</p>
                </div>
                <a href="{{ route('tenant.bookings.create') }}" class="inline-flex items-center px-4 py-2 bg-[#ff6900] border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Booking Baru
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($bookings->count() > 0)
            <!-- Booking Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                @php
                    $totalBookings = $bookings->count();
                    $pendingBookings = $bookings->where('status', 'pending')->count();
                    $confirmedBookings = $bookings->where('status', 'confirmed')->count();
                    $activeBookings = $bookings->whereIn('status', ['checked_in', 'active'])->count();
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Booking</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Menunggu</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $pendingBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Dikonfirmasi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $confirmedBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-50 rounded-lg">
                            <svg class="w-6 h-6 text-[#ff6900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Aktif</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $activeBookings }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                        <select class="rounded-lg border-gray-300 focus:border-[#ff6900] focus:ring-livora-primary">
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu</option>
                            <option value="confirmed">Dikonfirmasi</option>
                            <option value="checked_in">Check-in</option>
                            <option value="checked_out">Check-out</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                        
                        <input type="date" class="rounded-lg border-gray-300 focus:border-[#ff6900] focus:ring-livora-primary">
                        
                        <input type="text" placeholder="Cari booking..." class="rounded-lg border-gray-300 focus:border-[#ff6900] focus:ring-livora-primary">
                    </div>

                    <div class="flex items-center space-x-2">
                        <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Booking List -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <!-- Booking Header -->
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">
                                        {{ $booking->room->boardingHouse->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Kamar {{ $booking->room->room_number }} - {{ $booking->room->room_type }}
                                    </p>
                                </div>
                                
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg-yellow-100', 'text-yellow-800', 'Menunggu'],
                                        'confirmed' => ['bg-green-100', 'text-green-800', 'Dikonfirmasi'],
                                        'checked_in' => ['bg-blue-100', 'text-blue-800', 'Check-in'],
                                        'checked_out' => ['bg-gray-100', 'text-gray-800', 'Check-out'],
                                        'cancelled' => ['bg-red-100', 'text-red-800', 'Dibatalkan'],
                                        'rejected' => ['bg-red-100', 'text-red-800', 'Ditolak'],
                                    ];
                                    $status = $statusConfig[$booking->status] ?? ['bg-gray-100', 'text-gray-800', ucfirst($booking->status)];
                                @endphp
                                
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status[0] }} {{ $status[1] }}">
                                    {{ $status[2] }}
                                </span>
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">Check-in:</p>
                                    <p class="font-medium text-gray-900">{{ $booking->check_in_date->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Check-out:</p>
                                    <p class="font-medium text-gray-900">{{ $booking->check_out_date->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">Durasi:</p>
                                    <p class="font-medium text-gray-900">{{ $booking->duration_months }} bulan</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Total Bayar:</p>
                                    <p class="font-bold text-[#ff6900]">Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <!-- Payment Status -->
                            @if($booking->payments->isNotEmpty())
                                <div class="pt-4 border-t border-gray-100">
                                    @php
                                        $latestPayment = $booking->payments->sortByDesc('created_at')->first();
                                        $paymentStatusConfig = [
                                            'pending' => ['bg-yellow-100', 'text-yellow-800', 'Menunggu Pembayaran'],
                                            'settlement' => ['bg-green-100', 'text-green-800', 'Berhasil'],
                                            'capture' => ['bg-green-100', 'text-green-800', 'Berhasil'],
                                            'expire' => ['bg-red-100', 'text-red-800', 'Kadaluarsa'],
                                            'cancel' => ['bg-red-100', 'text-red-800', 'Dibatalkan'],
                                            'deny' => ['bg-red-100', 'text-red-800', 'Ditolak'],
                                            'refund' => ['bg-orange-100', 'text-orange-800', 'Dikembalikan'],
                                        ];
                                        $paymentStatus = $paymentStatusConfig[$latestPayment->status] ?? ['bg-gray-100', 'text-gray-800', ucfirst($latestPayment->status)];
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Status Pembayaran:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $paymentStatus[0] }} {{ $paymentStatus[1] }}">
                                            {{ $paymentStatus[2] }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="pt-4 border-t border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Pembayaran:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Belum Dibayar
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="px-6 pb-6">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('tenant.bookings.show', $booking) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-livora-primary transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detail
                                </a>

                                @if($booking->status === 'confirmed' && $booking->payments->where('status', 'settlement')->isEmpty())
                                    <a href="{{ route('tenant.payments.midtrans.create', ['booking_id' => $booking->id]) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-[#ff6900] border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Bayar
                                    </a>
                                @endif

                                @if(in_array($booking->status, ['pending', 'confirmed']))
                                    <button data-booking-id="{{ $booking->id }}" onclick="cancelBooking(this.dataset.bookingId)"
                                            class="inline-flex items-center px-3 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <!-- Add pagination here if needed -->
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada booking</h3>
                <p class="text-gray-600 mb-8">Anda belum memiliki booking apapun. Mulai booking sekarang!</p>
                <a href="{{ route('tenant.bookings.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#ff6900] border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Booking Pertama
                </a>
            </div>
        @endif
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