@extends('layouts.admin')

@section('title', 'Payment Details - LIVORA')

@section('page-title', 'Payment Details')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.payments.index') }}" class="text-gray-700 hover:text-livora-primary">
                            Payments
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 md:ml-2">Payment #{{ $payment->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-semibold text-gray-900 mt-2">Payment #{{ $payment->id }}</h1>
        </div>
        <div class="flex space-x-3">
            @if($payment->status === 'pending')
                <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                        Verify Payment
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" class="inline reject-payment-form">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                        Reject Payment
                    </button>
                </form>
            @endif
            @if($payment->payment_proof)
                <a href="{{ route('admin.payments.download-proof', $payment) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    Download Proof
                </a>
            @endif
        </div>
    </div>

    <!-- Status Alert -->
    <div class="mb-6">
        @php
            $statusColors = [
                'pending' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
                'verified' => 'bg-green-50 text-green-800 border-green-200',
                'rejected' => 'bg-red-50 text-red-800 border-red-200'
            ];
        @endphp
        <div class="border-l-4 p-4 {{ $statusColors[$payment->status] ?? 'bg-gray-50 text-gray-800 border-gray-200' }}">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm">
                        <strong>Status:</strong> {{ ucfirst($payment->status) }}
                        @if($payment->status === 'pending')
                            - Menunggu verifikasi admin
                        @elseif($payment->status === 'verified')
                            - Pembayaran telah diverifikasi
                        @elseif($payment->status === 'rejected')
                            - Pembayaran ditolak
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Payment Information</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">#{{ $payment->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $payment->created_at->format('F d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $payment->payment_method ?? 'Transfer Bank' }}</dd>
                        </div>
                        @if($payment->payment_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Paid Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $payment->payment_date->format('F d, Y') }}</dd>
                        </div>
                        @endif
                        @if($payment->verified_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Verified Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $payment->verified_at->format('F d, Y H:i') }}</dd>
                        </div>
                        @endif
                        @if($payment->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $payment->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Related Booking</h3>
                    <a href="{{ route('admin.bookings.show', $payment->booking) }}" class="text-livora-primary hover:text-blue-700 text-sm font-medium">
                        View Booking Details →
                    </a>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-gray-900">Booking #{{ $payment->booking->id }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ $payment->booking->room->boardingHouse->name }}</p>
                            <p class="text-sm text-gray-500">Room: {{ $payment->booking->room->name }}</p>
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Check-in</dt>
                                    <dd class="text-sm text-gray-900">{{ $payment->booking->check_in_date->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Check-out</dt>
                                    <dd class="text-sm text-gray-900">{{ $payment->booking->check_out_date->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Status</dt>
                                    <dd class="text-sm">
                                        @php
                                            $bookingStatusColors = [
                                                'pending' => 'text-yellow-600',
                                                'confirmed' => 'text-green-600',
                                                'cancelled' => 'text-red-600',
                                                'completed' => 'text-blue-600'
                                            ];
                                        @endphp
                                        <span class="{{ $bookingStatusColors[$payment->booking->status] ?? 'text-gray-600' }}">
                                            {{ ucfirst($payment->booking->status) }}
                                        </span>
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="h-12 w-12 flex-shrink-0">
                            <div class="h-12 w-12 bg-livora-primary rounded-full flex items-center justify-center">
                                <span class="text-lg font-medium text-white">
                                    {{ substr($payment->booking->user->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-lg font-medium text-gray-900">{{ $payment->booking->user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $payment->booking->user->email }}</p>
                            @if($payment->booking->user->phone)
                                <p class="text-sm text-gray-500">{{ $payment->booking->user->phone }}</p>
                            @endif
                        </div>
                        <div>
                            <a href="mailto:{{ $payment->booking->user->email }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Proof -->
            @if($payment->payment_proof)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Payment Proof</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Payment Proof" class="max-w-full h-auto mx-auto rounded-lg shadow-sm border border-gray-200" style="max-height: 500px;">
                        <div class="mt-4 space-x-3">
                            <a href="{{ route('admin.payments.download-proof', $payment) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Full Size
                            </a>
                            <button type="button" data-proof-url="{{ asset('storage/' . $payment->payment_proof) }}" onclick="openFullscreen(this.dataset.proofUrl)" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                                View Fullscreen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @if($payment->status === 'pending')
                        <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" class="w-full">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Verify Payment
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" class="w-full reject-payment-form-sidebar">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject Payment
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.bookings.show', $payment->booking) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        View Booking
                    </a>
                    <a href="mailto:{{ $payment->booking->user->email }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact Customer
                    </a>
                    @if($payment->payment_proof)
                        <a href="{{ route('admin.payments.download-proof', $payment) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Proof
                        </a>
                    @endif
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Summary</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Payment Amount</span>
                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Payment Method</span>
                        <span class="text-sm text-gray-900">{{ $payment->payment_method ?? 'Transfer Bank' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="text-sm">
                            @php
                                $statusColors = [
                                    'pending' => 'text-yellow-600',
                                    'verified' => 'text-green-600',
                                    'rejected' => 'text-red-600'
                                ];
                            @endphp
                            <span class="{{ $statusColors[$payment->status] ?? 'text-gray-600' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </span>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Booking Total</span>
                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->booking->final_amount, 0, ',', '.') }}</span>
                        </div>
                        @php
                            $totalPaid = $payment->booking->payments->where('status', 'verified')->sum('amount');
                            $remaining = $payment->booking->final_amount - $totalPaid;
                        @endphp
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Paid</span>
                            <span class="text-sm text-green-600">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Remaining</span>
                            <span class="text-sm {{ $remaining > 0 ? 'text-red-600' : 'text-green-600' }}">
                                Rp {{ number_format($remaining, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Modal -->
<div id="fullscreenModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-screen">
            <button onclick="closeFullscreen()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="fullscreenImage" src="" alt="Payment Proof" class="max-w-full max-h-screen object-contain">
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle reject payment confirmation
    document.querySelectorAll('.reject-payment-form, .reject-payment-form-sidebar').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to reject this payment?')) {
                this.submit();
            }
        });
    });

    // Fullscreen functionality
    function openFullscreen(imageUrl) {
        document.getElementById('fullscreenImage').src = imageUrl;
        document.getElementById('fullscreenModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeFullscreen() {
        document.getElementById('fullscreenModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside image
    document.getElementById('fullscreenModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeFullscreen();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('fullscreenModal').classList.contains('hidden')) {
            closeFullscreen();
        }
    });
</script>
@endpush
@endsection