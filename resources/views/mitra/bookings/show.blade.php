@extends('layouts.mitra')

@section('title', 'Detail Booking - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('mitra.bookings.index') }}" 
                       class="text-livora-accent hover:text-livora-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-livora-text">Detail Booking</h1>
                        <p class="text-gray-600 mt-1">{{ $booking->booking_code }}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                        @if($booking->status_color == 'yellow') bg-yellow-100 text-yellow-800
                        @elseif($booking->status_color == 'blue') bg-blue-100 text-blue-800
                        @elseif($booking->status_color == 'green') bg-green-100 text-green-800
                        @elseif($booking->status_color == 'gray') bg-gray-100 text-gray-800
                        @elseif($booking->status_color == 'red') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $booking->status_label }}
                    </span>
                    
                    @if($booking->canBeEdited())
                    <a href="{{ route('mitra.bookings.edit', $booking) }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Booking Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Informasi Booking</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Detail Booking</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm text-gray-500">Kode Booking</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->booking_code }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Tanggal Booking</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->created_at->format('d M Y, H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Tipe Booking</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->getBookingTypeLabel() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Status</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->status_label }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Periode Sewa</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm text-gray-500">Check-in</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->check_in_date ? $booking->check_in_date->format('d M Y') : 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Check-out</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->check_out_date ? $booking->check_out_date->format('d M Y') : 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Durasi</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->duration_months }} bulan</dd>
                                </div>
                                {{-- Actual dates not implemented yet
                                @if($booking->actual_check_in_date)
                                <div>
                                    <dt class="text-sm text-gray-500">Actual Check-in</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->actual_check_in_date->format('d M Y, H:i') }}</dd>
                                </div>
                                @endif --}}
                                {{-- Actual dates not implemented yet
                                @if($booking->actual_check_out_date)
                                <div>
                                    <dt class="text-sm text-gray-500">Actual Check-out</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->actual_check_out_date->format('d M Y, H:i') }}</dd>
                                </div>
                                @endif --}}
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Property & Room Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Properti & Kamar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Properti</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm text-gray-500">Nama</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->boardingHouse->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Alamat</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->boardingHouse->address }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Kota</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->boardingHouse->city }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Kamar</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm text-gray-500">Nama Kamar</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->room->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Kapasitas</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->room->capacity }} orang</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Harga</dt>
                                    <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->room->price, 0, ',', '.') }}/bulan</dd>
                                </div>
                                @if($booking->room->facilities && count($booking->room->facilities) > 0)
                                <div>
                                    <dt class="text-sm text-gray-500 mb-2">Fasilitas</dt>
                                    <dd class="text-sm">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($booking->room->facilities as $facility)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-livora-accent bg-opacity-10 text-livora-accent border border-livora-accent border-opacity-20">
                                                    @if($facility->icon)
                                                        <span class="mr-1">{!! $facility->icon !!}</span>
                                                    @endif
                                                    {{ $facility->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Tenant Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Data Penyewa</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Informasi Pribadi</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm text-gray-500">Nama Lengkap</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->tenant_name ?: ($booking->user->name ?? 'N/A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Nomor Telepon</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        @php
                                            $phone = $booking->tenant_phone ?: ($booking->user->phone ?? '');
                                        @endphp
                                        @if($phone)
                                            <a href="tel:{{ $phone }}" class="text-livora-accent hover:text-livora-primary">
                                                {{ $phone }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Email</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        @php
                                            $email = $booking->tenant_email ?: ($booking->user->email ?? '');
                                        @endphp
                                        @if($email)
                                            <a href="mailto:{{ $email }}" class="text-livora-accent hover:text-livora-primary">
                                                {{ $email }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Nomor KTP</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->tenant_identity_number ?: 'Tidak tersedia' }}</dd>
                                </div>
                                @if($booking->ktp_image)
                                <div>
                                    <dt class="text-sm text-gray-500">Foto KTP</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        <a href="{{ Storage::url($booking->ktp_image) }}" target="_blank" class="inline-block mt-2">
                                            <img src="{{ Storage::url($booking->ktp_image) }}" alt="KTP" 
                                                 class="max-h-32 rounded-lg border border-gray-300 hover:opacity-80 transition-opacity cursor-pointer">
                                        </a>
                                        <p class="text-xs text-gray-500 mt-1">Klik untuk melihat ukuran penuh</p>
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Alamat & Kontak Darurat</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm text-gray-500">Alamat</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->tenant_address ?: ($booking->user->address ?? 'Tidak tersedia') }}</dd>
                                </div>
                                @if($booking->emergency_contact_name)
                                <div>
                                    <dt class="text-sm text-gray-500">Kontak Darurat</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->emergency_contact_name }}</dd>
                                </div>
                                @endif
                                @if($booking->emergency_contact_phone)
                                <div>
                                    <dt class="text-sm text-gray-500">Nomor Kontak Darurat</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        <a href="tel:{{ $booking->emergency_contact_phone }}" class="text-livora-accent hover:text-livora-primary">
                                            {{ $booking->emergency_contact_phone }}
                                        </a>
                                    </dd>
                                </div>
                                @endif
                                @if($booking->emergency_contact_relation)
                                <div>
                                    <dt class="text-sm text-gray-500">Hubungan</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->emergency_contact_relation }}</dd>
                                </div>
                                @endif
                                {{-- Emergency contact relation not implemented yet
                                @if($booking->emergency_contact_relation)
                                <div>
                                    <dt class="text-sm text-gray-500">Hubungan</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->emergency_contact_relation }}</dd>
                                </div>
                                @endif --}}
                            </dl>
                        </div>
                    </div>
                </div>

                @if($booking->notes)
                <!-- Notes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Catatan Tambahan</h3>
                    @php
                        // Clean notes by removing structured data that we already displayed above
                        $cleanNotes = $booking->notes;
                        $cleanNotes = preg_replace('/KTP[:\s]*[0-9]{16}[\n]?/', '', $cleanNotes);
                        $cleanNotes = preg_replace('/Kontak Darurat[:\s]*[^\n]+[\n]?/', '', $cleanNotes);
                        $cleanNotes = preg_replace('/Nomor Kontak Darurat[:\s]*[0-9\-\+\s]+[\n]?/', '', $cleanNotes);
                        $cleanNotes = trim($cleanNotes);
                    @endphp
                    @if($cleanNotes)
                        <p class="text-gray-700">{{ $cleanNotes }}</p>
                    @else
                        <p class="text-gray-500 italic">Tidak ada catatan tambahan</p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Payment Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Informasi Pembayaran</h3>
                    
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Harga Kamar</dt>
                            <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->room->price, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Durasi</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $booking->duration_months }} bulan</dd>
                        </div>
                        {{-- Deposit not implemented yet
                        @if($booking->deposit_amount > 0)
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Deposit</dt>
                            <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->deposit_amount, 0, ',', '.') }}</dd>
                        </div>
                        @endif --}}
                        {{-- Admin fee not implemented yet
                        @if($booking->admin_fee > 0)
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Biaya Admin</dt>
                            <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->admin_fee, 0, ',', '.') }}</dd>
                        </div>
                        @endif --}}
                        {{-- Discount not implemented yet
                        @if($booking->discount_amount > 0)
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 text-red-600">Diskon</dt>
                            <dd class="text-sm font-medium text-red-600">-Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</dd>
                        </div>
                        @endif --}}
                        <hr>
                        <div class="flex justify-between">
                            <dt class="font-medium text-gray-900">Total</dt>
                            <dd class="font-bold text-lg text-livora-accent">Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Aksi</h3>
                    
                    <div class="space-y-3">
                        @if($booking->canBeConfirmed())
                        <form action="{{ route('mitra.bookings.confirm', $booking) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Konfirmasi Booking
                            </button>
                        </form>
                        @endif
                        
                        @if($booking->canBeCheckedIn())
                        <form action="{{ route('mitra.bookings.check-in', $booking) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Check-in Penyewa
                            </button>
                        </form>
                        @endif
                        
                        @if($booking->canBeCheckedOut())
                        <form action="{{ route('mitra.bookings.check-out', $booking) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Check-out Penyewa
                            </button>
                        </form>
                        @endif
                        
                        @if($booking->canBeCancelled())
                        <form action="{{ route('mitra.bookings.cancel', $booking) }}" method="POST" class="w-full"
                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batalkan Booking
                            </button>
                        </form>
                        @endif

                        <!-- Contact Actions -->
                        <div class="pt-3 border-t">
                            <div class="grid grid-cols-2 gap-2">
                                <a href="tel:{{ $booking->user->phone ?? '' }}" 
                                   class="flex items-center justify-center px-3 py-2 border border-gray-300 text-sm text-gray-700 rounded-lg hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    Telepon
                                </a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->user->phone ?? '') }}" 
                                   target="_blank"
                                   class="flex items-center justify-center px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Timeline Booking</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Booking Dibuat</p>
                                <p class="text-xs text-gray-500">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        {{-- Confirmed timestamp not implemented yet
                        @if($booking->confirmed_at)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Dikonfirmasi</p>
                                <p class="text-xs text-gray-500">{{ $booking->confirmed_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif --}}
                        
                        {{-- Timeline for actual check-in not implemented yet
                        @if($booking->actual_check_in_date)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-yellow-600 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Check-in</p>
                                <p class="text-xs text-gray-500">{{ $booking->actual_check_in_date->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif --}}
                        
                        {{-- Timeline for actual check-out not implemented yet
                        @if($booking->actual_check_out_date)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-gray-600 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Check-out</p>
                                <p class="text-xs text-gray-500">{{ $booking->actual_check_out_date->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif --}}
                        
                        @if($booking->cancelled_at)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-red-600 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Dibatalkan</p>
                                <p class="text-xs text-gray-500">{{ $booking->cancelled_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection