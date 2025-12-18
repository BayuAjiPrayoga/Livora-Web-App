@extends('layouts.admin')

@section('title', 'Booking Details - LIVORA')

@section('page-title', 'Booking Details')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.bookings.index') }}" class="text-gray-700 hover:text-[#ff6900]">
                            Bookings
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 md:ml-2">Booking #{{ $booking->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-semibold text-gray-900 mt-2">Booking #{{ $booking->id }}</h1>
        </div>
        <div class="flex space-x-3">
            @if($booking->status === 'pending')
                <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                        Approve Booking
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.bookings.reject', $booking) }}" class="inline reject-booking-form">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                        Reject Booking
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-primary">
                Edit Booking
            </a>
        </div>
    </div>

    <!-- Status Alert -->
    <div class="mb-6">
        @php
            $statusColors = [
                'pending' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
                'confirmed' => 'bg-green-50 text-green-800 border-green-200',
                'cancelled' => 'bg-red-50 text-red-800 border-red-200',
                'completed' => 'bg-blue-50 text-blue-800 border-blue-200'
            ];
        @endphp
        <div class="border-l-4 p-4 {{ $statusColors[$booking->status] ?? 'bg-gray-50 text-gray-800 border-gray-200' }}">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm">
                        <strong>Status:</strong> {{ ucfirst($booking->status) }}
                        @if($booking->status === 'pending')
                            - Menunggu persetujuan admin
                        @elseif($booking->status === 'confirmed')
                            - Booking telah dikonfirmasi
                        @elseif($booking->status === 'cancelled')
                            - Booking telah dibatalkan
                        @elseif($booking->status === 'completed')
                            - Booking telah selesai
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Booking Information</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Booking ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">#{{ $booking->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Booking Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->created_at->format('F d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Check-in Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->check_in_date->format('F d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Check-out Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->check_out_date->format('F d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Duration</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($booking->booking_type === 'daily')
                                    {{ $booking->duration_days }} days
                                @else
                                    {{ $booking->duration_months }} months
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</dd>
                        </div>
                        @if($booking->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- User Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Guest Information</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="h-12 w-12 flex-shrink-0">
                            <div class="h- w- bg-gradient-to-br from-[#ff6900] to-[#ff8533] rounded-full flex items-center justify-center">
                                <span class="text-lg font-medium text-white">
                                    {{ substr($booking->user->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-lg font-medium text-gray-900">{{ $booking->user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                            @if($booking->user->phone)
                                <p class="text-sm text-gray-500">{{ $booking->user->phone }}</p>
                            @endif
                        </div>
                        <div>
                            <a href="mailto:{{ $booking->user->email }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Room Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Room Information</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-start space-x-4">
                        @if($booking->room->image)
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $booking->room->image) }}" alt="Room" class="w-24 h-24 object-cover rounded-lg">
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-gray-900">{{ $booking->room->name }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ $booking->room->boardingHouse->name }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->room->description }}</p>
                            <div class="mt-3 flex items-center space-x-4">
                                <span class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->room->price, 0, ',', '.') }}/month</span>
                                <span class="text-sm text-gray-500">Size: {{ $booking->room->size }}m²</span>
                                @if($booking->room->facilities)
                                    <span class="text-sm text-gray-500">{{ $booking->room->facilities }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Payment History</h3>
                </div>
                @if($booking->payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($booking->payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $payment->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'verified' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.payments.show', $payment) }}" class="text-[#ff6900] hover:text-blue-700">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No payments</h3>
                        <p class="mt-1 text-sm text-gray-500">No payments have been made for this booking yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Booking
                    </a>
                    <a href="mailto:{{ $booking->user->email }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact Guest
                    </a>
                    <a href="{{ route('admin.properties.show', $booking->room->boarding_house_id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        View Property Details
                    </a>
                </div>
            </div>

            <!-- Booking Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Timeline</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative px-1">
                                            <div class="h-8 w-8 bg-blue-500 rounded-full ring-8 ring-white flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900">Booking Created</span>
                                                </div>
                                                <p class="mt-0.5 text-sm text-gray-500">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @if($booking->status !== 'pending')
                                <li>
                                    <div class="relative pb-8">
                                        @if($booking->status !== 'cancelled')
                                            <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                        @endif
                                        <div class="relative flex items-start space-x-3">
                                            <div class="relative px-1">
                                                <div class="h-8 w-8 {{ $booking->status === 'confirmed' ? 'bg-green-500' : 'bg-red-500' }} rounded-full ring-8 ring-white flex items-center justify-center">
                                                    @if($booking->status === 'confirmed')
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <div class="text-sm">
                                                        <span class="font-medium text-gray-900">Booking {{ ucfirst($booking->status) }}</span>
                                                    </div>
                                                    <p class="mt-0.5 text-sm text-gray-500">{{ $booking->updated_at->format('M d, Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle reject booking confirmation
    document.querySelectorAll('.reject-booking-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to reject this booking?')) {
                this.submit();
            }
        });
    });
</script>
@endpush
@endsection