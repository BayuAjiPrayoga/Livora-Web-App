@extends('layouts.admin')

@section('title', 'Create Payment - LIVORA Admin')

@section('page-title', 'Create New Payment')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Create New Payment</h1>
                    <p class="text-sm text-gray-600 mt-1">Record a payment for an existing booking</p>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    Back to Payments
                </a>
            </div>

            <form action="{{ route('admin.payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Booking Selection -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-2">Booking *</label>
                        <select name="booking_id" id="booking_id" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('booking_id') border-red-500 @enderror">
                            <option value="">Select Booking</option>
                            @foreach(App\Models\Booking::with(['user', 'room.boardingHouse'])->get() as $booking)
                                <option value="{{ $booking->id }}" 
                                        data-amount="{{ $booking->final_amount }}"
                                        {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                                    #{{ $booking->id }} - {{ $booking->user->name }} - {{ $booking->room->boardingHouse->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('booking_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Payment Amount (Rp) *</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0" step="1000"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('amount') border-red-500 @enderror">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                        <select name="payment_method" id="payment_method" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('payment_method') border-red-500 @enderror">
                            <option value="">Select Method</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="e_wallet" {{ old('payment_method') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status *</label>
                        <select name="status" id="status" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="paid_at" class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                        <input type="datetime-local" name="paid_at" id="paid_at" value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('paid_at') border-red-500 @enderror">
                        @error('paid_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bank Transfer Details (conditional) -->
                <div id="bank_details" class="hidden grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                        <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                    </div>

                    <div>
                        <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                        <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                    </div>
                </div>

                <!-- E-Wallet Details (conditional) -->
                <div id="ewallet_details" class="hidden">
                    <label for="ewallet_number" class="block text-sm font-medium text-gray-700 mb-2">E-Wallet Number/ID</label>
                    <input type="text" name="ewallet_number" id="ewallet_number" value="{{ old('ewallet_number') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                </div>

                <!-- Transaction Reference -->
                <div>
                    <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">Transaction ID/Reference</label>
                    <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('transaction_id') border-red-500 @enderror">
                    @error('transaction_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Proof -->
                <div>
                    <label for="proof_image" class="block text-sm font-medium text-gray-700 mb-2">Payment Proof</label>
                    <input type="file" name="proof_image" id="proof_image" accept="image/*"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('proof_image') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Upload payment receipt or transfer proof (JPG, PNG, GIF). Maximum 5MB.</p>
                    @error('proof_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Payment Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Admin Verification Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Verification</h3>
                    
                    <div id="verification_section" class="space-y-4">
                        <div>
                            <label for="verified_by" class="block text-sm font-medium text-gray-700 mb-2">Verified By</label>
                            <input type="text" name="verified_by" id="verified_by" value="{{ Auth::user()->name }}" readonly
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>

                        <div>
                            <label for="verification_notes" class="block text-sm font-medium text-gray-700 mb-2">Verification Notes</label>
                            <textarea name="verification_notes" id="verification_notes" rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">{{ old('verification_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.payments.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Create Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingSelect = document.getElementById('booking_id');
    const amountInput = document.getElementById('amount');
    const paymentMethodSelect = document.getElementById('payment_method');
    const bankDetails = document.getElementById('bank_details');
    const ewalletDetails = document.getElementById('ewallet_details');
    const statusSelect = document.getElementById('status');
    const verificationSection = document.getElementById('verification_section');

    // Auto-fill amount when booking is selected
    bookingSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.dataset.amount) {
            amountInput.value = selectedOption.dataset.amount;
        }
    });

    // Show/hide payment method specific fields
    paymentMethodSelect.addEventListener('change', function() {
        const method = this.value;
        
        // Hide all conditional fields
        bankDetails.classList.add('hidden');
        ewalletDetails.classList.add('hidden');
        
        // Show relevant fields
        if (method === 'bank_transfer') {
            bankDetails.classList.remove('hidden');
            bankDetails.classList.add('grid');
        } else if (method === 'e_wallet') {
            ewalletDetails.classList.remove('hidden');
        }
    });

    // Show/hide verification section based on status
    statusSelect.addEventListener('change', function() {
        const status = this.value;
        if (status === 'verified' || status === 'rejected') {
            verificationSection.style.display = 'block';
        } else {
            verificationSection.style.display = 'none';
        }
    });

    // Image preview
    document.getElementById('proof_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const existingPreview = document.getElementById('proof-preview');
        
        if (existingPreview) {
            existingPreview.remove();
        }
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.id = 'proof-preview';
                preview.className = 'mt-4';
                preview.innerHTML = `
                    <p class="text-sm text-gray-600 mb-2">Payment Proof Preview:</p>
                    <img src="${e.target.result}" alt="Payment Proof Preview" class="w-64 h-auto border border-gray-300 rounded-lg">
                `;
                document.getElementById('proof_image').parentNode.appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    });

    // Auto-generate transaction ID if needed
    document.getElementById('transaction_id').addEventListener('focus', function() {
        if (!this.value) {
            const timestamp = new Date().getTime();
            this.value = 'TXN' + timestamp;
        }
    });

    // Initialize payment method fields on page load
    if (paymentMethodSelect.value) {
        paymentMethodSelect.dispatchEvent(new Event('change'));
    }
    
    // Initialize verification section on page load
    if (statusSelect.value) {
        statusSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection