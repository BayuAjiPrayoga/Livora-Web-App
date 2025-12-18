@extends('layouts.mitra')

@section('title', 'Buat Booking Baru - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('mitra.bookings.index') }}" 
                   class="text-livora-accent hover:text-livora-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-livora-text">Buat Booking Baru</h1>
                    <p class="text-gray-600 mt-1">Tambahkan booking baru untuk properti Anda</p>
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

        <form id="booking-form" action="{{ route('mitra.bookings.store') }}" method="POST" class="space-y-6" 
              data-rooms="{{ base64_encode(json_encode($allRooms ?? [])) }}">
            @csrf
            
            <!-- Step 1: Properti & Kamar -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">1. Pilih Properti & Kamar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="boarding_house_id" class="block text-sm font-medium text-gray-700 mb-2">Properti <span class="text-red-500">*</span></label>
                        <select name="boarding_house_id" id="boarding_house_id" required 
                                class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                            <option value="">Pilih Properti</option>
                            @foreach($boardingHouses as $property)
                                <option value="{{ $property->id }}" {{ old('boarding_house_id') == $property->id ? 'selected' : '' }}>
                                    {{ $property->name }} - {{ $property->address }}
                                </option>
                            @endforeach
                        </select>
                        @error('boarding_house_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">Kamar <span class="text-red-500">*</span></label>
                        <select name="room_id" id="room_id" required 
                                class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                            <option value="">Pilih properti terlebih dahulu</option>
                        </select>
                        <div id="room_info" class="mt-2 p-3 bg-gray-50 rounded-lg hidden">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium">Harga:</span>
                                    <span id="room_price">-</span>
                                </div>
                                <div>
                                    <span class="font-medium">Kapasitas:</span>
                                    <span id="room_capacity">-</span>
                                </div>
                                <div>
                                    <span class="font-medium">Fasilitas:</span>
                                    <span id="room_facilities">-</span>
                                </div>
                                <div>
                                    <span class="font-medium">Status:</span>
                                    <span id="room_availability">-</span>
                                </div>
                            </div>
                        </div>
                        @error('room_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 2: Tipe & Periode Booking -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">2. Tipe & Periode Booking</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="booking_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Booking <span class="text-red-500">*</span></label>
                        <select name="booking_type" id="booking_type" required 
                                class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                            <option value="">Pilih Tipe Booking</option>
                            <option value="daily" {{ old('booking_type') == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="monthly" {{ old('booking_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="yearly" {{ old('booking_type') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                        @error('booking_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="check_in_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Check-in <span class="text-red-500">*</span></label>
                        <input type="date" name="check_in_date" id="check_in_date" required
                               value="{{ old('check_in_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('check_in_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="check_out_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Check-out <span class="text-red-500">*</span></label>
                        <input type="date" name="check_out_date" id="check_out_date" required
                               value="{{ old('check_out_date') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('check_out_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div id="duration_info" class="mt-4 p-3 bg-blue-50 rounded-lg hidden">
                    <p class="text-sm text-blue-700">
                        <span class="font-medium">Durasi:</span>
                        <span id="duration_text">-</span>
                    </p>
                </div>
            </div>

            <!-- Step 3: Data Penyewa -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">3. Data Penyewa</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tenant_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="tenant_name" id="tenant_name" required
                               value="{{ old('tenant_name') }}"
                               placeholder="Masukkan nama lengkap penyewa"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('tenant_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tenant_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" name="tenant_phone" id="tenant_phone" required
                               value="{{ old('tenant_phone') }}"
                               placeholder="Contoh: 08123456789"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('tenant_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tenant_email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="tenant_email" id="tenant_email" required
                               value="{{ old('tenant_email') }}"
                               placeholder="contoh@email.com"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('tenant_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tenant_identity_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor KTP <span class="text-red-500">*</span></label>
                        <input type="text" name="tenant_identity_number" id="tenant_identity_number" required
                               value="{{ old('tenant_identity_number') }}"
                               placeholder="Masukkan 16 digit nomor KTP"
                               maxlength="16"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('tenant_identity_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="tenant_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="tenant_address" id="tenant_address" required rows="3"
                                  placeholder="Masukkan alamat lengkap penyewa"
                                  class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">{{ old('tenant_address') }}</textarea>
                        @error('tenant_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 4: Kontak Darurat -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">4. Kontak Darurat</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kontak Darurat</label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name"
                               value="{{ old('emergency_contact_name') }}"
                               placeholder="Nama keluarga/kerabat terdekat"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Kontak Darurat</label>
                        <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone"
                               value="{{ old('emergency_contact_phone') }}"
                               placeholder="Contoh: 08123456789"
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
                            <option value="Orang Tua" {{ old('emergency_contact_relation') == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                            <option value="Saudara" {{ old('emergency_contact_relation') == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                            <option value="Kerabat" {{ old('emergency_contact_relation') == 'Kerabat' ? 'selected' : '' }}>Kerabat</option>
                            <option value="Teman" {{ old('emergency_contact_relation') == 'Teman' ? 'selected' : '' }}>Teman</option>
                            <option value="Lainnya" {{ old('emergency_contact_relation') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('emergency_contact_relation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 5: Perhitungan Biaya -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">5. Perhitungan Biaya</h3>
                
                <div id="price_calculation" class="hidden">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga per periode:</span>
                            <span id="base_price">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durasi:</span>
                            <span id="period_count">0 periode</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotal">Rp 0</span>
                        </div>
                        <hr>
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Total Pembayaran:</span>
                            <span id="final_total" class="text-livora-accent">Rp 0</span>
                        </div>
                    </div>
                </div>
                
                <div id="price_placeholder" class="text-center text-gray-500 py-8">
                    Pilih kamar dan tanggal untuk melihat perhitungan biaya
                </div>
            </div>

            <!-- Step 6: Catatan -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-livora-text mb-4">6. Catatan (Opsional)</h3>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="notes" id="notes" rows="4"
                              placeholder="Catatan khusus untuk booking ini (opsional)"
                              class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between">
                    <a href="{{ route('mitra.bookings.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-livora-accent text-white rounded-lg hover:bg-livora-primary transition-colors">
                        Simpan Booking
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add form submit debug logging
    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            // Log all form data
            const formData = new FormData(this);
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
        });
    }
    const boardingHouseSelect = document.getElementById('boarding_house_id');
    const roomSelect = document.getElementById('room_id');
    const roomInfo = document.getElementById('room_info');
    const bookingTypeSelect = document.getElementById('booking_type');
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const durationInfo = document.getElementById('duration_info');
    const durationText = document.getElementById('duration_text');
    const priceCalculation = document.getElementById('price_calculation');
    const pricePlaceholder = document.getElementById('price_placeholder');
    
    // All rooms data from server
    const allRooms = JSON.parse(atob(bookingForm.dataset.rooms || 'W10='));
    let roomData = null;

    console.log('All rooms loaded:', allRooms);
    console.log('Total rooms:', allRooms.length);

    // Load rooms when boarding house changes
    boardingHouseSelect.addEventListener('change', function() {
        const boardingHouseId = parseInt(this.value);
        console.log('Boarding house selected:', boardingHouseId);
        roomSelect.innerHTML = '<option value="">Memuat kamar...</option>';
        roomInfo.classList.add('hidden');
        
        if (boardingHouseId) {
            console.log('Filtering rooms for property:', boardingHouseId);
            
            // Filter rooms for selected property
            const propertyRooms = allRooms.filter(room => room.boarding_house_id === boardingHouseId);
            console.log('Filtered rooms:', propertyRooms);
            
            roomSelect.innerHTML = '<option value="">Pilih Kamar</option>';
            
            if (propertyRooms.length > 0) {
                propertyRooms.forEach(room => {
                    const availabilityText = room.is_available ? '' : ' (Tidak Tersedia)';
                    roomSelect.innerHTML += `<option value="${room.id}" data-room='${JSON.stringify(room)}'>
                        ${room.name} - Rp ${room.price_formatted}/bulan${availabilityText}
                    </option>`;
                });
            } else {
                roomSelect.innerHTML += '<option value="">Tidak ada kamar tersedia</option>';
            }
        } else {
            roomSelect.innerHTML = '<option value="">Pilih properti terlebih dahulu</option>';
        }
        
        updatePriceCalculation();
    });

    // Show room info when room changes
    roomSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.dataset.room) {
            roomData = JSON.parse(selectedOption.dataset.room);
            document.getElementById('room_price').textContent = roomData.price_formatted + '/bulan';
            document.getElementById('room_capacity').textContent = roomData.capacity + ' orang';
            document.getElementById('room_facilities').textContent = roomData.facilities || 'Tidak ada';
            document.getElementById('room_availability').innerHTML = roomData.is_available 
                ? '<span class="text-green-600">Tersedia</span>' 
                : '<span class="text-red-600">Tidak Tersedia</span>';
            roomInfo.classList.remove('hidden');
        } else {
            roomInfo.classList.add('hidden');
            roomData = null;
        }
        
        updatePriceCalculation();
    });

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
                durationInfo.classList.remove('hidden');
            } else {
                durationInfo.classList.add('hidden');
            }
        } else {
            durationInfo.classList.add('hidden');
        }
        
        updatePriceCalculation();
    }

    checkInDate.addEventListener('change', updateDuration);
    checkOutDate.addEventListener('change', updateDuration);
    bookingTypeSelect.addEventListener('change', updatePriceCalculation);

    function updatePriceCalculation() {
        if (!roomData || !checkInDate.value || !checkOutDate.value || !bookingTypeSelect.value) {
            priceCalculation.classList.add('hidden');
            pricePlaceholder.classList.remove('hidden');
            return;
        }

        const checkIn = new Date(checkInDate.value);
        const checkOut = new Date(checkOutDate.value);
        const diffTime = checkOut - checkIn;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays <= 0) {
            priceCalculation.classList.add('hidden');
            pricePlaceholder.classList.remove('hidden');
            return;
        }

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

        const basePrice = roomData.price;
        const subtotal = basePrice * periodCount;
        const finalTotal = subtotal;

        document.getElementById('base_price').textContent = `Rp ${basePrice.toLocaleString('id-ID')}`;
        document.getElementById('period_count').textContent = `${periodCount} ${periodName}`;
        document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        document.getElementById('final_total').textContent = `Rp ${finalTotal.toLocaleString('id-ID')}`;

        priceCalculation.classList.remove('hidden');
        pricePlaceholder.classList.add('hidden');
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

    bookingTypeSelect.addEventListener('change', updateCheckOutDate);
    checkInDate.addEventListener('change', updateCheckOutDate);

    // Validate KTP number (16 digits)
    document.getElementById('tenant_identity_number').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 16);
    });
});
</script>
@endpush
@endsection