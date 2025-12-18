@extends('layouts.tenant')

@section('title', 'Buat Pembayaran - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Buat Pembayaran Baru
                    </h1>
                    <p class="text-gray-600 mt-1">Upload bukti pembayaran untuk booking Anda</p>
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
                        <h3 class="text-lg font-semibold text-gray-900">Formulir Pembayaran</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('tenant.payments.store') }}" method="POST" enctype="multipart/form-data" id="paymentForm" class="space-y-6">
                            @csrf
                            
                            <!-- Booking Selection -->
                            <div>
                                <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Booking yang Akan Dibayar <span class="text-red-500">*</span>
                                </label>
                                <select name="booking_id" id="booking_id" 
                                        class="w-full rounded-lg border-gray-300 focus:border-livora-primary focus:ring-livora-primary @error('booking_id') border-red-300 @enderror" 
                                        required>
                                    <option value="">-- Pilih Booking --</option>
                                    @foreach($availableBookings as $booking)
                                        <option value="{{ $booking->id }}" 
                                                data-total-price="{{ $booking->total_price }}"
                                                {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                                            #{{ $booking->id }} - {{ $booking->room->name ?? 'N/A' }} 
                                            ({{ $booking->room->boardingHouse->name ?? 'N/A' }}) 
                                            - Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Pilih booking yang ingin Anda bayar. Hanya booking yang confirmed dan belum dibayar yang muncul di sini.</p>
                            </div>

                            <!-- Payment Amount -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="number" 
                                           name="amount" 
                                           id="amount" 
                                           class="w-full pl-8 pr-4 py-2 rounded-lg border-gray-300 focus:border-livora-primary focus:ring-livora-primary @error('amount') border-red-300 @enderror"
                                           placeholder="Masukkan jumlah pembayaran"
                                           min="1"
                                           step="1"
                                           value="{{ old('amount') }}"
                                           required>
                                </div>
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            <div class="form-text">
                                <span id="booking-total-info" class="text-muted"></span>
                            </div>
                        </div>

                        <!-- Payment Proof -->
                        <div class="mb-4">
                            <label for="proof_image" class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                            <input type="file" 
                                   name="proof_image" 
                                   id="proof_image" 
                                   class="form-control @error('proof_image') is-invalid @enderror"
                                   accept="image/jpeg,image/jpg,image/png"
                                   required>
                            @error('proof_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Upload foto bukti pembayaran (JPEG, JPG, PNG). Maksimal ukuran file 2MB.
                            </div>
                            
                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-3" style="display: none;">
                                <img id="preview-img" src="" alt="Preview" class="img-fluid rounded shadow" style="max-height: 200px;">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-paper-plane"></i> Submit Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Payment Instructions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">📋 Petunjuk Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-sm font-weight-bold">1. Lakukan Pembayaran</h6>
                        <p class="text-xs text-secondary mb-0">
                            Transfer sesuai jumlah yang tertera ke rekening yang telah diberikan pemilik kost.
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-sm font-weight-bold">2. Ambil Foto Bukti</h6>
                        <p class="text-xs text-secondary mb-0">
                            Foto struk transfer atau screenshot bukti pembayaran dengan jelas.
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-sm font-weight-bold">3. Upload Bukti</h6>
                        <p class="text-xs text-secondary mb-0">
                            Pilih booking, masukkan jumlah yang dibayar, dan upload foto bukti pembayaran.
                        </p>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-sm font-weight-bold">4. Tunggu Verifikasi</h6>
                        <p class="text-xs text-secondary mb-0">
                            Pembayaran akan diverifikasi oleh pemilik kost dalam 1x24 jam.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Selected Booking Info -->
            <div class="card" id="booking-info-card" style="display: none;">
                <div class="card-header">
                    <h6 class="mb-0">📍 Info Booking Terpilih</h6>
                </div>
                <div class="card-body" id="booking-info-content">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>

            <!-- Tips -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">💡 Tips Upload Bukti</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="text-sm">Pastikan foto tidak blur dan dapat dibaca</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="text-sm">Sertakan informasi tanggal dan waktu transfer</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="text-sm">Pastikan jumlah yang tertera jelas terlihat</span>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="text-sm">Upload dalam format JPG, JPEG, atau PNG</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingSelect = document.getElementById('booking_id');
    const amountInput = document.getElementById('amount');
    const bookingInfoCard = document.getElementById('booking-info-card');
    const bookingInfoContent = document.getElementById('booking-info-content');
    const bookingTotalInfo = document.getElementById('booking-total-info');
    const proofImageInput = document.getElementById('proof_image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    // Handle booking selection
    bookingSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const totalPrice = parseFloat(selectedOption.dataset.totalPrice);
            
            // Set amount to booking total price (as integer)
            amountInput.value = Math.round(totalPrice);
            
            // Show booking total info
            bookingTotalInfo.textContent = `Total harga booking: Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;
            bookingTotalInfo.className = 'text-info font-weight-bold';
            
            // Show booking info card
            bookingInfoCard.style.display = 'block';
            
            // Get booking details (you might need to fetch this via AJAX in a real application)
            const bookingText = selectedOption.textContent;
            bookingInfoContent.innerHTML = `
                <div class="mb-2">
                    <small class="text-muted">Booking ID</small>
                    <div class="font-weight-bold">${bookingText.split(' - ')[0]}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Total Harga</small>
                    <div class="font-weight-bold text-primary">Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}</div>
                </div>
                <div class="alert alert-info alert-sm mb-0">
                    <small>Pastikan jumlah pembayaran sesuai dengan total harga booking.</small>
                </div>
            `;
        } else {
            // Hide booking info
            bookingInfoCard.style.display = 'none';
            bookingTotalInfo.textContent = '';
            amountInput.value = '';
        }
    });

    // Handle image preview
    proofImageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Check file size (2MB = 2 * 1024 * 1024 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                this.value = '';
                imagePreview.style.display = 'none';
                return;
            }
            
            // Check file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPEG, JPG, atau PNG.');
                this.value = '';
                imagePreview.style.display = 'none';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });

    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const bookingId = bookingSelect.value;
        const amount = parseInt(amountInput.value);
        const proofImage = proofImageInput.files[0];

        if (!bookingId) {
            e.preventDefault();
            alert('Silakan pilih booking yang akan dibayar.');
            bookingSelect.focus();
            return;
        }

        if (!amount || amount < 1) {
            e.preventDefault();
            alert('Masukkan jumlah pembayaran yang valid.');
            amountInput.focus();
            return;
        }

        if (!proofImage) {
            e.preventDefault();
            alert('Silakan upload bukti pembayaran.');
            proofImageInput.focus();
            return;
        }

        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    });
});
</script>
@endpush
@endsection