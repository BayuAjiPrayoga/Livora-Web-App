@extends('layouts.mitra')

@section('title', 'Edit Booking - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('mitra.bookings.show', $booking) }}" 
                   class="text-livora-accent hover:text-livora-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-livora-text">Edit Booking</h1>
                    <p class="text-gray-600 mt-1">{{ $booking->booking_code }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('mitra.bookings.update', $booking) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Booking Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">Informasi Booking</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Booking</label>
                        <input type="text" value="{{ $booking->booking_code }}" readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Properti</label>
                        <input type="text" value="{{ $booking->boardingHouse->name }}" readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kamar</label>
                        <input type="text" value="{{ $booking->room->name }}" readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500">
                    </div>
                </div>
            </div>

            <!-- Periode Booking (conditionally editable) -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">Periode Booking</h3>
                
                @if($booking->canEditDates())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="booking_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Booking <span class="text-red-500">*</span></label>
                        <select name="booking_type" id="booking_type" required 
                                class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                            <option value="daily" {{ old('booking_type', $booking->booking_type) == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="monthly" {{ old('booking_type', $booking->booking_type) == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="yearly" {{ old('booking_type', $booking->booking_type) == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                        @error('booking_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="check_in_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Check-in <span class="text-red-500">*</span></label>
                        <input type="date" name="check_in_date" id="check_in_date" required
                               value="{{ old('check_in_date', $booking->check_in_date ? $booking->check_in_date->format('Y-m-d') : '') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('check_in_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="check_out_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Check-out <span class="text-red-500">*</span></label>
                        <input type="date" name="check_out_date" id="check_out_date" required
                               value="{{ old('check_out_date', $booking->check_out_date ? $booking->check_out_date->format('Y-m-d') : '') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('check_out_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div id="duration_info" class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <span class="font-medium">Durasi:</span>
                        <span id="duration_text">{{ $booking->duration_months }} bulan</span>
                    </p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Booking</label>
                        <input type="text" value="{{ $booking->getBookingTypeLabel() }}" readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-in</label>
                        <input type="text" value="{{ $booking->check_in_date->format('d M Y') }}" readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-out</label>
                        <input type="text" value="{{ $booking->check_out_date->format('d M Y') }}" readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500">
                    </div>
                </div>
                <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                    <p class="text-sm text-yellow-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Periode booking tidak dapat diubah karena status booking sudah {{ $booking->status_label }}
                    </p>
                </div>
                @endif
            </div>

            <!-- Data Penyewa -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">Data Penyewa</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tenant_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="tenant_name" id="tenant_name" required
                               value="{{ old('tenant_name', $booking->tenant_name) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('tenant_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tenant_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" name="tenant_phone" id="tenant_phone" required
                               value="{{ old('tenant_phone', $booking->tenant_phone) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('tenant_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tenant_email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="tenant_email" id="tenant_email" required
                               value="{{ old('tenant_email', $booking->tenant_email) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('tenant_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tenant_identity_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor KTP <span class="text-red-500">*</span></label>
                        <input type="text" name="tenant_identity_number" id="tenant_identity_number" required
                               value="{{ old('tenant_identity_number', $booking->tenant_identity_number) }}"
                               maxlength="16"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('tenant_identity_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="tenant_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="tenant_address" id="tenant_address" required rows="3"
                                  class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">{{ old('tenant_address', $booking->tenant_address) }}</textarea>
                        @error('tenant_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Kontak Darurat -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">Kontak Darurat</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kontak Darurat</label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name"
                               value="{{ old('emergency_contact_name', $booking->emergency_contact_name) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Kontak Darurat</label>
                        <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone"
                               value="{{ old('emergency_contact_phone', $booking->emergency_contact_phone) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('emergency_contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="emergency_contact_relation" class="block text-sm font-medium text-gray-700 mb-2">Hubungan</label>
                        <select name="emergency_contact_relation" id="emergency_contact_relation"
                                class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                            <option value="">Pilih hubungan</option>
                            <option value="Orang Tua" {{ old('emergency_contact_relation', $booking->emergency_contact_relation) == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                            <option value="Saudara" {{ old('emergency_contact_relation', $booking->emergency_contact_relation) == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                            <option value="Kerabat" {{ old('emergency_contact_relation', $booking->emergency_contact_relation) == 'Kerabat' ? 'selected' : '' }}>Kerabat</option>
                            <option value="Teman" {{ old('emergency_contact_relation', $booking->emergency_contact_relation) == 'Teman' ? 'selected' : '' }}>Teman</option>
                            <option value="Lainnya" {{ old('emergency_contact_relation', $booking->emergency_contact_relation) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('emergency_contact_relation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">Catatan</h3>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">{{ old('notes', $booking->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if($booking->canEditDates())
            <!-- Perhitungan Biaya -->
            <div class="bg-white rounded-lg shadow-md p-6" data-room-price="{{ $booking->room->price ?? 0 }}">
                <h3 class="text-lg font-semibold text-livora-text mb-4">Perhitungan Biaya Baru</h3>
                
                <div id="price_calculation">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga per periode:</span>
                            <span id="base_price">Rp {{ number_format($booking->room->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durasi:</span>
                            <span id="period_count">{{ $booking->duration_months }} bulan</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotal">Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Total Pembayaran:</span>
                            <span id="final_total" class="text-livora-accent">Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                        <p class="text-sm text-yellow-700">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Jika Anda mengubah tanggal, total pembayaran akan dihitung ulang otomatis
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Submit Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between">
                    <a href="{{ route('mitra.bookings.show', $booking) }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-livora-accent text-white rounded-lg hover:bg-livora-primary transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($booking->canEditDates())
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingTypeSelect = document.getElementById('booking_type');
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const durationText = document.getElementById('duration_text');
    
    const roomPrice = parseFloat(document.querySelector('[data-room-price]').dataset.roomPrice) || 0;

    // Update duration when dates change
    function updateDuration() {
        if (checkInDate.value && checkOutDate.value) {
            const checkIn = new Date(checkInDate.value);
            const checkOut = new Date(checkOutDate.value);
            const diffTime = checkOut - checkIn;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 0) {
                const months = Math.ceil(diffDays / 30);
                durationText.textContent = `${diffDays} hari (≈ ${months} bulan)`;
                
                updatePriceCalculation(diffDays);
            }
        }
    }

    function updatePriceCalculation(diffDays) {
        const bookingType = bookingTypeSelect.value;
        let periodCount = 0;
        let periodName = '';

        if (bookingType === 'daily') {
            periodCount = diffDays;
            periodName = 'hari';
        } else if (bookingType === 'monthly') {
            periodCount = Math.ceil(diffDays / 30);
            periodName = 'bulan';
        } else if (bookingType === 'yearly') {
            periodCount = Math.ceil(diffDays / 365);
            periodName = 'tahun';
        }

        const subtotal = roomPrice * periodCount;
        const finalTotal = subtotal;

        document.getElementById('period_count').textContent = `${periodCount} ${periodName}`;
        document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        document.getElementById('final_total').textContent = `Rp ${finalTotal.toLocaleString('id-ID')}`;
    }

    // Auto-update checkout date based on check-in and booking type
    function updateCheckOutDate() {
        if (checkInDate.value && bookingTypeSelect.value) {
            const checkIn = new Date(checkInDate.value);
            let checkOut = new Date(checkIn);
            
            if (bookingTypeSelect.value === 'daily') {
                checkOut.setDate(checkOut.getDate() + 1);
            } else if (bookingTypeSelect.value === 'monthly') {
                checkOut.setMonth(checkOut.getMonth() + 1);
            } else if (bookingTypeSelect.value === 'yearly') {
                checkOut.setFullYear(checkOut.getFullYear() + 1);
            }
            
            checkOutDate.value = checkOut.toISOString().split('T')[0];
            updateDuration();
        }
    }

    checkInDate.addEventListener('change', updateDuration);
    checkOutDate.addEventListener('change', updateDuration);
    bookingTypeSelect.addEventListener('change', updateCheckOutDate);
    checkInDate.addEventListener('change', updateCheckOutDate);

    // Validate KTP number (16 digits)
    document.getElementById('tenant_identity_number').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 16);
    });
});
</script>
@endpush
@endif
@endsection