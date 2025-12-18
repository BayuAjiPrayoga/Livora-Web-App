@extends('layouts.tenant')

@section('title', 'Edit Pembayaran - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Pembayaran #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
                    </h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi pembayaran Anda</p>
                </div>
                <a href="{{ route('tenant.payments.show', $payment) }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form - Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Booking Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-orange-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Booking yang Dibayar
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Informasi booking tidak dapat diubah</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">ID Booking</label>
                                <div class="text-lg font-semibold text-gray-900">#{{ $payment->booking->id }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status Booking</label>
                                <div>
                                    @if($payment->booking->status === 'confirmed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            Confirmed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ ucfirst($payment->booking->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Kamar</label>
                                <div class="text-lg font-semibold text-gray-900">{{ $payment->booking->room->name ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Kost</label>
                                <div class="text-lg font-semibold text-gray-900">{{ $payment->booking->room->boardingHouse->name ?? 'N/A' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Total Harga Booking</label>
                                <div class="text-2xl font-bold text-livora-primary">
                                    Rp {{ number_format($payment->booking->total_price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form Card -->
                <form action="{{ route('tenant.payments.update', $payment) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-livora-primary to-blue-600">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Formulir Edit Pembayaran
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Payment Amount -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Pembayaran <span class="text-red-600">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-semibold">
                                        Rp
                                    </span>
                                    <input type="number" 
                                           name="amount" 
                                           id="amount" 
                                           class="block w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent @error('amount') border-red-500 @enderror"
                                           placeholder="Masukkan jumlah pembayaran"
                                           min="1"
                                           step="1"
                                           value="{{ old('amount', $payment->amount) }}"
                                           required>
                                </div>
                                @error('amount')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-600">
                                    💡 Total harga booking: <span class="font-semibold text-livora-primary">Rp {{ number_format($payment->booking->total_price, 0, ',', '.') }}</span>
                                </p>
                            </div>

                            <!-- Current Payment Proof -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Bukti Pembayaran Saat Ini
                                </label>
                                @if($payment->proof_image)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($payment->proof_image) }}" 
                                             alt="Bukti Pembayaran Saat Ini" 
                                             class="w-full h-64 object-cover rounded-lg shadow-md border border-gray-200 cursor-pointer hover:opacity-90 transition"
                                             data-image-url="{{ Storage::url($payment->proof_image) }}">
                                    </div>
                                @else
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Tidak ada bukti pembayaran</p>
                                    </div>
                                @endif
                            </div>

                            <!-- New Payment Proof Upload -->
                            <div>
                                <label for="proof_image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Bukti Pembayaran Baru 
                                    <span class="text-gray-500 font-normal">(Opsional)</span>
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="proof_image" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-600"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                                            <p class="text-xs text-gray-500">JPEG, JPG, PNG (Max 2MB)</p>
                                        </div>
                                        <input id="proof_image" name="proof_image" type="file" class="hidden" accept="image/jpeg,image/jpg,image/png">
                                    </label>
                                </div>
                                @error('proof_image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">
                                    💡 Kosongkan jika tidak ingin mengubah bukti pembayaran yang sudah ada
                                </p>
                                
                                <!-- New Image Preview -->
                                <div id="new-image-preview" class="mt-4 hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Preview Bukti Baru:</label>
                                    <img id="new-preview-img" src="" alt="Preview Baru" class="w-full h-64 object-cover rounded-lg shadow-md border-2 border-livora-primary">
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('tenant.payments.show', $payment) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                                    Batal
                                </a>
                                <button type="submit" id="submitBtn" class="px-6 py-3 bg-gradient-to-r from-livora-primary to-blue-600 text-white rounded-lg font-medium hover:from-livora-secondary hover:to-blue-700 transition shadow-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Payment Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">ℹ️ Status Pembayaran</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            @if($payment->status === 'pending')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Menunggu Verifikasi
                                </span>
                            @elseif($payment->status === 'verified')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Terverifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ditolak
                                </span>
                            @endif
                        </div>
                        
                        @if($payment->status === 'pending')
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-blue-800">
                                        Pembayaran masih dapat diubah. Setelah diverifikasi, tidak dapat diubah lagi.
                                    </p>
                                </div>
                            </div>
                        @elseif($payment->status === 'rejected')
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-red-800 mb-1">Alasan Penolakan:</p>
                                        <p class="text-sm text-red-700">{{ $payment->notes ?? 'Tidak ada alasan yang diberikan.' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Submit:</span>
                                <span class="font-semibold text-gray-900">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if($payment->updated_at && $payment->updated_at != $payment->created_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Terakhir Diubah:</span>
                                <span class="font-semibold text-gray-900">{{ $payment->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Edit Guidelines Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">📋 Panduan Edit</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Yang Dapat Diubah:</h4>
                            <ul class="space-y-2">
                                <li class="flex items-center text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Jumlah pembayaran
                                </li>
                                <li class="flex items-center text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Foto bukti pembayaran
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Yang Tidak Dapat Diubah:</h4>
                            <ul class="space-y-2">
                                <li class="flex items-center text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Booking yang dibayar
                                </li>
                                <li class="flex items-center text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Status pembayaran
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-sm border border-orange-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-orange-200">
                        <h3 class="text-lg font-semibold text-gray-900">💡 Tips Edit Pembayaran</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-orange-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-700">Pastikan jumlah sesuai dengan transfer yang dilakukan</p>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-orange-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-700">Upload bukti yang jelas dan mudah dibaca</p>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-orange-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-700">Edit hanya bisa saat status "Menunggu Verifikasi"</p>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-orange-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-700">Setelah edit, status kembali ke pending</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const proofImageInput = document.getElementById('proof_image');
    const newImagePreview = document.getElementById('new-image-preview');
    const newPreviewImg = document.getElementById('new-preview-img');

    // Handle file input change
    proofImageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Check file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('⚠️ Ukuran file terlalu besar! Maksimal 2MB.');
                this.value = '';
                newImagePreview.classList.add('hidden');
                return;
            }
            
            // Check file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('⚠️ Format file tidak didukung! Gunakan JPEG, JPG, atau PNG.');
                this.value = '';
                newImagePreview.classList.add('hidden');
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                newPreviewImg.src = e.target.result;
                newImagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            newImagePreview.classList.add('hidden');
        }
    });

    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const amount = parseInt(document.getElementById('amount').value);

        if (!amount || amount < 1) {
            e.preventDefault();
            alert('⚠️ Masukkan jumlah pembayaran yang valid!');
            document.getElementById('amount').focus();
            return;
        }

        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
    });

    // Image click to view
    document.querySelectorAll('[data-image-url]').forEach(img => {
        img.addEventListener('click', function() {
            const url = this.getAttribute('data-image-url');
            window.open(url, '_blank');
        });
    });
});
</script>
@endsection
