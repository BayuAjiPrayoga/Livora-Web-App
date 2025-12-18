@extends('layouts.admin')

@section('title', 'User Management - LIVORA')

@section('page-title', 'User Management')

@section('content')
<div class="p-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-semibold text-gray-900">User Management</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola semua user dalam sistem LIVORA</p>
        </div>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-livora-primary text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah User
            </a>
            <a href="{{ route('admin.users.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Property Owners</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['owners'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tenants</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['tenants'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Cari nama, email, atau phone..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Role Filter -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" id="role" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner (Mitra)</option>
                    <option value="tenant" {{ request('role') == 'tenant' ? 'selected' : '' }}>Tenant</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-livora-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" class="rounded border-gray-300 text-livora-primary focus:ring-livora-primary">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->avatar)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        @if($user->phone)
                                            <div class="text-sm text-gray-500">{{ $user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 
                                       ($user->role === 'mitra' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-400">{{ $user->last_login_at->diffForHumans() }}</div>
                                @else
                                    <span class="text-gray-400">Never</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-livora-primary hover:text-blue-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-600 hover:text-gray-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @if($user->is_active)
                                        <form method="POST" action="{{ route('admin.users.deactivate', $user) }}" class="inline" onsubmit="return confirm('Yakin ingin menonaktifkan user ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.activate', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if($user->role !== 'admin' || auth()->user()->id !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new user.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Bulk Actions -->
    <div id="bulkActions" class="hidden fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-lg shadow-lg border border-gray-200 px-6 py-3">
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">
                <span id="selectedCount">0</span> user(s) selected
            </span>
            <div class="flex space-x-2">
                <button onclick="bulkActivate()" class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200">
                    Activate
                </button>
                <button onclick="bulkDeactivate()" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded text-sm hover:bg-yellow-200">
                    Deactivate
                </button>
                <button onclick="bulkDelete()" class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll('input[name="selected_users[]"]');
    const bulkActionsDiv = document.getElementById('bulkActions');
    const selectedCountSpan = document.getElementById('selectedCount');

    selectAllCheckbox.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const selectedCheckboxes = document.querySelectorAll('input[name="selected_users[]"]:checked');
        const count = selectedCheckboxes.length;
        
        selectedCountSpan.textContent = count;
        
        if (count > 0) {
            bulkActionsDiv.classList.remove('hidden');
        } else {
            bulkActionsDiv.classList.add('hidden');
        }
        
        // Update select all checkbox state
        selectAllCheckbox.checked = count === userCheckboxes.length;
        selectAllCheckbox.indeterminate = count > 0 && count < userCheckboxes.length;
    }

    function getSelectedUserIds() {
        return Array.from(document.querySelectorAll('input[name="selected_users[]"]:checked'))
                   .map(checkbox => checkbox.value);
    }

    function bulkActivate() {
        const userIds = getSelectedUserIds();
        if (userIds.length === 0) return;
        
        if (confirm(`Yakin ingin mengaktifkan ${userIds.length} user?`)) {
            // Implement bulk activate functionality
            fetch('{{ route("admin.users.bulk-activate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ user_ids: userIds })
            }).then(() => location.reload());
        }
    }

    function bulkDeactivate() {
        const userIds = getSelectedUserIds();
        if (userIds.length === 0) return;
        
        if (confirm(`Yakin ingin menonaktifkan ${userIds.length} user?`)) {
            // Implement bulk deactivate functionality
            fetch('{{ route("admin.users.bulk-deactivate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ user_ids: userIds })
            }).then(() => location.reload());
        }
    }

    function bulkDelete() {
        const userIds = getSelectedUserIds();
        if (userIds.length === 0) return;
        
        if (confirm(`Yakin ingin menghapus ${userIds.length} user? Tindakan ini tidak dapat dibatalkan.`)) {
            // Implement bulk delete functionality
            fetch('{{ route("admin.users.bulk-delete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ user_ids: userIds })
            }).then(() => location.reload());
        }
    }

    // Export functionality is now handled by direct link
</script>
@endpush
@endsection