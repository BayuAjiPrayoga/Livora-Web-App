@extends('layouts.tenant')

@section('title', 'Profil Saya - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
                    <p class="text-gray-600 mt-1">Kelola informasi pribadi dan preferensi akun Anda</p>
                </div>
                <a href="{{ route('tenant.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Picture & Basic Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="relative inline-block">
                            <div class="w-24 h-24 bg-livora-primary rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <button class="absolute bottom-0 right-0 w-8 h-8 bg-livora-accent rounded-full flex items-center justify-center text-white hover:bg-orange-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mt-4">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-livora-primary font-medium mt-2">Tenant LIVORA</p>
                        
                        <div class="mt-6 space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Bergabung sejak:</span>
                                <span class="font-medium">{{ Auth::user()->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Akun</h3>
                        <div class="space-y-4">
                            @php
                                $userBookings = \App\Models\Booking::where('user_id', Auth::id())->get();
                                $userPayments = \App\Models\Payment::whereHas('booking', function($q) {
                                    $q->where('user_id', Auth::id());
                                })->get();
                                $userTickets = \App\Models\Ticket::where('user_id', Auth::id())->get();
                            @endphp
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-6 0h6"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-600">Total Booking</span>
                                </div>
                                <span class="font-semibold text-gray-900">{{ $userBookings->count() }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-600">Total Pembayaran</span>
                                </div>
                                <span class="font-semibold text-gray-900">{{ $userPayments->count() }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-600">Total Tiket</span>
                                </div>
                                <span class="font-semibold text-gray-900">{{ $userTickets->count() }}</span>
                            </div>

                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900">Total Pengeluaran</span>
                                    <span class="font-bold text-livora-primary">
                                        Rp {{ number_format($userPayments->where('status', 'verified')->sum('amount'), 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Personal Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Pribadi</h3>
                            <p class="text-sm text-gray-600 mt-1">Update informasi dasar profil Anda</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" 
                                           value="{{ old('name', Auth::user()->name) }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-livora-primary focus:ring-livora-primary @error('name') border-red-300 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" id="email" 
                                           value="{{ old('email', Auth::user()->email) }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-livora-primary focus:ring-livora-primary @error('email') border-red-300 @enderror">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                    <input type="tel" name="phone" id="phone" 
                                           value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-livora-primary focus:ring-livora-primary"
                                           placeholder="08xxxxxxxxxx">
                                </div>

                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" id="birth_date" 
                                           value="{{ old('birth_date', Auth::user()->birth_date ?? '') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-livora-primary focus:ring-livora-primary">
                                </div>
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <textarea name="address" id="address" rows="3" 
                                          class="w-full rounded-lg border-gray-300 focus:border-livora-primary focus:ring-livora-primary"
                                          placeholder="Alamat lengkap Anda">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Keamanan Akun</h3>
                            <p class="text-sm text-gray-600 mt-1">Update password dan pengaturan keamanan</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                <input type="password" name="current_password" id="current_password" 
                                       class="w-full rounded-lg border-gray-300 focus:border-livora-primary focus:ring-livora-primary @error('current_password') border-red-300 @enderror"
                                       placeholder="Masukkan password saat ini">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                    <input type="password" name="password" id="password" 
                                           class="w-full rounded-lg border-gray-300 focus:border-livora-primary focus:ring-livora-primary @error('password') border-red-300 @enderror"
                                           placeholder="Masukkan password baru">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="w-full rounded-lg border-gray-300 focus:border-livora-primary focus:ring-livora-primary"
                                           placeholder="Ulangi password baru">
                                </div>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-blue-800">Tips Password Aman</h4>
                                        <div class="text-sm text-blue-700 mt-1">
                                            • Minimal 8 karakter<br>
                                            • Kombinasi huruf besar, kecil, angka<br>
                                            • Hindari informasi pribadi
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Preferences -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Preferensi Notifikasi</h3>
                            <p class="text-sm text-gray-600 mt-1">Atur bagaimana Anda ingin menerima pemberitahuan</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Email Notifications</p>
                                    <p class="text-xs text-gray-600">Terima pemberitahuan melalui email</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_notifications" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-livora-primary"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">SMS Notifications</p>
                                    <p class="text-xs text-gray-600">Terima pemberitahuan melalui SMS</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="sms_notifications" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-livora-primary"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Marketing Updates</p>
                                    <p class="text-xs text-gray-600">Terima info promo dan update terbaru</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="marketing_notifications" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-livora-primary"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex items-center justify-end space-x-4">
                        <button type="button" onclick="window.location.reload()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Reset
                        </button>
                        <button type="submit" class="px-6 py-2 bg-livora-primary text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

                <!-- Account Actions -->
                <div class="bg-red-50 border border-red-200 rounded-xl p-6 mt-6">
                    <h3 class="text-lg font-semibold text-red-800 mb-2">Zona Bahaya</h3>
                    <p class="text-sm text-red-600 mb-4">Tindakan berikut akan mempengaruhi akun Anda secara permanen</p>
                    <div class="space-y-3">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-red-700 bg-white hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L5.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Nonaktifkan Akun
                        </button>
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-red-700 bg-white hover:bg-red-50 transition-colors ml-3">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection