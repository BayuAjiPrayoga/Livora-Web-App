@extends('layouts.tenant')

@section('title', 'Create Booking - LIVORA')

@section('page-title', 'Book a Room')

@section('content')
<div class="p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Book a Room</h1>
                <p class="text-sm text-gray-600 mt-1">Find and book the perfect room for your stay</p>
            </div>
            <a href="{{ route('tenant.bookings.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                My Bookings
            </a>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Search Properties</h2>
            <form id="search-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" id="city" name="city" placeholder="Enter city name"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                </div>

                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">Max Price (Monthly)</label>
                    <select id="max_price" name="max_price"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                        <option value="">Any price</option>
                        <option value="500000">Up to Rp 500K</option>
                        <option value="1000000">Up to Rp 1M</option>
                        <option value="1500000">Up to Rp 1.5M</option>
                        <option value="2000000">Up to Rp 2M</option>
                        <option value="3000000">Up to Rp 3M</option>
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
                    <input type="date" id="start_date" name="start_date" min="{{ date('Y-m-d') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                </div>

                <div class="flex items-end">
                    <button type="button" id="search-btn" class="w-full bg-[#ff6900] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Search Properties
                    </button>
                </div>
            </form>
        </div>

        <!-- Available Properties -->
        <div id="properties-grid" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
            @if(isset($boardingHouses) && $boardingHouses->count() > 0)
                @foreach($boardingHouses as $property)
                    @include('tenant.bookings.partials.property-card', ['property' => $property])
                @endforeach
            @else
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No properties found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria or browse all available properties.</p>
                        <div class="mt-6">
                            <button type="button" id="show-all-btn" class="bg-[#ff6900] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                Show All Properties
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Booking Form Modal -->
        <div id="booking-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full relative z-10">
                    <form action="{{ route('tenant.bookings.store') }}" method="POST" id="booking-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="room_id" id="room_id">
                        
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Book Room</h3>
                                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <h4 id="modal-room-name" class="font-semibold text-gray-900"></h4>
                                <p id="modal-property-name" class="text-sm text-gray-600"></p>
                                <p id="modal-location" class="text-sm text-gray-500"></p>
                                <p id="modal-price" class="text-lg font-bold text-blue-600 mt-2"></p>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KTP <span class="text-red-500">*</span></label>
                                    <input type="text" name="tenant_identity_number" id="tenant_identity_number" required 
                                           maxlength="16" placeholder="Masukkan 16 digit nomor KTP"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-xs text-gray-500">Masukkan 16 digit nomor KTP Anda</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Foto KTP <span class="text-red-500">*</span></label>
                                    <input type="file" name="ktp_image" id="ktp_image" required accept="image/*"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-500">Upload foto KTP dalam format JPG, PNG, atau JPEG (max 2MB)</p>
                                    <div id="ktp_preview" class="mt-2 hidden">
                                        <img id="ktp_preview_img" src="" alt="KTP Preview" class="max-h-40 rounded-lg border">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date *</label>
                                    <input type="date" name="start_date" id="booking_start_date" required 
                                           min="{{ date('Y-m-d') }}"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration (months) *</label>
                                    <select name="duration" id="booking_duration" required
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                        <option value="1">1 Month</option>
                                        <option value="3">3 Months</option>
                                        <option value="6">6 Months</option>
                                        <option value="12">12 Months</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                    <textarea name="notes" rows="3" 
                                              placeholder="Special requests or notes..."
                                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Submit Booking
                            </button>
                            <button type="button" onclick="closeModal()"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900" id="modal-title">Book This Room</h3>
                                <button type="button" id="close-modal" class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Selected Property Info -->
                            <div id="selected-property" class="bg-gray-50 rounded-lg p-4 mb-6">
                                <!-- Property details will be populated here -->
                            </div>

                            <input type="hidden" name="room_id" id="selected-room-id">
                            <input type="hidden" name="duration_type" value="monthly">

                            <!-- Booking Details -->
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="booking_start_date" class="block text-sm font-medium text-gray-700 mb-2">Check-in Date *</label>
                                        <input type="date" name="start_date" id="booking_start_date" required min="{{ date('Y-m-d') }}"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                                    </div>

                                    <div>
                                        <label for="booking_end_date" class="block text-sm font-medium text-gray-700 mb-2">Check-out Date *</label>
                                        <input type="date" name="end_date" id="booking_end_date" required
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                                    </div>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Special Requests (Optional)</label>
                                    <textarea name="notes" id="notes" rows="3"
                                              placeholder="Any special requests or notes for the property owner..."
                                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]"></textarea>
                                </div>

                                <!-- Price Calculation -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Price Calculation</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span>Room price per month:</span>
                                            <span id="room-price">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Duration:</span>
                                            <span id="duration">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Subtotal:</span>
                                            <span id="subtotal">-</span>
                                        </div>
                                        <div class="flex justify-between text-sm text-gray-600">
                                            <span>Administrative fee:</span>
                                            <span id="admin-fee">Rp 50,000</span>
                                        </div>
                                        <div class="border-t border-gray-200 pt-2 flex justify-between font-medium">
                                            <span>Total Amount:</span>
                                            <span id="total-amount" class="text-[#ff6900]">-</span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#ff6900] text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-livora-primary sm:ml-3 sm:w-auto sm:text-sm">
                                Confirm Booking
                            </button>
                            <button type="button" id="cancel-booking" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-livora-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up handlers...');
    
    // Modal elements
    const modal = document.getElementById('booking-modal');
    const modalRoomName = document.getElementById('modal-room-name');
    const modalPropertyName = document.getElementById('modal-property-name');
    const modalLocation = document.getElementById('modal-location');
    const modalPrice = document.getElementById('modal-price');
    const roomIdInput = document.getElementById('room_id');
    const bookingForm = document.getElementById('booking-form');
    
    console.log('Modal element:', modal);
    console.log('All book buttons:', document.querySelectorAll('.book-room-btn'));
    
    // Handle all book button clicks with event delegation
    document.body.addEventListener('click', function(e) {
        console.log('Body clicked:', e.target);
        const btn = e.target.closest('.book-room-btn');
        console.log('Closest book-room-btn:', btn);
        if (!btn) return;
        
        console.log('Book button clicked via event delegation!');
        e.preventDefault();
        e.stopPropagation();
        
        // Get data from button
        const roomId = btn.getAttribute('data-room-id');
        const roomName = btn.getAttribute('data-room-name');
        const propertyName = btn.getAttribute('data-property-name');
        const location = btn.getAttribute('data-location');
        const price = btn.getAttribute('data-price');
        
        console.log('Room data:', { roomId, roomName, propertyName, location, price });
        
        // Update modal
        if (modalRoomName) modalRoomName.textContent = roomName;
        if (modalPropertyName) modalPropertyName.textContent = propertyName;
        if (modalLocation) modalLocation.textContent = location;
        if (modalPrice) modalPrice.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price) + '/month';
        if (roomIdInput) roomIdInput.value = roomId;
        
        // Show modal
        if (modal) {
            console.log('Showing modal...');
            modal.classList.remove('hidden');
        } else {
            console.error('Modal not found!');
        }
    });
    
    // Close modal function
    window.closeModal = function() {
        console.log('Closing modal...');
        if (modal) modal.classList.add('hidden');
        if (bookingForm) bookingForm.reset();
        resetPriceCalculation();
    };
    
    // Close on backdrop click
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                window.closeModal();
            }
        });
    }
    
    // Price calculation
    let currentRoomPrice = 0;
    const ADMIN_FEE = 50000;
    
    // Update price calculation when room is selected
    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.book-room-btn');
        if (btn) {
            const price = parseFloat(btn.getAttribute('data-price'));
            currentRoomPrice = price;
            document.getElementById('room-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            calculatePrice();
        }
    });
    
    // Update calculation when dates or duration change
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const durationSelect = document.getElementById('duration');
    
    if (checkInDate) {
        checkInDate.addEventListener('change', calculatePrice);
    }
    
    if (checkOutDate) {
        checkOutDate.addEventListener('change', calculatePrice);
    }
    
    if (durationSelect) {
        durationSelect.addEventListener('change', function() {
            // Auto-calculate check-out date based on duration
            if (checkInDate.value && durationSelect.value) {
                const startDate = new Date(checkInDate.value);
                const months = parseInt(durationSelect.value);
                const endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + months);
                
                // Format to YYYY-MM-DD
                const formattedDate = endDate.toISOString().split('T')[0];
                if (checkOutDate) {
                    checkOutDate.value = formattedDate;
                }
            }
            calculatePrice();
        });
    }
    
    function calculatePrice() {
        const duration = parseInt(durationSelect?.value || 0);
        
        if (currentRoomPrice > 0 && duration > 0) {
            const subtotal = currentRoomPrice * duration;
            const total = subtotal + ADMIN_FEE;
            
            document.getElementById('duration').textContent = duration + ' month' + (duration > 1 ? 's' : '');
            document.getElementById('subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            document.getElementById('total-amount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        } else {
            resetPriceCalculation();
        }
    }
    
    function resetPriceCalculation() {
        document.getElementById('room-price').textContent = '-';
        document.getElementById('duration').textContent = '-';
        document.getElementById('subtotal').textContent = '-';
        document.getElementById('total-amount').textContent = '-';
        currentRoomPrice = 0;
    }
    
    console.log('All handlers set up successfully');
    
    // KTP image preview
    const ktpInput = document.getElementById('ktp_image');
    const ktpPreview = document.getElementById('ktp_preview');
    const ktpPreviewImg = document.getElementById('ktp_preview_img');
    
    if (ktpInput) {
        ktpInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB');
                    e.target.value = '';
                    ktpPreview.classList.add('hidden');
                    return;
                }
                
                // Check file type
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar!');
                    e.target.value = '';
                    ktpPreview.classList.add('hidden');
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    ktpPreviewImg.src = e.target.result;
                    ktpPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                ktpPreview.classList.add('hidden');
            }
        });
    }
});
</script>

<!-- Simple Booking Modal -->
<div id="booking-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b">
            <h3 class="text-lg font-medium text-gray-900">Book Room</h3>
            <button id="close-modal-btn" type="button" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="pt-4">
            <!-- Room Info -->
            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                <h4 id="modal-room-name" class="font-semibold text-gray-900">Room Name</h4>
                <p id="modal-property-name" class="text-sm text-gray-600">Property Name</p>
                <p id="modal-location" class="text-sm text-gray-500">Location</p>
                <p id="modal-price" class="text-lg font-bold text-blue-600 mt-2">Price</p>
            </div>
            
            <!-- Booking Form -->
            <form id="booking-form" action="{{ route('tenant.bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" id="room_id" name="room_id">
                
                <div class="space-y-4">
                    <div>
                        <label for="booking_start_date" class="block text-sm font-medium text-gray-700 mb-1">Check-in Date *</label>
                        <input type="date" name="start_date" id="booking_start_date" required min="{{ date('Y-m-d') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="booking_duration" class="block text-sm font-medium text-gray-700 mb-1">Duration (months) *</label>
                        <select name="duration" id="booking_duration" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">1 Month</option>
                            <option value="3">3 Months</option>
                            <option value="6">6 Months</option>
                            <option value="12">12 Months</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="booking_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea name="notes" id="booking_notes" rows="3" 
                                  placeholder="Special requests or notes..."
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeBookingModal()" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Submit Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endsection