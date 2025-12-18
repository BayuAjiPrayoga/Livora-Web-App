@extends('layouts.mitra')

@section('title', 'Respon Tiket - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('mitra.tickets.show', $ticket) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Respon Tiket</h1>
                    <p class="text-gray-600 mt-1">Tiket #{{ $ticket->id }} • {{ $ticket->subject }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="text-red-700">
                    <p class="font-medium mb-2">Terdapat kesalahan pada form:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Ticket Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Detail Tiket</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Tenant</h3>
                        <p class="text-gray-900">{{ $ticket->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $ticket->user->email }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Kamar</h3>
                        @if($ticket->room)
                            <p class="text-gray-900">{{ $ticket->room->name }}</p>
                            <p class="text-sm text-gray-500">{{ $ticket->room->boardingHouse->name }}</p>
                        @else
                            <p class="text-gray-500 italic">Tidak terkait kamar</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                            {{ $ticket->status === 'open' ? 'bg-red-100 text-red-800' : 
                               ($ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                               ($ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Prioritas</h3>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                            {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' : 
                               ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Pesan dari Tenant</h3>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-700">{{ $ticket->message }}</p>
                        <p class="text-xs text-gray-500 mt-2">
                            Dibuat: {{ $ticket->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Respon & Update Status</h2>
            </div>

            <form action="{{ route('mitra.tickets.update', $ticket) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status *
                        </label>
                        <select name="status" id="status" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                            <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Prioritas *
                        </label>
                        <select name="priority" id="priority" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                            <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>

                <!-- Response -->
                <div class="mb-6">
                    <label for="response" class="block text-sm font-medium text-gray-700 mb-2">
                        Respon untuk Tenant
                    </label>
                    <textarea name="response" id="response" rows="5"
                              placeholder="Tulis respon Anda untuk tenant..."
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">{{ old('response', $ticket->response) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Respon ini akan dikirim ke tenant via notifikasi.</p>
                </div>

                <!-- Current Response (if exists) -->
                @if($ticket->response)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Respon Sebelumnya
                        </label>
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $ticket->response }}</p>
                            @if($ticket->resolved_at)
                                <p class="text-xs text-gray-500 mt-2">
                                    Diselesaikan pada: {{ $ticket->resolved_at->format('d M Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('mitra.tickets.show', $ticket) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-livora-primary transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-[#ff6900] border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Update Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection