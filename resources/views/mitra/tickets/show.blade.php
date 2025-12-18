@extends('layouts.mitra')

@section('title', 'Detail Tiket #' . $ticket->id . ' - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('mitra.dashboard') }}" class="hover:text-livora-primary">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('mitra.tickets.index') }}" class="hover:text-livora-primary">Kelola Tiket</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-livora-primary font-medium">Tiket #{{ $ticket->id }}</span>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-livora-text">Detail Tiket #{{ $ticket->id }}</h1>
                    <p class="text-gray-600 mt-1">{{ $ticket->subject }}</p>
                </div>
                <a href="{{ route('mitra.tickets.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <div class="font-bold">Terjadi kesalahan:</div>
            <ul class="mt-2 list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Ticket Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold text-livora-text">{{ $ticket->subject }}</h3>
                        <div class="flex space-x-2">
                            <!-- Priority Badge -->
                            @php
                                $priorityClass = match($ticket->priority) {
                                    'urgent' => 'bg-red-100 text-red-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'low' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $priorityText = match($ticket->priority) {
                                    'urgent' => 'Urgent',
                                    'high' => 'Tinggi',
                                    'medium' => 'Sedang',
                                    'low' => 'Rendah',
                                    default => 'Normal'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $priorityClass }}">
                                {{ $priorityText }}
                            </span>

                            <!-- Status Badge -->
                            @php
                                $statusClass = match($ticket->status) {
                                    'open' => 'bg-red-100 text-red-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusText = match($ticket->status) {
                                    'open' => 'Belum Diproses',
                                    'in_progress' => 'Sedang Diproses',
                                    'resolved' => 'Selesai',
                                    default => 'Unknown'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $ticket->message }}</p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span>Dibuat: {{ $ticket->created_at->format('d F Y, H:i') }}</span>
                            @if($ticket->resolved_at)
                                <span>Diselesaikan: {{ $ticket->resolved_at->format('d F Y, H:i') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Response Section -->
                @if($ticket->response)
                <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                    <h4 class="text-md font-semibold text-blue-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Respon Anda
                    </h4>
                    <p class="text-blue-800 whitespace-pre-line">{{ $ticket->response }}</p>
                </div>
                @endif

                <!-- Update Response Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="text-lg font-semibold text-livora-text mb-4">
                        {{ $ticket->response ? 'Update Respon' : 'Berikan Respon' }}
                    </h4>
                    
                    <form action="{{ route('mitra.tickets.update', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="space-y-4">
                            <!-- Response Textarea -->
                            <div>
                                <label for="response" class="block text-sm font-medium text-gray-700 mb-2">
                                    Respon
                                </label>
                                <textarea name="response" id="response" rows="4" 
                                          class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('response') border-red-300 @enderror"
                                          placeholder="Berikan respon terhadap tiket ini...">{{ old('response', $ticket->response) }}</textarea>
                                @error('response')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status and Priority -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" 
                                            class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('status') border-red-300 @enderror">
                                        <option value="open" {{ old('status', $ticket->status) === 'open' ? 'selected' : '' }}>Belum Diproses</option>
                                        <option value="in_progress" {{ old('status', $ticket->status) === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                                        <option value="resolved" {{ old('status', $ticket->status) === 'resolved' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Priority -->
                                <div>
                                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                        Prioritas
                                    </label>
                                    <select name="priority" id="priority" 
                                            class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent">
                                        <option value="low" {{ old('priority', $ticket->priority) === 'low' ? 'selected' : '' }}>Rendah</option>
                                        <option value="medium" {{ old('priority', $ticket->priority) === 'medium' ? 'selected' : '' }}>Sedang</option>
                                        <option value="high" {{ old('priority', $ticket->priority) === 'high' ? 'selected' : '' }}>Tinggi</option>
                                        <option value="urgent" {{ old('priority', $ticket->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-2 bg-livora-primary border border-transparent rounded-md font-semibold text-white hover:bg-blue-800 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $ticket->response ? 'Update Respon' : 'Kirim Respon' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Tenant Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="text-lg font-semibold text-livora-text mb-4">Informasi Penyewa</h4>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->tenant->name }}</p>
                                <p class="text-sm text-gray-500">Penyewa</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->tenant->email }}</p>
                                <p class="text-sm text-gray-500">Email</p>
                            </div>
                        </div>

                        @if($ticket->tenant->phone)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->tenant->phone }}</p>
                                <p class="text-sm text-gray-500">Telepon</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Property & Room Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="text-lg font-semibold text-livora-text mb-4">Properti & Kamar</h4>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->room->boardingHouse->name }}</p>
                                <p class="text-sm text-gray-500">Properti</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10.5 3L12 2l1.5 1H21v6H3V3h7.5z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->room->name }}</p>
                                <p class="text-sm text-gray-500">Kamar</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->room->boardingHouse->address }}</p>
                                <p class="text-sm text-gray-500">Alamat</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="text-lg font-semibold text-livora-text mb-4">Aksi Cepat</h4>
                    
                    <div class="space-y-3">
                        @if($ticket->status !== 'resolved')
                        <button onclick="updateStatus('resolved')" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Selesaikan Tiket
                        </button>
                        @endif

                        @if($ticket->status === 'open')
                        <button onclick="updateStatus('in_progress')" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-white hover:bg-yellow-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mulai Proses
                        </button>
                        @endif

                        @if($ticket->priority !== 'urgent')
                        <button onclick="updatePriority('urgent')" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Tandai Urgent
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for quick actions -->
<script>
function updateStatus(status) {
    if (!confirm('Apakah Anda yakin ingin mengubah status tiket ini?')) {
        return;
    }

    fetch('{{ route("mitra.tickets.update-status", $ticket) }}', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate status tiket.');
    });
}

function updatePriority(priority) {
    if (!confirm('Apakah Anda yakin ingin mengubah prioritas tiket ini?')) {
        return;
    }

    fetch('{{ route("mitra.tickets.update-priority", $ticket) }}', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ priority: priority })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate prioritas tiket.');
    });
}
</script>
@endsection