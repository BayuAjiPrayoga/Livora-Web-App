@extends('layouts.tenant')

@section('title', 'Pembayaran Saya - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Pembayaran Saya
                    </h1>
                    <p class="text-gray-600 mt-1">Kelola dan lacak status pembayaran Anda</p>
                </div>
                <!-- METODE PEMBAYARAN KONVENSIONAL - DINONAKTIFKAN (MENGGUNAKAN MIDTRANS) -->
                {{-- 
                <a href="{{ route('tenant.payments.create') }}" 
                   class="btn btn-primary mr-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Upload Bukti Bayar
                </a>
                --}}
                <a href="{{ route('tenant.payments.midtrans.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-orange-500 text-white font-semibold rounded-lg hover:from-orange-700 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Bayar Online (Midtrans)
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Pembayaran -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pembayaran</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $payments->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Menunggu Pembayaran -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Menunggu Pembayaran</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $payments->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Berhasil -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pembayaran Berhasil</p>
                        <p class="text-2xl font-bold text-green-600">{{ $payments->whereIn('status', ['verified', 'settlement'])->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Pembayaran -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-50 rounded-lg">
                        <svg class="w-6 h-6 text-[#ff6900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Bayar</p>
                        <p class="text-2xl font-bold text-[#ff6900]">Rp {{ number_format($payments->sum('amount'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments List -->
        @if($payments->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h3>
            </div>
            
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">#{{ $payment->booking->id }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $payment->booking->room->name ?? 'N/A' }} - 
                                        {{ $payment->booking->room->boardingHouse->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu Pembayaran'],
                                        'verified' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Berhasil'],
                                        'settlement' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Berhasil'],
                                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                                        'expired' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Kadaluarsa'],
                                        'cancelled' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Dibatalkan'],
                                        'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Gagal'],
                                        'refund' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Refund'],
                                    ];
                                    $config = $statusConfig[$payment->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($payment->status)];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                    @if($payment->payment_type)
                                        <span class="ml-1 opacity-75">({{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }})</span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payment->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                                        <a href="{{ route('tenant.payments.show', $payment) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                            Lihat Detail
                                        </a>
                                        @if($payment->status === 'verified')
                                            <a href="{{ route('tenant.payments.download-receipt', $payment) }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                Download Kwitansi
                                            </a>
                                        @endif
                                        @if($payment->status === 'pending')
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <a href="{{ route('tenant.payments.edit', $payment) }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                Edit Pembayaran
                                            </a>
                                            <button onclick="deletePayment('{{ $payment->id }}')" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                Hapus
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4 p-4">
                @foreach($payments as $payment)
                @php
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                        'verified' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Berhasil'],
                        'settlement' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Berhasil'],
                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                        'expired' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Expired'],
                        'cancelled' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Batal'],
                        'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Gagal'],
                        'refund' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Refund'],
                    ];
                    $config = $statusConfig[$payment->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($payment->status)];
                @endphp
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-900">#{{ $payment->booking->id }}</h4>
                            <p class="text-sm text-gray-600">{{ $payment->booking->room->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->booking->room->boardingHouse->name ?? 'N/A' }}</p>
                            @if($payment->payment_type)
                                <p class="text-xs text-gray-500 mt-1">
                                    <span class="font-medium">Metode:</span> {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}
                                </p>
                            @endif
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                            {{ $config['label'] }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-lg font-bold text-[#ff6900]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <a href="{{ route('tenant.payments.show', $payment) }}" 
                           class="text-[#ff6900] hover:text-blue-700 text-sm font-medium">
                            Detail →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pembayaran</h3>
                <p class="text-gray-500 mb-6">Anda belum melakukan pembayaran apapun.</p>
                <a href="{{ route('tenant.payments.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#ff6900] border border-transparent rounded-lg font-semibold text-white hover:bg-blue-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Pembayaran Pertama
                </a>
            </div>
        </div>
        @endif

        <!-- Info Card -->
        <div class="bg-gradient-to-br from-livora-primary to-blue-600 rounded-xl p-6 text-white mt-8">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold mb-3">💡 Tips Pembayaran</h3>
                    <div class="space-y-2 text-blue-100 text-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Pastikan bukti pembayaran jelas dan dapat dibaca
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Upload foto dengan format JPG, JPEG, atau PNG (maksimal 2MB)
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Periksa kembali jumlah pembayaran sebelum submit
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Pembayaran yang sudah terverifikasi tidak dapat diubah
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function deletePayment(paymentId) {
    if (confirm('Yakin ingin menghapus pembayaran ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tenant/payments/${paymentId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection