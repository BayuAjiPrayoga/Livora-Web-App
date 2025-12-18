@extends('layouts.admin')

@section('title', 'Create Ticket - LIVORA Admin')

@section('page-title', 'Create New Ticket')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Create New Ticket</h1>
                    <p class="text-sm text-gray-600 mt-1">Create a support ticket on behalf of a user</p>
                </div>
                <a href="{{ route('admin.tickets.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    Back to Tickets
                </a>
            </div>

            <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- User and Basic Info -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User *</label>
                        <select name="user_id" id="user_id" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('user_id') border-red-500 @enderror">
                            <option value="">Select User</option>
                            @foreach(App\Models\User::whereIn('role', ['tenant', 'owner'])->get() as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->role }}) - {{ $user->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category" id="category" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            <option value="booking" {{ old('category') == 'booking' ? 'selected' : '' }}>Booking Issues</option>
                            <option value="payment" {{ old('category') == 'payment' ? 'selected' : '' }}>Payment Issues</option>
                            <option value="property" {{ old('category') == 'property' ? 'selected' : '' }}>Property Issues</option>
                            <option value="account" {{ old('category') == 'account' ? 'selected' : '' }}>Account Issues</option>
                            <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical Issues</option>
                            <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                            <option value="complaint" {{ old('category') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Priority and Status -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select name="priority" id="priority" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('priority') border-red-500 @enderror">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('status') border-red-500 @enderror">
                            <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                        <select name="assigned_to" id="assigned_to"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('assigned_to') border-red-500 @enderror">
                            <option value="">Unassigned</option>
                            @foreach(App\Models\User::where('role', 'admin')->get() as $admin)
                                <option value="{{ $admin->id }}" {{ old('assigned_to') == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Related Booking (optional) -->
                <div>
                    <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-2">Related Booking (Optional)</label>
                    <select name="booking_id" id="booking_id"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('booking_id') border-red-500 @enderror">
                        <option value="">No related booking</option>
                        @foreach(App\Models\Booking::with(['user', 'room.boardingHouse'])->latest()->take(50)->get() as $booking)
                            <option value="{{ $booking->id }}" {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                                #{{ $booking->id }} - {{ $booking->user->name }} - {{ $booking->room->boardingHouse->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('booking_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('subject') border-red-500 @enderror">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="6" required
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Attachments -->
                <div>
                    <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Attachments</label>
                    <input type="file" name="attachments[]" id="attachments" multiple accept="image/*,.pdf,.doc,.docx,.txt"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('attachments') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Upload relevant files (images, documents). Maximum 5MB per file.</p>
                    @error('attachments')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Admin Notes -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Notes</h3>
                    
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="4"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('admin_notes') border-red-500 @enderror">{{ old('admin_notes') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Internal notes visible only to admin staff.</p>
                        @error('admin_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Due Date (optional) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date (Optional)</label>
                        <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('due_date') border-red-500 @enderror">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="send_notification" value="1" {{ old('send_notification', 1) ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#ff6900] focus:ring-livora-primary border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Send notification to user</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.tickets.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const subjectInput = document.getElementById('subject');
    const bookingSelect = document.getElementById('booking_id');
    const userSelect = document.getElementById('user_id');

    // Auto-suggest subjects based on category
    const categorySubjects = {
        'booking': 'Booking Issue - ',
        'payment': 'Payment Issue - ',
        'property': 'Property Issue - ',
        'account': 'Account Issue - ',
        'technical': 'Technical Issue - ',
        'general': 'General Inquiry - ',
        'complaint': 'Complaint - ',
        'other': 'Support Request - '
    };

    categorySelect.addEventListener('change', function() {
        const category = this.value;
        if (category && categorySubjects[category] && !subjectInput.value) {
            subjectInput.value = categorySubjects[category];
            subjectInput.focus();
            subjectInput.setSelectionRange(subjectInput.value.length, subjectInput.value.length);
        }
    });

    // Filter bookings by user when user changes
    userSelect.addEventListener('change', function() {
        const userId = this.value;
        if (userId) {
            // You would typically make an AJAX call here to filter bookings
            // For now, we'll just reset the booking selection
            bookingSelect.value = '';
        }
    });

    // File upload preview
    document.getElementById('attachments').addEventListener('change', function(e) {
        const files = e.target.files;
        let existingPreview = document.getElementById('file-preview');
        
        if (existingPreview) {
            existingPreview.remove();
        }
        
        if (files.length > 0) {
            const preview = document.createElement('div');
            preview.id = 'file-preview';
            preview.className = 'mt-4 space-y-2';
            
            const title = document.createElement('p');
            title.className = 'text-sm font-medium text-gray-700';
            title.textContent = 'Selected Files:';
            preview.appendChild(title);
            
            Array.from(files).forEach(file => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center text-sm text-gray-600';
                
                const icon = file.type.startsWith('image/') ? '📷' : '📄';
                fileItem.innerHTML = `
                    <span class="mr-2">${icon}</span>
                    <span>${file.name}</span>
                    <span class="ml-auto text-gray-400">(${(file.size / 1024).toFixed(1)} KB)</span>
                `;
                
                preview.appendChild(fileItem);
            });
            
            e.target.parentNode.appendChild(preview);
        }
    });

    // Auto-assign based on category
    categorySelect.addEventListener('change', function() {
        const category = this.value;
        const assignedToSelect = document.getElementById('assigned_to');
        
        // Auto-select current admin as assignee if no one is assigned
        if (category && !assignedToSelect.value) {
            // You could implement logic to auto-assign based on category
            // For example, assign technical issues to specific admin
        }
    });

    // Priority color coding
    const prioritySelect = document.getElementById('priority');
    prioritySelect.addEventListener('change', function() {
        const priority = this.value;
        const colors = {
            'low': 'border-green-300 bg-green-50',
            'medium': 'border-yellow-300 bg-yellow-50',
            'high': 'border-orange-300 bg-orange-50',
            'urgent': 'border-red-300 bg-red-50'
        };
        
        // Reset classes
        this.className = 'block w-full px-3 py-2 border rounded-lg focus:ring-livora-primary focus:border-[#ff6900]';
        
        // Add priority-specific classes
        if (colors[priority]) {
            this.className += ' ' + colors[priority];
        } else {
            this.className += ' border-gray-300';
        }
    });

    // Initialize priority colors
    prioritySelect.dispatchEvent(new Event('change'));
});
</script>
@endpush
@endsection