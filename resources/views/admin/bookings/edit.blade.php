@extends('layouts.admin')

@section('title', 'Edit Booking - LIVORA')

@section('page-title', 'Edit Booking')

@section('content')
<div class="p-6">
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-[#ff6900]">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Bookings
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-900">Edit Booking #{{ $booking->booking_number }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Booking Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Booking Information</h3>
                    <p class="text-sm text-gray-500 mt-1">Booking #{{ $booking->booking_number }}</p>
                </div>

                <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" id="booking-form">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Booking Status</label>
                                <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                                    <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="checked_in" {{ $booking->status === 'checked_in' ? 'selected' : '' }}>Checked In</option>
                                    <option value="checked_out" {{ $booking->status === 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label for="check_in_date" class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
                                <input type="date" name="check_in_date" id="check_in_date" 
                                       value="{{ $booking->check_in_date ? $booking->check_in_date->format('Y-m-d') : '' }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="check_out_date" class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
                                <input type="date" name="check_out_date" id="check_out_date" 
                                       value="{{ $booking->check_out_date ? $booking->check_out_date->format('Y-m-d') : '' }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                            </div>
                            <div>
                                <label for="duration_months" class="block text-sm font-medium text-gray-700 mb-2">Duration (months)</label>
                                <input type="number" name="duration_months" id="duration_months" 
                                       value="{{ $booking->duration_months }}" min="1"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                            </div>
                        </div>

                        <div>
                            <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                            <textarea name="admin_notes" id="admin_notes" rows="4" 
                                      placeholder="Add internal notes about this booking..."
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">{{ $booking->admin_notes }}</textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.bookings.show', $booking) }}" 
                               class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Update Booking
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Activity Log -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Activity Log</h3>
                <div class="space-y-4">
                    @forelse($booking->activities ?? [] as $activity)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-[#ff6900] bg-opacity-10 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#ff6900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">{{ $activity['description'] ?? 'Activity logged' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity['created_at'] ?? 'Recently' }} by {{ $activity['user'] ?? 'System' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-sm text-gray-500">No activity logged yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Current Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $booking->status === 'checked_in' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $booking->status === 'checked_out' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Payment Status:</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($booking->payment_status ?? 'Pending') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Amount:</span>
                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->final_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Tenant Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tenant Information</h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        @if($booking->tenant->avatar ?? false)
                            <img src="{{ asset('storage/' . $booking->tenant->avatar) }}" alt="{{ $booking->tenant->name }}" class="w-10 h-10 rounded-full">
                        @else
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $booking->tenant->name ?? 'Unknown Tenant' }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->tenant->email ?? 'No email' }}</p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500">Phone:</span>
                                <p class="text-gray-900">{{ $booking->tenant->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Joined:</span>
                                <p class="text-gray-900">{{ $booking->tenant->created_at ? $booking->tenant->created_at->format('M Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.show', $booking->tenant->id ?? '#') }}" 
                       class="text-sm text-[#ff6900] hover:text-blue-700 font-medium">
                        View Full Profile →
                    </a>
                </div>
            </div>

            <!-- Property Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Property & Room</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $booking->room->property->name ?? 'Unknown Property' }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->room->property->address ?? 'No address' }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500">Room:</span>
                                <p class="text-gray-900">{{ $booking->room->name ?? 'Unknown Room' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Type:</span>
                                <p class="text-gray-900">{{ $booking->room->type ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.properties.show', $booking->room->property->id ?? '#') }}" 
                       class="text-sm text-[#ff6900] hover:text-blue-700 font-medium">
                        View Property Details →
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($booking->status === 'pending')
                    <button onclick="confirmBooking()" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                        Confirm Booking
                    </button>
                    @endif
                    
                    @if($booking->status === 'confirmed')
                    <button onclick="checkInTenant()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Check-in Tenant
                    </button>
                    @endif
                    
                    @if($booking->status === 'checked_in')
                    <button onclick="checkOutTenant()" class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                        Check-out Tenant
                    </button>
                    @endif
                    
                    @if(in_array($booking->status, ['pending', 'confirmed']))
                    <button onclick="cancelBooking()" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                        Cancel Booking
                    </button>
                    @endif
                    
                    <a href="mailto:{{ $booking->tenant->email ?? '' }}" 
                       class="w-full block text-center bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                        Email Tenant
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmBooking() {
        if (confirm('Are you sure you want to confirm this booking?')) {
            updateBookingStatus('confirmed');
        }
    }

    function checkInTenant() {
        if (confirm('Mark this tenant as checked in?')) {
            updateBookingStatus('checked_in');
        }
    }

    function checkOutTenant() {
        if (confirm('Mark this tenant as checked out?')) {
            updateBookingStatus('checked_out');
        }
    }

    function cancelBooking() {
        if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
            updateBookingStatus('cancelled');
        }
    }

    function updateBookingStatus(status) {
        document.getElementById('status').value = status;
        document.getElementById('booking-form').submit();
    }

    // Auto-calculate duration when dates change
    document.getElementById('check_in_date').addEventListener('change', calculateDuration);
    document.getElementById('check_out_date').addEventListener('change', calculateDuration);

    function calculateDuration() {
        const checkIn = new Date(document.getElementById('check_in_date').value);
        const checkOut = new Date(document.getElementById('check_out_date').value);
        
        if (checkIn && checkOut && checkOut > checkIn) {
            const diffTime = Math.abs(checkOut - checkIn);
            const diffMonths = Math.ceil(diffTime / (1000 * 60 * 60 * 24 * 30));
            document.getElementById('duration_months').value = diffMonths;
        }
    }
</script>
@endpush
@endsection