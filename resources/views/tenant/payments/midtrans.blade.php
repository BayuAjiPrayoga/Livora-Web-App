@extends('layouts.tenant')

@section('title', 'Pembayaran dengan Midtrans - LIVORA')

@section('head')
<!-- Midtrans Snap JS -->
<script type="text/javascript" src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<!-- Debug: Log Midtrans config -->
<script>
    console.log('=== MIDTRANS SCRIPT CONFIG ===');
    console.log('Script URL:', 'https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js');
    console.log('Client Key:', '{{ config('midtrans.client_key') }}');
    console.log('Is Production:', {{ config('midtrans.is_production') ? 'true' : 'false' }});
    
    // Wait for page load and check if script loaded
    window.addEventListener('load', function() {
        console.log('Page loaded, snap available?', typeof snap !== 'undefined');
        if (typeof snap === 'undefined') {
            console.error('❌ Midtrans Snap.js FAILED TO LOAD');
            console.log('Possible causes:');
            console.log('1. Browser extension blocking');
            console.log('2. Corporate firewall/proxy');
            console.log('3. Network connectivity issue');
            console.log('4. Invalid client key');
        } else {
            console.log('✅ Midtrans Snap.js loaded successfully');
        }
    });
</script>
@endsection

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Pembayaran Online - Midtrans
                    </h1>
                    <p class="text-gray-600 mt-1">Bayar dengan berbagai metode pembayaran yang aman</p>
                </div>
                <a href="{{ route('tenant.payments.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Pilih Booking untuk Pembayaran</h3>
                    </div>
                    <div class="p-6">
                        <!-- Alert Success -->
                        @if(session('success'))
                            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <!-- Alert Error -->
                        @if(session('error'))
                            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <div class="space-y-6">
                            @if($availableBookings->isEmpty())
                                <!-- No Bookings Available Alert -->
                                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-6 text-center">
                                    <svg class="w-12 h-12 mx-auto text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold mb-2">Tidak Ada Booking yang Tersedia</h3>
                                    <p class="text-sm mb-4">
                                        Anda belum memiliki booking aktif yang perlu dibayar, atau booking Anda sudah memiliki pembayaran yang sedang diproses.
                                    </p>
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('tenant.bookings.index') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Lihat Booking Saya
                                        </a>
                                        <a href="{{ route('browse') }}" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            Cari Kost
                                        </a>
                                    </div>
                                </div>
                            @else
                            <!-- Booking Selection -->
                            <div>
                                <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Booking yang Akan Dibayar <span class="text-red-500">*</span>
                                </label>
                                <select id="booking_id" 
                                        class="w-full rounded-lg border-gray-300 focus:border-[#ff6900] focus:ring-livora-primary" 
                                        required>
                                    <option value="">-- Pilih Booking --</option>
                                    @foreach($availableBookings as $booking)
                                        <option value="{{ $booking->id }}" 
                                                data-amount="{{ $booking->final_amount }}"
                                                data-property="{{ $booking->room->boardingHouse->name ?? 'N/A' }}"
                                                data-room="{{ $booking->room->name ?? 'N/A' }}">
                                            #{{ $booking->booking_code ?? $booking->id }} - {{ $booking->room->name ?? 'N/A' }} 
                                            ({{ $booking->room->boardingHouse->name ?? 'N/A' }}) 
                                            - Rp {{ number_format($booking->final_amount, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Pilih booking yang ingin Anda bayar.</p>
                            </div>
                            @endif

                            @if(!$availableBookings->isEmpty())
                            <!-- Payment Amount Display -->
                            <div id="payment-info" class="hidden bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-orange-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-2">Detail Pembayaran</h4>
                                        <div class="space-y-1 text-sm text-gray-700">
                                            <p><span class="font-medium">Properti:</span> <span id="property-name"></span></p>
                                            <p><span class="font-medium">Kamar:</span> <span id="room-name"></span></p>
                                            <p class="pt-2 border-t border-orange-200">
                                                <span class="font-medium">Total Pembayaran:</span> 
                                                <span id="total-amount" class="text-lg font-bold text-orange-600"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pay Button -->
                            <button type="button" 
                                    id="pay-button" 
                                    class="w-full bg-gradient-to-r from-orange-600 to-orange-500 text-white font-semibold py-3 px-6 rounded-lg hover:from-orange-700 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                                    disabled>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span id="button-text">Bayar Sekarang</span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-8">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Metode Pembayaran</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Payment Methods -->
                        <div class="space-y-3">
                            <div class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Credit/Debit Card
                            </div>
                            <div class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Bank Transfer
                            </div>
                            <div class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                E-Wallet (GoPay, OVO, DANA)
                            </div>
                            <div class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Virtual Account
                            </div>
                            <div class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Convenience Store
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-semibold mb-1">Pembayaran Aman</p>
                                        <p class="text-blue-700">Transaksi Anda dilindungi oleh Midtrans Payment Gateway dengan enkripsi SSL.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500 text-center">
                                Powered by <span class="font-semibold">Midtrans</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Check if Midtrans Snap is loaded
    if (typeof snap === 'undefined') {
        console.error('Midtrans Snap.js not loaded! Please disable ad-blocker or check internet connection.');
        alert('Gagal memuat Midtrans Payment Gateway.\n\nKemungkinan penyebab:\n1. Ad-blocker memblokir script Midtrans\n2. Koneksi internet bermasalah\n\nSolusi:\n- Disable ad-blocker (uBlock, AdBlock, dll)\n- Atau gunakan Incognito/Private mode\n- Refresh halaman');
    }
    
    const bookingSelect = document.getElementById('booking_id');
    const payButton = document.getElementById('pay-button');
    const buttonText = document.getElementById('button-text');
    const paymentInfo = document.getElementById('payment-info');
    const propertyName = document.getElementById('property-name');
    const roomName = document.getElementById('room-name');
    const totalAmount = document.getElementById('total-amount');

    let selectedBooking = null;
    let isProcessing = false;

    // Handle booking selection
    bookingSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            selectedBooking = {
                id: this.value,
                amount: selectedOption.dataset.amount,
                property: selectedOption.dataset.property,
                room: selectedOption.dataset.room
            };

            // Show payment info
            paymentInfo.classList.remove('hidden');
            propertyName.textContent = selectedBooking.property;
            roomName.textContent = selectedBooking.room;
            totalAmount.textContent = 'Rp ' + formatNumber(selectedBooking.amount);

            // Enable pay button
            payButton.disabled = false;
        } else {
            selectedBooking = null;
            paymentInfo.classList.add('hidden');
            payButton.disabled = true;
        }
    });

    // Handle payment button click
    payButton.addEventListener('click', function() {
        if (!selectedBooking || isProcessing) {
            return;
        }

        isProcessing = true;
        buttonText.textContent = 'Memproses...';
        payButton.disabled = true;

        // Create Midtrans checkout
        fetch('{{ route("tenant.payments.midtrans.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                booking_id: selectedBooking.id,
                amount: selectedBooking.amount
            })
        })
        .then(response => response.json())
        .then(data => {
            // DEBUG: Log response from server
            console.log('=== MIDTRANS RESPONSE ===', data);
            
            if (data.success && data.snap_token) {
                console.log('Snap token received:', data.snap_token);
                
                // Check if snap is available
                if (typeof snap === 'undefined') {
                    alert('Midtrans Payment Gateway tidak dapat dimuat.\n\nSilakan:\n1. Disable ad-blocker\n2. Refresh halaman\n3. Coba lagi');
                    resetButton();
                    return;
                }
                
                // Open Midtrans Snap popup
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        window.location.href = '{{ route("tenant.payments.index") }}?payment_success=1';
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        window.location.href = '{{ route("tenant.payments.index") }}?payment_pending=1';
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                        resetButton();
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        resetButton();
                    }
                });
            } else {
                alert(data.message || 'Gagal membuat transaksi pembayaran');
                resetButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            resetButton();
        });
    });

    function resetButton() {
        isProcessing = false;
        buttonText.textContent = 'Bayar Sekarang';
        if (selectedBooking) {
            payButton.disabled = false;
        }
    }

    function formatNumber(num) {
        return parseFloat(num).toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }
</script>
@endpush
@endsection
