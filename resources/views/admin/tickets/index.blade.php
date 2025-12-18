@extends('layouts.admin')

@section('title', 'Ticket Management - LIVORA')

@section('page-title', 'Ticket Management')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-semibold text-gray-900">Ticket Management</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola dan tangani tiket dukungan pelanggan</p>
        </div>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('admin.tickets.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Tickets</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Open</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['open'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">In Progress</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['in_progress'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Resolved</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['resolved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L5.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">High Priority</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['high_priority'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Cari subjek atau ID tiket..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    <option value="">Semua Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select name="priority" id="priority" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    <option value="">Semua Prioritas</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-livora-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        @if($tickets->count() > 0)
            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                        <label for="selectAll" class="ml-2 text-sm font-medium text-gray-700">Select All</label>
                    </div>
                    <div class="hidden" id="bulkActions">
                        <button onclick="bulkAssign()" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                            Assign Selected
                        </button>
                        <button onclick="bulkClose()" class="ml-2 inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                            Close Selected
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ticket Info
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User & Subject
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority & Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Last Updated
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="ticket-checkbox rounded border-gray-300" value="{{ $ticket->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $ticket->id }}</div>
                                <div class="text-sm text-gray-500">{{ $ticket->created_at->format('M d, Y H:i') }}</div>
                                @if($ticket->resolved_at)
                                    <div class="text-xs text-green-600">Resolved: {{ $ticket->resolved_at->format('M d, Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 bg-livora-primary rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($ticket->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                        <div class="text-sm text-gray-900 font-medium">{{ $ticket->subject }}</div>
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($ticket->message, 50) }}</div>
                                        @if($ticket->room)
                                            <div class="text-xs text-gray-400">Room: {{ $ticket->room->name ?? $ticket->room->number }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $priorityColors = [
                                        'low' => 'bg-gray-100 text-gray-800',
                                        'medium' => 'bg-yellow-100 text-yellow-800', 
                                        'high' => 'bg-red-100 text-red-800'
                                    ];
                                    $statusColors = [
                                        'open' => 'bg-blue-100 text-blue-800',
                                        'in_progress' => 'bg-yellow-100 text-yellow-800',
                                        'resolved' => 'bg-green-100 text-green-800',
                                        'closed' => 'bg-gray-100 text-gray-800'
                                    ];
                                @endphp
                                <div class="space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$ticket->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($ticket->priority) }} Priority
                                    </span>
                                    <br>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ticket->updated_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $ticket->updated_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-livora-primary hover:text-blue-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if(in_array($ticket->status, ['open', 'in_progress']))
                                        <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="resolved">
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Mark as Resolved">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-3 border-t border-gray-200">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No tickets found</h3>
                <p class="mt-1 text-sm text-gray-500">No tickets match your current filter criteria.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.ticket-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        toggleBulkActions();
    });

    document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });

    function toggleBulkActions() {
        const selectedCount = document.querySelectorAll('.ticket-checkbox:checked').length;
        const bulkActions = document.getElementById('bulkActions');
        
        if (selectedCount > 0) {
            bulkActions.classList.remove('hidden');
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    function getSelectedTicketIds() {
        const selected = [];
        document.querySelectorAll('.ticket-checkbox:checked').forEach(checkbox => {
            selected.push(checkbox.value);
        });
        return selected;
    }

    function bulkAssign() {
        const ticketIds = getSelectedTicketIds();
        if (ticketIds.length === 0) return;
        
        const adminSelect = prompt('Enter admin ID to assign tickets to:');
        if (adminSelect) {
            fetch('{{ route("admin.tickets.bulk-assign") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ticket_ids: ticketIds, assigned_to: adminSelect })
            }).then(() => location.reload());
        }
    }

    function bulkClose() {
        const ticketIds = getSelectedTicketIds();
        if (ticketIds.length === 0) return;
        
        if (confirm(`Are you sure you want to close ${ticketIds.length} ticket(s)?`)) {
            fetch('{{ route("admin.tickets.bulk-close") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ticket_ids: ticketIds })
            }).then(() => location.reload());
        }
    }
</script>
@endpush
@endsection