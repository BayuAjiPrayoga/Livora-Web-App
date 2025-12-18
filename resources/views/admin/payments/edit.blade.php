@extends('layouts.admin')

@section('title', 'Edit Payment - LIVORA')

@section('page-title', 'Edit Payment')

@section('content')
<div class="p-6">
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-[#ff6900]">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Payments
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-900">Edit Payment #{{ $payment->payment_number ?? $payment->id }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Edit Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.payments.update', $payment) }}" id="payment-form">
                @csrf
                @method('PATCH')
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-medium text-gray-900">Payment Information</h3>
                        <p class="text-sm text-gray-500 mt-1">Update payment details and status</p>
                    </div>

                    <!-- Payment Basic Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Payment Amount (IDR)</label>
                            <input type="number" name="amount" id="amount" 
                                   value="{{ $payment->amount }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]" 
                                   required>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]" required>
                                <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $payment->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $payment->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="processing" {{ $payment->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                                <option value="Bank Transfer" {{ $payment->payment_method === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="E-Wallet" {{ $payment->payment_method === 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                <option value="Credit Card" {{ $payment->payment_method === 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="Cash" {{ $payment->payment_method === 'Cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                        </div>

                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                            <input type="datetime-local" name="payment_date" id="payment_date" 
                                   value="{{ $payment->payment_date ? $payment->payment_date->format('Y-m-d\TH:i') : '' }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                            <input type="text" name="reference_number" id="reference_number" 
                                   value="{{ $payment->reference_number }}" 
                                   placeholder="Enter transaction reference number"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                        </div>

                        <div>
                            <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-2">Bank Account</label>
                            <input type="text" name="bank_account" id="bank_account" 
                                   value="{{ $payment->bank_account }}" 
                                   placeholder="Enter bank account details"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Payment Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  placeholder="Add payment description or notes..."
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">{{ $payment->description }}</textarea>
                    </div>

                    <!-- Admin Notes -->
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="4" 
                                  placeholder="Internal admin notes about this payment..."
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">{{ $payment->admin_notes }}</textarea>
                    </div>

                    <!-- Verification Section (only show for approved/rejected status) -->
                    <div id="verification-section" class="space-y-4 {{ !in_array($payment->status, ['approved', 'rejected']) ? 'hidden' : '' }}">
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Verification Details</h4>
                        </div>

                        <div>
                            <label for="verification_notes" class="block text-sm font-medium text-gray-700 mb-2">Verification Notes</label>
                            <textarea name="verification_notes" id="verification_notes" rows="3" 
                                      placeholder="Add notes about the verification process..."
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">{{ $payment->verification_notes }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="verified_at" class="block text-sm font-medium text-gray-700 mb-2">Verification Date</label>
                                <input type="datetime-local" name="verified_at" id="verified_at" 
                                       value="{{ $payment->verified_at ? $payment->verified_at->format('Y-m-d\TH:i') : '' }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                            </div>

                            <div>
                                <label for="verified_by" class="block text-sm font-medium text-gray-700 mb-2">Verified By</label>
                                <select name="verified_by" id="verified_by" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                                    <option value="">Select Admin</option>
                                    @foreach($admins ?? [] as $admin)
                                        <option value="{{ $admin->id }}" {{ $payment->verified_by == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('admin.payments.show', $payment) }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Update Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Current Payment Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Details</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Payment ID:</span>
                        <span class="text-sm font-medium text-gray-900">#{{ $payment->payment_number ?? $payment->id }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Original Amount:</span>
                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->getOriginal('amount') ?? $payment->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Submitted:</span>
                        <span class="text-sm text-gray-900">{{ $payment->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Updated:</span>
                        <span class="text-sm text-gray-900">{{ $payment->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Tenant Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tenant Information</h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        @if($payment->tenant->avatar ?? false)
                            <img src="{{ asset('storage/' . $payment->tenant->avatar) }}" alt="{{ $payment->tenant->name }}" class="w-10 h-10 rounded-full">
                        @else
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $payment->tenant->name ?? 'Unknown Tenant' }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->tenant->email ?? 'No email' }}</p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500">Phone:</span>
                                <p class="text-gray-900">{{ $payment->tenant->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Total Payments:</span>
                                <p class="text-gray-900">{{ $payment->tenant->payments_count ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            @if($payment->booking)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Related Booking</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Booking #{{ $payment->booking->booking_number ?? $payment->booking->id }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->booking->room->property->name ?? 'Unknown Property' }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <div class="grid grid-cols-1 gap-3 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Room:</span>
                                <span class="text-gray-900">{{ $payment->booking->room->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Duration:</span>
                                <span class="text-gray-900">{{ $payment->booking->duration_months ?? 'N/A' }} months</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Amount:</span>
                                <span class="text-gray-900">Rp {{ number_format($payment->booking->final_amount ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.bookings.show', $payment->booking) }}" 
                       class="text-sm text-[#ff6900] hover:text-blue-700 font-medium">
                        View Booking →
                    </a>
                </div>
            </div>
            @endif

            <!-- Payment Proof -->
            @if($payment->proof_of_payment)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Proof</h3>
                <div class="text-center">
                    <img src="{{ asset('storage/' . $payment->proof_of_payment) }}" 
                         alt="Payment Proof" 
                         class="max-w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer"
                         onclick="openImageModal(this.src)">
                    <p class="text-sm text-gray-500 mt-2">Click to view full size</p>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($payment->status === 'pending')
                    <button onclick="quickApprove()" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                        Quick Approve
                    </button>
                    <button onclick="quickReject()" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                        Quick Reject
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.payments.show', $payment) }}" 
                       class="w-full block text-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        View Details
                    </a>
                    
                    <a href="mailto:{{ $payment->tenant->email ?? '' }}" 
                       class="w-full block text-center bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                        Email Tenant
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Payment Proof</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-4">
            <img id="modalImage" src="" alt="Payment Proof" class="max-w-full h-auto">
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide verification section based on status
        const statusSelect = document.getElementById('status');
        const verificationSection = document.getElementById('verification-section');
        
        statusSelect.addEventListener('change', function() {
            if (this.value === 'approved' || this.value === 'rejected') {
                verificationSection.classList.remove('hidden');
                // Auto-set verification date to now if not already set
                const verifiedAtInput = document.getElementById('verified_at');
                if (!verifiedAtInput.value) {
                    verifiedAtInput.value = new Date().toISOString().slice(0, 16);
                }
            } else {
                verificationSection.classList.add('hidden');
            }
        });
    });

    function quickApprove() {
        if (confirm('Quickly approve this payment?')) {
            document.getElementById('status').value = 'approved';
            document.getElementById('verified_at').value = new Date().toISOString().slice(0, 16);
            document.getElementById('verification_notes').value = 'Approved via quick action';
            document.getElementById('verification-section').classList.remove('hidden');
        }
    }

    function quickReject() {
        const reason = prompt('Please provide a reason for rejecting this payment:');
        if (reason) {
            document.getElementById('status').value = 'rejected';
            document.getElementById('verified_at').value = new Date().toISOString().slice(0, 16);
            document.getElementById('verification_notes').value = reason;
            document.getElementById('verification-section').classList.remove('hidden');
        }
    }

    function openImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Format amount input
    document.getElementById('amount').addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = value;
    });
</script>
@endpush
@endsection