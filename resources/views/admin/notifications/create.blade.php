@extends('layouts.admin')

@section('title', 'Send Notification')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Send Notification</h1>
            <a href="{{ route('admin.notifications.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                Back to Notifications
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="POST" action="{{ route('admin.notifications.store') }}" class="space-y-6">
                @csrf

                <!-- Recipient Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Type</label>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="relative">
                            <input type="radio" id="single" name="recipient_type" value="single" class="peer hidden" {{ old('recipient_type', 'single') === 'single' ? 'checked' : '' }}>
                            <label for="single" class="block p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-livora-primary peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-livora-primary/20 transition-all">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-600 peer-checked:text-livora-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Single User</span>
                                </div>
                            </label>
                        </div>
                        <div class="relative">
                            <input type="radio" id="role" name="recipient_type" value="role" class="peer hidden" {{ old('recipient_type') === 'role' ? 'checked' : '' }}>
                            <label for="role" class="block p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-livora-primary peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-livora-primary/20 transition-all">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-600 peer-checked:text-livora-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">By Role</span>
                                </div>
                            </label>
                        </div>
                        <div class="relative">
                            <input type="radio" id="all" name="recipient_type" value="all" class="peer hidden" {{ old('recipient_type') === 'all' ? 'checked' : '' }}>
                            <label for="all" class="block p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-livora-primary peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-livora-primary/20 transition-all">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-600 peer-checked:text-livora-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">All Users</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('recipient_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Single User Selection -->
                <div id="singleUserDiv" class="recipient-option {{ old('recipient_type', 'single') === 'single' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                    <select name="user_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent">
                        <option value="">Choose a user...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div id="roleDiv" class="recipient-option {{ old('recipient_type') === 'role' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                    <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent">
                        <option value="">Choose a role...</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Property Owner</option>
                        <option value="tenant" {{ old('role') === 'tenant' ? 'selected' : '' }}>Tenant</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notification Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent">
                            <option value="">Select type...</option>
                            <option value="announcement" {{ old('type') === 'announcement' ? 'selected' : '' }}>Announcement</option>
                            <option value="system_update" {{ old('type') === 'system_update' ? 'selected' : '' }}>System Update</option>
                            <option value="maintenance" {{ old('type') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="promotion" {{ old('type') === 'promotion' ? 'selected' : '' }}>Promotion</option>
                            <option value="reminder" {{ old('type') === 'reminder' ? 'selected' : '' }}>Reminder</option>
                            <option value="warning" {{ old('type') === 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="info" {{ old('type') === 'info' ? 'selected' : '' }}>Information</option>
                            <option value="custom" {{ old('type') === 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select name="priority" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent">
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent" placeholder="Enter notification title...">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message <span class="text-red-500">*</span></label>
                    <textarea name="message" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent" placeholder="Enter notification message...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Action URL (Optional)</label>
                    <input type="url" name="action_url" value="{{ old('action_url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-livora-primary focus:border-transparent" placeholder="https://...">
                    <p class="mt-1 text-sm text-gray-500">Optional URL that users can click to perform an action related to this notification.</p>
                    @error('action_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Preview</h3>
                    <div class="bg-white rounded-lg border p-4" id="notificationPreview">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="text-sm font-semibold text-gray-900" id="previewTitle">Enter a title...</h4>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800" id="previewPriority">Medium</span>
                                </div>
                                <p class="text-sm text-gray-700" id="previewMessage">Enter a message...</p>
                                <div class="mt-2 text-xs text-gray-500">Just now</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.notifications.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-livora-primary text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recipient type toggle
    const recipientRadios = document.querySelectorAll('input[name="recipient_type"]');
    const recipientOptions = document.querySelectorAll('.recipient-option');
    
    recipientRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            recipientOptions.forEach(option => {
                option.classList.add('hidden');
            });
            
            if (this.value === 'single') {
                document.getElementById('singleUserDiv').classList.remove('hidden');
            } else if (this.value === 'role') {
                document.getElementById('roleDiv').classList.remove('hidden');
            }
        });
    });

    // Live preview
    const titleInput = document.querySelector('input[name="title"]');
    const messageInput = document.querySelector('textarea[name="message"]');
    const prioritySelect = document.querySelector('select[name="priority"]');
    
    const previewTitle = document.getElementById('previewTitle');
    const previewMessage = document.getElementById('previewMessage');
    const previewPriority = document.getElementById('previewPriority');

    function updatePreview() {
        previewTitle.textContent = titleInput.value || 'Enter a title...';
        previewMessage.textContent = messageInput.value || 'Enter a message...';
        
        const priority = prioritySelect.value || 'medium';
        previewPriority.textContent = priority.charAt(0).toUpperCase() + priority.slice(1);
        
        // Update priority color
        const priorityColors = {
            'low': 'bg-green-100 text-green-800',
            'medium': 'bg-yellow-100 text-yellow-800',
            'high': 'bg-red-100 text-red-800'
        };
        previewPriority.className = `px-2 py-1 text-xs font-semibold rounded-full ${priorityColors[priority]}`;
    }

    titleInput.addEventListener('input', updatePreview);
    messageInput.addEventListener('input', updatePreview);
    prioritySelect.addEventListener('change', updatePreview);
    
    // Initial preview update
    updatePreview();
});
</script>
@endsection