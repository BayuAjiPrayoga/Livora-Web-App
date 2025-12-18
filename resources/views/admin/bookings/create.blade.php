@extends('layouts.admin')

@section('title', 'Create Booking - LIVORA Admin')

@section('page-title', 'Create New Booking')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Create New Booking</h1>
                    <p class="text-sm text-gray-600 mt-1">Create a booking for a tenant</p>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    Back to Bookings
                </a>
            </div>

            <form action="{{ route('admin.bookings.store') }}" method="POST" class="space-y-6" id="booking-form">
                @csrf

                <!-- User Selection -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Tenant *</label>
                        <select name="user_id" id="user_id" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('user_id') border-red-500 @enderror">
                            <option value="">Select Tenant</option>
                            @foreach(App\Models\User::where('role', 'tenant')->get() as $tenant)
                                <option value="{{ $tenant->id }}" {{ old('user_id') == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }} ({{ $tenant->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Booking Status *</label>
                        <select name="status" id="status" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="checked_in" {{ old('status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Property and Room Selection -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="boarding_house_id" class="block text-sm font-medium text-gray-700 mb-2">Boarding House *</label>
                        <select name="boarding_house_id" id="boarding_house_id" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('boarding_house_id') border-red-500 @enderror">
                            <option value="">Select Boarding House</option>
                            @foreach(App\Models\BoardingHouse::with('owner')->get() as $property)
                                <option value="{{ $property->id }}" {{ old('boarding_house_id') == $property->id ? 'selected' : '' }}>
                                    {{ $property->name }} - {{ $property->city }} ({{ $property->owner->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('boarding_house_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">Room *</label>
                        <select name="room_id" id="room_id" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('room_id') border-red-500 @enderror">
                            <option value="">Select Room</option>
                            <!-- Rooms will be populated via JavaScript -->
                        </select>
                        @error('room_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Booking Dates -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                               min="{{ date('Y-m-d') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="base_amount" class="block text-sm font-medium text-gray-700 mb-2">Base Amount (Rp) *</label>
                        <input type="number" name="base_amount" id="base_amount" value="{{ old('base_amount') }}" required min="0" step="1000"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('base_amount') border-red-500 @enderror">
                        @error('base_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="additional_fees" class="block text-sm font-medium text-gray-700 mb-2">Additional Fees (Rp)</label>
                        <input type="number" name="additional_fees" id="additional_fees" value="{{ old('additional_fees', 0) }}" min="0" step="1000"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('additional_fees') border-red-500 @enderror">
                        @error('additional_fees')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="final_amount" class="block text-sm font-medium text-gray-700 mb-2">Final Amount (Rp) *</label>
                        <input type="number" name="final_amount" id="final_amount" value="{{ old('final_amount') }}" required min="0" step="1000" readonly
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:ring-livora-primary focus:border-livora-primary @error('final_amount') border-red-500 @enderror">
                        @error('final_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Special Requests -->
                <div>
                    <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-2">Special Requests</label>
                    <textarea name="special_requests" id="special_requests" rows="3"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('special_requests') border-red-500 @enderror">{{ old('special_requests') }}</textarea>
                    @error('special_requests')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Admin Notes -->
                <div>
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                    <textarea name="admin_notes" id="admin_notes" rows="3"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('admin_notes') border-red-500 @enderror">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.bookings.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-livora-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Create Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const boardingHouseSelect = document.getElementById('boarding_house_id');
    const roomSelect = document.getElementById('room_id');
    const baseAmountInput = document.getElementById('base_amount');
    const additionalFeesInput = document.getElementById('additional_fees');
    const finalAmountInput = document.getElementById('final_amount');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Load rooms when boarding house changes
    boardingHouseSelect.addEventListener('change', function() {
        const boardingHouseId = this.value;
        roomSelect.innerHTML = '<option value="">Loading rooms...</option>';
        baseAmountInput.value = '';
        
        if (boardingHouseId) {
            fetch(`/admin/properties/${boardingHouseId}/rooms`)
                .then(response => response.json())
                .then(data => {
                    roomSelect.innerHTML = '<option value="">Select Room</option>';
                    data.rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = `${room.name} - Rp ${room.price.toLocaleString('id-ID')}`;
                        option.dataset.price = room.price;
                        roomSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading rooms:', error);
                    roomSelect.innerHTML = '<option value="">Error loading rooms</option>';
                });
        } else {
            roomSelect.innerHTML = '<option value="">Select Room</option>';
        }
    });

    // Update base amount when room changes
    roomSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.dataset.price) {
            baseAmountInput.value = selectedOption.dataset.price;
            calculateFinalAmount();
        } else {
            baseAmountInput.value = '';
        }
    });

    // Calculate final amount
    function calculateFinalAmount() {
        const baseAmount = parseFloat(baseAmountInput.value) || 0;
        const additionalFees = parseFloat(additionalFeesInput.value) || 0;
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        let days = 1;
        if (startDate && endDate && endDate > startDate) {
            days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
        }
        
        const finalAmount = (baseAmount * days) + additionalFees;
        finalAmountInput.value = finalAmount;
    }

    // Recalculate when amounts or dates change
    baseAmountInput.addEventListener('input', calculateFinalAmount);
    additionalFeesInput.addEventListener('input', calculateFinalAmount);
    startDateInput.addEventListener('change', calculateFinalAmount);
    endDateInput.addEventListener('change', calculateFinalAmount);

    // Set minimum end date based on start date
    startDateInput.addEventListener('change', function() {
        const startDate = this.value;
        if (startDate) {
            const nextDay = new Date(startDate);
            nextDay.setDate(nextDay.getDate() + 1);
            endDateInput.min = nextDay.toISOString().split('T')[0];
            
            if (endDateInput.value && endDateInput.value <= startDate) {
                endDateInput.value = nextDay.toISOString().split('T')[0];
            }
        }
        calculateFinalAmount();
    });
});
</script>
@endpush
@endsection