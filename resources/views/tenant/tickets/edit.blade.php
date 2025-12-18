@extends('layouts.tenant')

@section('title', 'Edit Tiket - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('tenant.tickets.show', $ticket) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Tiket</h1>
                    <p class="text-gray-600 mt-1">Tiket #{{ $ticket->id }} • {{ $ticket->subject }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

        <!-- Notice -->
        @if($ticket->status !== 'open')
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Perhatian:</strong> Tiket ini sudah {{ $ticket->status == 'resolved' ? 'diselesaikan' : 'dalam proses' }} dan mungkin tidak dapat diubah.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Edit Tiket Support</h2>
            </div>

            <form action="{{ route('tenant.tickets.update', $ticket) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Current Status Info (Read Only) -->
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-md font-medium text-gray-900 mb-3">Status Tiket Saat Ini</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                {{ $ticket->status === 'open' ? 'bg-red-100 text-red-800' : 
                                   ($ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Prioritas:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                   ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Dibuat:</span>
                            <span class="font-medium text-gray-900">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Room Selection -->
                <div class="mb-6">
                    <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kamar Terkait
                    </label>
                    <select name="room_id" id="room_id"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                        <option value="">Pilih kamar (opsional)...</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $ticket->room_id) == $room->id ? 'selected' : '' }}>
                                {{ $room->name }} - {{ $room->boardingHouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject -->
                <div class="mb-6">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Subjek *
                    </label>
                    <input type="text" name="subject" id="subject" required
                           value="{{ old('subject', $ticket->subject) }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                </div>

                <!-- Priority -->
                <div class="mb-6">
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                        Prioritas *
                    </label>
                    <select name="priority" id="priority" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                        <option value="">Pilih prioritas...</option>
                        <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                        <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                    </select>
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Pesan *
                    </label>
                    <textarea name="message" id="message" rows="5" required
                              placeholder="Jelaskan masalah atau permintaan Anda dengan detail..."
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">{{ old('message', $ticket->message) }}</textarea>
                </div>

                <!-- Response (if exists) -->
                @if($ticket->response)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Respon dari Admin
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
                    <a href="{{ route('tenant.tickets.show', $ticket) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-livora-primary transition-colors">
                        Batal
                    </a>
                    @if($ticket->status === 'open')
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-[#ff6900] border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Simpan Perubahan
                        </button>
                    @else
                        <span class="inline-flex items-center px-6 py-2 bg-gray-300 border border-transparent rounded-lg text-sm font-medium text-gray-500 cursor-not-allowed">
                            Tidak Dapat Diubah
                        </span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection