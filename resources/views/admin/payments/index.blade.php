@extends('layouts.admin')

@section('title', 'Payment Management - LIVORA')

@section('page-title', 'Payment Management')

@section('content')
<div class="p-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-semibold text-gray-900">Payment Management</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola dan verifikasi pembayaran dalam sistem LIVORA</p>
        </div>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('admin.payments.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Payments</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Verified</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['verified'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Amount</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search Input -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Cari nama user atau ID payment..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Boarding House Filter -->
            <div>
                <label for="boarding_house_id" class="block text-sm font-medium text-gray-700 mb-2">Boarding House</label>
                <select name="boarding_house_id" id="boarding_house_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    <option value="">Semua Properti</option>
                    @foreach($boardingHouses as $house)
                        <option value="{{ $house->id }}" {{ request('boarding_house_id') == $house->id ? 'selected' : '' }}>
                            {{ $house->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
            </div>

            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-livora-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        @if($payments->count() > 0)
            <!-- Bulk Actions -->
            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                        <label for="selectAll" class="ml-2 text-sm font-medium text-gray-700">Select All</label>
                    </div>
                    <div class="hidden" id="bulkActions">
                        <button onclick="bulkVerify()" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                            Verify Selected
                        </button>
                        <button onclick="bulkReject()" class="ml-2 inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                            Reject Selected
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Info
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User & Booking
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount & Method
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Proof
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="payment-checkbox rounded border-gray-300" value="{{ $payment->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $payment->id }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->created_at->format('M d, Y H:i') }}</div>
                                @if($payment->payment_date)
                                    <div class="text-xs text-gray-400">Paid: {{ $payment->payment_date->format('M d, Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 bg-livora-primary rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($payment->booking->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->booking->user->name }}</div>
                                        <div class="text-sm text-gray-500">Booking #{{ $payment->booking->id }}</div>
                                        <div class="text-xs text-gray-400">{{ $payment->booking->room->boardingHouse->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->payment_method ?? 'Transfer Bank' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->payment_proof)
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.payments.download-proof', $payment) }}" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-livora-primary bg-blue-100 hover:bg-blue-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download
                                        </a>
                                        <button type="button" data-proof-url="{{ asset('storage/' . $payment->payment_proof) }}" onclick="previewProof(this.dataset.proofUrl)" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-gray-600 bg-gray-100 hover:bg-gray-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Preview
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">No proof uploaded</span>
                                @endif
                            </td>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="text-livora-primary hover:text-blue-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if($payment->status === 'pending')
                                        <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Verify">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" class="inline reject-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Reject">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No payments found</h3>
                <p class="mt-1 text-sm text-gray-500">No payments match your current filter criteria.</p>
            </div>
        @endif
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Payment Proof Preview</h3>
            <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="text-center">
            <img id="previewImage" src="" alt="Payment Proof" class="max-w-full h-auto mx-auto">
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Bulk actions functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.payment-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        toggleBulkActions();
    });

    document.querySelectorAll('.payment-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });

    function toggleBulkActions() {
        const selectedCount = document.querySelectorAll('.payment-checkbox:checked').length;
        const bulkActions = document.getElementById('bulkActions');
        
        if (selectedCount > 0) {
            bulkActions.classList.remove('hidden');
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    function getSelectedPaymentIds() {
        const selected = [];
        document.querySelectorAll('.payment-checkbox:checked').forEach(checkbox => {
            selected.push(checkbox.value);
        });
        return selected;
    }

    function bulkVerify() {
        const paymentIds = getSelectedPaymentIds();
        if (paymentIds.length === 0) return;
        
        if (confirm(`Are you sure you want to verify ${paymentIds.length} payment(s)?`)) {
            fetch('{{ route("admin.payments.bulk-verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ payment_ids: paymentIds })
            }).then(() => location.reload());
        }
    }

    function bulkReject() {
        const paymentIds = getSelectedPaymentIds();
        if (paymentIds.length === 0) return;
        
        if (confirm(`Are you sure you want to reject ${paymentIds.length} payment(s)?`)) {
            fetch('{{ route("admin.payments.bulk-reject") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ payment_ids: paymentIds })
            }).then(() => location.reload());
        }
    }

    // Preview functionality
    function previewProof(imageUrl) {
        document.getElementById('previewImage').src = imageUrl;
        document.getElementById('previewModal').classList.remove('hidden');
    }

    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('previewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePreview();
        }
    });

    // Handle reject confirmation
    document.querySelectorAll('.reject-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to reject this payment?')) {
                this.submit();
            }
        });
    });
</script>
@endpush
@endsection