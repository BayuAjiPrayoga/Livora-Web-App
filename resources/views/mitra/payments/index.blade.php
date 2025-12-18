@extends('layouts.mitra')

@section('title', 'Kelola Pembayaran - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-livora-text">💰 Kelola Pembayaran</h1>
                    <p class="text-gray-600 mt-1">Kelola dan verifikasi pembayaran dari penyewa</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="bulkAction('verify')" id="bulk-verify-btn" disabled
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md font-medium hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Verifikasi Terpilih
                    </button>
                    <button onclick="bulkAction('reject')" id="bulk-reject-btn" disabled
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md font-medium hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Tolak Terpilih
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="px-6 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Payments Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pembayaran</p>
                        <h3 class="text-lg font-bold text-gray-900">{{ number_format($statistics['total_payments'] ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        
            <!-- Pending Payments Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Menunggu Verifikasi</p>
                        <h3 class="text-lg font-bold text-yellow-600">{{ number_format($statistics['pending_payments'] ?? 0) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Verified Payments Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Terverifikasi</p>
                        <h3 class="text-lg font-bold text-green-600">{{ number_format($statistics['verified_payments'] ?? 0) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Nilai</p>
                        <h3 class="text-lg font-bold text-indigo-600">Rp {{ number_format($statistics['total_amount'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-md border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                    Filter Pembayaran
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('mitra.payments.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                            <input type="date" name="date_from" id="date_from" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   value="{{ request('date_from') }}">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                            <input type="date" name="date_to" id="date_to" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   value="{{ request('date_to') }}">
                        </div>
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Booking/Tenant</label>
                            <input type="text" name="search" id="search" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="ID Booking atau Nama Tenant" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('mitra.payments.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Daftar Pembayaran
            </h3>
        </div>
        
        @if($payments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="payment_ids[]" value="{{ $payment->id }}" 
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 payment-checkbox" 
                                   {{ $payment->status !== 'pending' ? 'disabled' : '' }}>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-gray-900">#{{ $payment->booking->id ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $payment->booking->room->name ?? 'N/A' }} - 
                                    {{ $payment->booking->room->boardingHouse->name ?? 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->booking->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->booking->user->phone ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->status === 'pending')
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Menunggu</span>
                            @elseif($payment->status === 'verified')
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Terverifikasi</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $payment->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                <button type="button" @click="open = !open" @click.away="open = false" 
                                        class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" 
                                     class="absolute right-0 z-10 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
                                    <a href="{{ route('mitra.payments.show', $payment) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat Detail
                                    </a>
                                    <a href="{{ route('mitra.payments.download-proof', $payment) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download Bukti
                                    </a>
                                    @if($payment->status === 'verified')
                                        <a href="{{ route('mitra.payments.download-receipt', $payment) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download Kwitansi
                                        </a>
                                    @endif
                                    @if($payment->status === 'pending')
                                        <div class="border-t border-gray-100"></div>
                                        <a href="#" data-payment-id="{{ $payment->id }}" onclick="verifyPayment(this.dataset.paymentId)" class="block px-4 py-2 text-sm text-green-600 hover:bg-green-50">
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Verifikasi
                                        </a>
                                        <a href="#" data-payment-id="{{ $payment->id }}" onclick="rejectPayment(this.dataset.paymentId)" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Tolak
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $payments->firstItem() ?? 0 }} sampai {{ $payments->lastItem() ?? 0 }} dari {{ $payments->total() ?? 0 }} hasil
                </div>
                <div class="flex items-center space-x-2">
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-12 w-12">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada pembayaran</h3>
            <p class="mt-1 text-sm text-gray-500">Pembayaran dari tenant akan muncul di sini.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Select all functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.payment-checkbox:not([disabled])');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkButtons();
});

// Individual checkbox functionality
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('payment-checkbox')) {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.payment-checkbox:not([disabled])');
        const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked:not([disabled])');
        
        selectAll.checked = checkboxes.length === checkedBoxes.length;
        selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
        
        toggleBulkButtons();
    }
});

function toggleBulkButtons() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked:not([disabled])');
    const verifyBtn = document.getElementById('bulk-verify-btn');
    const rejectBtn = document.getElementById('bulk-reject-btn');
    
    if (checkedBoxes.length > 0) {
        verifyBtn.disabled = false;
        rejectBtn.disabled = false;
    } else {
        verifyBtn.disabled = true;
        rejectBtn.disabled = true;
    }
}

function verifyPayment(paymentId) {
    if (confirm('Yakin ingin memverifikasi pembayaran ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/mitra/payments/${paymentId}/verify`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectPayment(paymentId) {
    const reason = prompt('Masukkan alasan penolakan:');
    if (reason !== null && reason.trim() !== '') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/mitra/payments/${paymentId}/reject`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        
        const reasonField = document.createElement('input');
        reasonField.type = 'hidden';
        reasonField.name = 'rejection_reason';
        reasonField.value = reason;
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(reasonField);
        document.body.appendChild(form);
        form.submit();
    }
}

function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked:not([disabled])');
    if (checkedBoxes.length === 0) {
        alert('Pilih minimal satu pembayaran untuk diproses.');
        return;
    }
    
    let confirmMessage = action === 'verify' ? 
        `Yakin ingin memverifikasi ${checkedBoxes.length} pembayaran terpilih?` :
        `Yakin ingin menolak ${checkedBoxes.length} pembayaran terpilih?`;
    
    if (confirm(confirmMessage)) {
        let reason = '';
        if (action === 'reject') {
            reason = prompt('Masukkan alasan penolakan:');
            if (reason === null || reason.trim() === '') {
                return;
            }
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("mitra.payments.bulk-action") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const actionField = document.createElement('input');
        actionField.type = 'hidden';
        actionField.name = 'action';
        actionField.value = action;
        
        if (reason) {
            const reasonField = document.createElement('input');
            reasonField.type = 'hidden';
            reasonField.name = 'rejection_reason';
            reasonField.value = reason;
            form.appendChild(reasonField);
        }
        
        checkedBoxes.forEach(checkbox => {
            const idField = document.createElement('input');
            idField.type = 'hidden';
            idField.name = 'payment_ids[]';
            idField.value = checkbox.value;
            form.appendChild(idField);
        });
        
        form.appendChild(csrfToken);
        form.appendChild(actionField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection