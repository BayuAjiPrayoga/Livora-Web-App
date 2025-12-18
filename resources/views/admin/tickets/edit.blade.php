@extends('layouts.admin')

@section('title', 'Edit Ticket - LIVORA')

@section('page-title', 'Edit Ticket')

@section('content')
<div class="p-6">
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-[#ff6900]">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Tickets
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-900">Edit Ticket #{{ $ticket->ticket_number ?? $ticket->id }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Edit Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" id="ticket-form">
                @csrf
                @method('PATCH')
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-medium text-gray-900">Ticket Information</h3>
                        <p class="text-sm text-gray-500 mt-1">Update ticket details and assignment</p>
                    </div>

                    <!-- Basic Ticket Details -->
                    <div class="space-y-4">
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" name="subject" id="subject" 
                                   value="{{ $ticket->subject }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]" 
                                   required>
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="5" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]" 
                                      required>{{ $ticket->description }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status and Priority -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]" required>
                                <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <select name="priority" id="priority" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]" required>
                                <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" id="category" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                                <option value="General" {{ $ticket->category === 'General' ? 'selected' : '' }}>General</option>
                                <option value="Technical" {{ $ticket->category === 'Technical' ? 'selected' : '' }}>Technical</option>
                                <option value="Billing" {{ $ticket->category === 'Billing' ? 'selected' : '' }}>Billing</option>
                                <option value="Property" {{ $ticket->category === 'Property' ? 'selected' : '' }}>Property</option>
                                <option value="Account" {{ $ticket->category === 'Account' ? 'selected' : '' }}>Account</option>
                                <option value="Complaint" {{ $ticket->category === 'Complaint' ? 'selected' : '' }}>Complaint</option>
                            </select>
                        </div>
                    </div>

                    <!-- Assignment -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                            <select name="assigned_to" id="assigned_to" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                                <option value="">Unassigned</option>
                                @foreach($admins ?? [] as $admin)
                                    <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <select name="department" id="department" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                                <option value="Support" {{ $ticket->department === 'Support' ? 'selected' : '' }}>Support</option>
                                <option value="Technical" {{ $ticket->department === 'Technical' ? 'selected' : '' }}>Technical</option>
                                <option value="Billing" {{ $ticket->department === 'Billing' ? 'selected' : '' }}>Billing</option>
                                <option value="Management" {{ $ticket->department === 'Management' ? 'selected' : '' }}>Management</option>
                            </select>
                        </div>
                    </div>

                    <!-- Resolution Details (show only for resolved/closed tickets) -->
                    <div id="resolution-section" class="space-y-4 {{ !in_array($ticket->status, ['resolved', 'closed']) ? 'hidden' : '' }}">
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Resolution Details</h4>
                        </div>

                        <div>
                            <label for="resolution_notes" class="block text-sm font-medium text-gray-700 mb-2">Resolution Notes</label>
                            <textarea name="resolution_notes" id="resolution_notes" rows="4" 
                                      placeholder="Describe how this ticket was resolved..."
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">{{ $ticket->resolution_notes }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="resolved_at" class="block text-sm font-medium text-gray-700 mb-2">Resolution Date</label>
                                <input type="datetime-local" name="resolved_at" id="resolved_at" 
                                       value="{{ $ticket->resolved_at ? $ticket->resolved_at->format('Y-m-d\TH:i') : '' }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                            </div>

                            <div>
                                <label for="resolved_by" class="block text-sm font-medium text-gray-700 mb-2">Resolved By</label>
                                <select name="resolved_by" id="resolved_by" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                                    <option value="">Select Admin</option>
                                    @foreach($admins ?? [] as $admin)
                                        <option value="{{ $admin->id }}" {{ $ticket->resolved_by == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Internal Notes -->
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="3" 
                                  placeholder="Internal admin notes (not visible to user)..."
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">{{ $ticket->admin_notes }}</textarea>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        <input type="text" name="tags" id="tags" 
                               value="{{ is_array($ticket->tags ?? []) ? implode(', ', $ticket->tags) : $ticket->tags }}" 
                               placeholder="Enter tags separated by commas"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                        <p class="text-xs text-gray-500 mt-1">Separate multiple tags with commas</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Update Ticket
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Current Ticket Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Details</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Ticket ID:</span>
                        <span class="text-sm font-medium text-gray-900">#{{ $ticket->ticket_number ?? $ticket->id }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Created:</span>
                        <span class="text-sm text-gray-900">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Updated:</span>
                        <span class="text-sm text-gray-900">{{ $ticket->updated_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Responses:</span>
                        <span class="text-sm text-gray-900">{{ count($ticket->responses ?? []) }}</span>
                    </div>
                </div>
            </div>

            <!-- Ticket Submitter -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Submitted By</h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        @if($ticket->user->avatar ?? false)
                            <img src="{{ asset('storage/' . $ticket->user->avatar) }}" alt="{{ $ticket->user->name }}" class="w-10 h-10 rounded-full">
                        @else
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $ticket->user->name ?? 'Unknown User' }}</p>
                            <p class="text-xs text-gray-500">{{ $ticket->user->email ?? 'No email' }}</p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500">Role:</span>
                                <p class="text-gray-900">{{ ucfirst($ticket->user->role ?? 'user') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Total Tickets:</span>
                                <p class="text-gray-900">{{ $ticket->user->tickets_count ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.show', $ticket->user->id ?? '#') }}" 
                       class="text-sm text-[#ff6900] hover:text-blue-700 font-medium">
                        View User Profile →
                    </a>
                </div>
            </div>

            <!-- Current Assignment -->
            @if($ticket->assignee)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Assignment</h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        @if($ticket->assignee->avatar ?? false)
                            <img src="{{ asset('storage/' . $ticket->assignee->avatar) }}" alt="{{ $ticket->assignee->name }}" class="w-10 h-10 rounded-full">
                        @else
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $ticket->assignee->name }}</p>
                            <p class="text-xs text-gray-500">{{ $ticket->department ?? 'Support' }} Department</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($ticket->status !== 'in_progress')
                    <button onclick="quickUpdateStatus('in_progress')" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-700 transition-colors">
                        Mark In Progress
                    </button>
                    @endif
                    
                    @if($ticket->status !== 'resolved')
                    <button onclick="quickUpdateStatus('resolved')" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                        Mark Resolved
                    </button>
                    @endif
                    
                    @if($ticket->status !== 'closed')
                    <button onclick="quickUpdateStatus('closed')" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                        Close Ticket
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.tickets.show', $ticket) }}" 
                       class="w-full block text-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        View Ticket
                    </a>
                    
                    <a href="mailto:{{ $ticket->user->email ?? '' }}" 
                       class="w-full block text-center bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                        Email User
                    </a>
                </div>
            </div>

            <!-- Activity Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Activity Summary</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">First Response:</span>
                        <span class="text-gray-900">{{ $ticket->first_response_time ?? 'Pending' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Resolution Time:</span>
                        <span class="text-gray-900">{{ $ticket->resolution_time ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Activity:</span>
                        <span class="text-gray-900">{{ $ticket->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide resolution section based on status
        const statusSelect = document.getElementById('status');
        const resolutionSection = document.getElementById('resolution-section');
        
        statusSelect.addEventListener('change', function() {
            if (this.value === 'resolved' || this.value === 'closed') {
                resolutionSection.classList.remove('hidden');
                // Auto-set resolution date to now if not already set
                const resolvedAtInput = document.getElementById('resolved_at');
                if (!resolvedAtInput.value && this.value === 'resolved') {
                    resolvedAtInput.value = new Date().toISOString().slice(0, 16);
                }
            } else {
                resolutionSection.classList.add('hidden');
            }
        });
    });

    function quickUpdateStatus(status) {
        if (confirm(`Mark this ticket as ${status.replace('_', ' ')}?`)) {
            document.getElementById('status').value = status;
            
            if (status === 'resolved' || status === 'closed') {
                document.getElementById('resolved_at').value = new Date().toISOString().slice(0, 16);
                document.getElementById('resolution-section').classList.remove('hidden');
            }
            
            // Auto-submit form
            document.getElementById('ticket-form').submit();
        }
    }
</script>
@endpush
@endsection