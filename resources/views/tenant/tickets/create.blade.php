@extends('layouts.tenant')

@section('title', 'Buat Tiket Baru - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('tenant.dashboard') }}" class="hover:text-livora-primary">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('tenant.tickets.index') }}" class="hover:text-livora-primary">Tiket Saya</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-livora-primary font-medium">Buat Tiket Baru</span>
            </div>
            <h1 class="text-2xl font-bold text-livora-text flex items-center">
                <svg class="w-6 h-6 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Buat Tiket Baru
            </h1>
            <p class="text-gray-600 mt-1">Sampaikan keluhan atau permintaan Anda</p>
        </div>
    </div>

    <div class="p-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                @if ($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tenant.tickets.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Room Selection -->
                    <div>
                        <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kamar <span class="text-red-500">*</span>
                        </label>
                        <select name="room_id" id="room_id" 
                                class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('room_id') border-red-500 @enderror"
                                required>
                            <option value="">Pilih kamar</option>
                            @foreach($bookings as $booking)
                                <option value="{{ $booking->room_id }}" {{ old('room_id') == $booking->room_id ? 'selected' : '' }}>
                                    {{ $booking->room->boardingHouse->name }} - {{ $booking->room->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Pilih kamar yang berkaitan dengan keluhan atau permintaan Anda</p>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Subjek <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" id="subject" 
                               value="{{ old('subject') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('subject') border-red-500 @enderror"
                               placeholder="Contoh: AC tidak dingin"
                               maxlength="255"
                               required>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Tingkat Prioritas <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" id="priority" 
                                class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('priority') border-red-500 @enderror"
                                required>
                            <option value="">Pilih tingkat prioritas</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah - Tidak mendesak</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Sedang - Perlu penanganan</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi - Segera ditangani</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent - Sangat mendesak</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Pesan Keluhan/Permintaan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" id="message" rows="6"
                                  class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('message') border-red-500 @enderror"
                                  placeholder="Jelaskan keluhan atau permintaan Anda secara detail..."
                                  maxlength="1000"
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Maksimal 1000 karakter</p>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Tips membuat tiket:</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-700">
                                    <li>Jelaskan masalah dengan detail dan spesifik</li>
                                    <li>Sebutkan waktu kejadian jika relevan</li>
                                    <li>Pilih prioritas sesuai urgensi masalah</li>
                                    <li>Mitra akan merespon tiket Anda secepatnya</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t">
                        <a href="{{ route('tenant.tickets.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-livora-primary border border-transparent rounded-md font-semibold text-white hover:bg-blue-800 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Kirim Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
