@extends('layouts.admin')

@section('title', 'Ticket Details - LIVORA')

@section('page-title', 'Ticket Details')

@section('content')
<div class="p-6">
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-livora-primary">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Tickets
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-900">Ticket #{{ $ticket->id }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Ticket Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $ticket->subject }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Ticket #{{ $ticket->id }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $ticket->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $ticket->priority === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($ticket->priority) }} Priority
                        </span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Ticket Information</h4>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-xs text-gray-500">User</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500">Room</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->room->name ?? $ticket->room->number }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500">Created</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->created_at->format('d M Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500">Last Updated</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->updated_at->format('d M Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Property</h4>
                        <dl class="space-y-2">
                            @if($ticket->room && $ticket->room->boardingHouse)
                            <div>
                                <dt class="text-xs text-gray-500">Boarding House</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->room->boardingHouse->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500">Address</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->room->boardingHouse->address }}</dd>
                            </div>
                            @endif
                            @if($ticket->resolved_at)
                            <div>
                                <dt class="text-xs text-gray-500">Resolved</dt>
                                <dd class="text-sm text-gray-900">{{ $ticket->resolved_at->format('d M Y H:i') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Original Message -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Original Message</h4>
                    <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $ticket->message }}</div>
                </div>

                <!-- Admin Response -->
                @if($ticket->response)
                <div class="mt-4 bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Admin Response</h4>
                    <div class="text-sm text-blue-800 whitespace-pre-wrap">{{ $ticket->response }}</div>
                </div>
                @endif
            </div>

            <!-- Responses/Conversation -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Conversation</h3>
                    <button onclick="toggleResponseForm()" class="bg-livora-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Add Response
                    </button>
                </div>

                <!-- Response Form (Initially Hidden) -->
                <div id="response-form" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                    <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label for="response_message" class="block text-sm font-medium text-gray-700 mb-2">Response Message</label>
                                <textarea name="response" id="response_message" rows="4" 
                                          placeholder="Type your response here..."
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary" required></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="new_status" class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                                    <select name="status" id="new_status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="new_priority" class="block text-sm font-medium text-gray-700 mb-2">Update Priority</label>
                                    <select name="priority" id="new_priority" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                                        <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="toggleResponseForm()" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-livora-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                    Send Response
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Ticket Timeline -->
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Ticket Created</p>
                            <p class="text-xs text-gray-500">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($ticket->response)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Admin Response Added</p>
                            <p class="text-xs text-gray-500">{{ $ticket->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($ticket->resolved_at)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Ticket Resolved</p>
                            <p class="text-xs text-gray-500">{{ $ticket->resolved_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                @if(count($ticket->responses ?? []) == 0 && !$ticket->response)
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No responses yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Be the first to respond to this ticket.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($ticket->status !== 'in_progress')
                    <button onclick="updateTicketStatus('in_progress')" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-700 transition-colors">
                        Mark In Progress
                    </button>
                    @endif
                    
                    @if($ticket->status !== 'resolved')
                    <button onclick="updateTicketStatus('resolved')" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                        Mark Resolved
                    </button>
                    @endif
                    
                    @if($ticket->status !== 'closed')
                    <button onclick="updateTicketStatus('closed')" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                        Close Ticket
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.tickets.edit', $ticket) }}" 
                       class="w-full block text-center bg-livora-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Edit Ticket
                    </a>
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
                                <span class="text-gray-500">Phone:</span>
                                <p class="text-gray-900">{{ $ticket->user->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Joined:</span>
                                <p class="text-gray-900">{{ $ticket->user->created_at ? $ticket->user->created_at->format('M Y') : 'N/A' }}</p>
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
                       class="text-sm text-livora-primary hover:text-blue-700 font-medium">
                        View Full Profile →
                    </a>
                </div>
            </div>

            <!-- Assignment -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Assignment</h3>
                <form method="POST" action="{{ route('admin.tickets.assign', $ticket) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="assignee_id" class="block text-sm font-medium text-gray-700 mb-2">Assign to</label>
                            <select name="assignee_id" id="assignee_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                                <option value="">Unassigned</option>
                                @foreach($admins ?? [] as $admin)
                                    <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <select name="priority" id="priority" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                                <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-livora-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            Update Assignment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ticket Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Response Time:</span>
                        <span class="text-gray-900">{{ $ticket->first_response_time ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Resolution Time:</span>
                        <span class="text-gray-900">{{ $ticket->resolution_time ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Responses:</span>
                        <span class="text-gray-900">{{ count($ticket->responses ?? []) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
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
    function toggleResponseForm() {
        const form = document.getElementById('response-form');
        form.classList.toggle('hidden');
        
        if (!form.classList.contains('hidden')) {
            document.getElementById('response_message').focus();
        }
    }

    function updateTicketStatus(status) {
        if (confirm(`Are you sure you want to mark this ticket as ${status.replace('_', ' ')}?`)) {
            fetch(`{{ route('admin.tickets.update-status', $ticket) }}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update ticket status');
                }
            })
            .catch(error => {
                alert('Error updating ticket status');
            });
        }
    }
</script>
@endpush
@endsection