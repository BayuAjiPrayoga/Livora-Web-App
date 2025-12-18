@extends('layouts.tenant')

@section('title', 'Edit Booking - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('tenant.bookings.show', $booking) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Booking</h1>
                    <p class="text-gray-600 mt-1">Booking ID: {{ $booking->id }} • {{ $booking->room->boardingHouse->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="text-red-700">
                    <p class="font-medium mb-2">Terdapat kesalahan pada form:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Notice -->
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Perhatian:</strong> Booking hanya dapat diedit jika statusnya masih "Menunggu Konfirmasi". Setelah dikonfirmasi oleh pemilik, booking tidak dapat diubah.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Edit Informasi Booking</h2>
            </div>

            <form action="{{ route('tenant.bookings.update', $booking) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Current Booking Info (Read Only) -->
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-md font-medium text-gray-900 mb-3">Booking Saat Ini</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Kamar:</span>
                            <span class="font-medium text-gray-900">{{ $booking->room->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Check-in:</span>
                            <span class="font-medium text-gray-900">{{ $booking->check_in_date->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Check-out:</span>
                            <span class="font-medium text-gray-900">{{ $booking->check_out_date->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Property Selection -->
                <div class="mb-6">
                    <label for="boarding_house_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Property
                    </label>
                    <select name="boarding_house_id" id="boarding_house_id" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary"
                            onchange="loadRooms()">
                        <option value="">Pilih property...</option>
                        @foreach($boardingHouses as $property)
                            <option value="{{ $property->id }}" 
                                    {{ old('boarding_house_id', $booking->room->boarding_house_id) == $property->id ? 'selected' : '' }}>
                                {{ $property->name }} - {{ $property->city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Room Selection -->
                <div class="mb-6">
                    <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Kamar *
                    </label>
                    <select name="room_id" id="room_id" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                        <option value="">Pilih kamar...</option>
                    </select>
                    <div id="room-info" class="mt-3 hidden p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <!-- Room details will be populated here -->
                    </div>
                </div>

                <!-- Booking Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Check-in *
                        </label>
                        <input type="date" name="start_date" id="start_date" required 
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('start_date', $booking->check_in_date->format('Y-m-d')) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Check-out *
                        </label>
                        <input type="date" name="end_date" id="end_date" required
                               value="{{ old('end_date', $booking->check_out_date->format('Y-m-d')) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    </div>
                </div>

                <!-- Duration Type -->
                <input type="hidden" name="duration_type" value="monthly">

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Tambahan
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              placeholder="Permintaan khusus atau catatan untuk pemilik property..."
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">{{ old('notes', $booking->notes) }}</textarea>
                </div>

                <!-- Price Calculation -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Estimasi Biaya</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Harga per bulan:</span>
                            <span id="room-price">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Durasi:</span>
                            <span id="duration">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span id="subtotal">-</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Biaya admin:</span>
                            <span>Rp 50.000</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 flex justify-between font-medium">
                            <span>Total Estimasi:</span>
                            <span id="total-amount" class="text-livora-primary">-</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('tenant.bookings.show', $booking) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-livora-primary transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-livora-primary border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- Data from server -->
<script type="application/json" id="booking-data">
{
    "allRooms": @json($allRoomsData),
    "currentRoomId": @json($booking->room_id),
    "adminFee": 50000
}
</script>

<script>
// Parse data from JSON script tag
var bookingData = JSON.parse(document.getElementById('booking-data').textContent);
var allRooms = bookingData.allRooms;
var ADMIN_FEE = bookingData.adminFee;
var currentRoomPrice = 0;

// Load rooms when property changes
function loadRooms() {
    var propertySelect = document.getElementById('boarding_house_id');
    var roomSelect = document.getElementById('room_id');
    var roomInfo = document.getElementById('room-info');

    var propertyId = propertySelect.value;
    
    // Clear room selection
    roomSelect.innerHTML = '<option value="">Pilih kamar...</option>';
    roomInfo.classList.add('hidden');
    resetPriceCalculation();
    
    if (!propertyId) return;
    
    // Filter rooms by property
    var propertyRooms = allRooms.filter(function(room) { return room.boarding_house_id == propertyId; });
    
    propertyRooms.forEach(function(room) {
        var option = document.createElement('option');
        option.value = room.id;
        option.textContent = room.name + ' - Rp ' + room.price_formatted + '/bulan';
        option.dataset.roomData = JSON.stringify(room);
        roomSelect.appendChild(option);
    });
    
    // If this is the current booking room, select it
    var currentRoomId = bookingData.currentRoomId;
    if (currentRoomId && propertyRooms.find(function(room) { return room.id == currentRoomId; })) {
        roomSelect.value = currentRoomId;
        onRoomSelected();
    }
}

// Handle room selection
function onRoomSelected() {
    var roomSelect = document.getElementById('room_id');
    var roomInfo = document.getElementById('room-info');
    
    if (!roomSelect.value) {
        roomInfo.classList.add('hidden');
        resetPriceCalculation();
        return;
    }
    
    var selectedOption = roomSelect.options[roomSelect.selectedIndex];
    var roomData = JSON.parse(selectedOption.dataset.roomData);
    
    // Update price calculation
    currentRoomPrice = parseFloat(roomData.price);
    document.getElementById('room-price').textContent = 'Rp ' + roomData.price_formatted;
    
    // Show room info
    roomInfo.innerHTML = '<div class="grid grid-cols-2 gap-4 text-sm">' +
        '<div><span class="text-gray-600">Kapasitas:</span> <span class="font-medium text-gray-900">' + roomData.capacity + ' orang</span></div>' +
        '<div><span class="text-gray-600">Ukuran:</span> <span class="font-medium text-gray-900">' + roomData.size + ' m²</span></div>' +
        '</div>' +
        (roomData.description ? '<p class="text-sm text-gray-600 mt-2">' + roomData.description + '</p>' : '');
    roomInfo.classList.remove('hidden');
    
    calculatePrice();
}

// Calculate pricing
function calculatePrice() {
    var startDate = document.getElementById('start_date').value;
    var endDate = document.getElementById('end_date').value;
    
    if (!startDate || !endDate || currentRoomPrice <= 0) {
        resetPriceCalculation();
        return;
    }
    
    var start = new Date(startDate);
    var end = new Date(endDate);
    
    if (end <= start) {
        resetPriceCalculation();
        return;
    }
    
    // Calculate duration in months (approximate)
    var diffTime = Math.abs(end - start);
    var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    var diffMonths = Math.max(1, Math.round(diffDays / 30));
    
    var subtotal = currentRoomPrice * diffMonths;
    var total = subtotal + ADMIN_FEE;
    
    document.getElementById('duration').textContent = diffMonths + ' bulan';
    document.getElementById('subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
    document.getElementById('total-amount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}

function resetPriceCalculation() {
    document.getElementById('room-price').textContent = '-';
    document.getElementById('duration').textContent = '-';
    document.getElementById('subtotal').textContent = '-';
    document.getElementById('total-amount').textContent = '-';
    currentRoomPrice = 0;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('room_id').addEventListener('change', onRoomSelected);
    document.getElementById('start_date').addEventListener('change', calculatePrice);
    document.getElementById('end_date').addEventListener('change', calculatePrice);
    
    // Load initial data if property is already selected
    if (document.getElementById('boarding_house_id').value) {
        loadRooms();
    }
});
</script>
@endpush
@endsection