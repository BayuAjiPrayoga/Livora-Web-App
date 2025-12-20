@extends('layouts.tenant')

@section('title', 'Dashboard Tenant - LIVORA')

@section('content')
<div class="min-h-screen bg-livora-background">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold">Selamat datang, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-purple-100 mt-2">Kelola booking, pembayaran, dan tiket Anda dengan mudah</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Browse Kost Button -->
                    <a href="{{ route('browse') }}" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 rounded-lg hover:bg-gray-50 transition-all duration-300 shadow-lg font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari Kost Lagi
                    </a>
                    <div class="hidden md:block">
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold">{{ \Carbon\Carbon::now()->format('d') }}</div>
                            <div class="text-sm">{{ \Carbon\Carbon::now()->format('M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Bookings -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-6 0h6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Booking</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_bookings'] }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-green-600 font-medium">{{ $statistics['active_bookings'] }} aktif</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span class="text-gray-600">{{ $statistics['completed_bookings'] }} selesai</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pembayaran</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_payments'] }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-yellow-600 font-medium">{{ $statistics['pending_payments'] }} menunggu</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span class="text-green-600">{{ $statistics['verified_payments'] }} verified</span>
                    </div>
                </div>
            </div>

            <!-- Monthly Spending -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pengeluaran Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($statistics['monthly_spent'], 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-gray-600">
                        Total: Rp {{ number_format($statistics['total_spent'], 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Support Tickets -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-violet-400 to-purple-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tiket Support</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['open_tickets'] + $statistics['resolved_tickets'] }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-orange-600 font-medium">{{ $statistics['open_tickets'] }} terbuka</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span class="text-gray-600">{{ $statistics['resolved_tickets'] }} selesai</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Current Booking Status -->
                @if($activeBooking)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-green-50 px-6 py-4 border-b border-green-100">
                        <h3 class="text-lg font-semibold text-green-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Booking Aktif Saat Ini
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-xl font-semibold text-gray-900">{{ $activeBooking->room->name ?? 'N/A' }}</h4>
                                <p class="text-gray-600">{{ $activeBooking->room->boardingHouse->name ?? 'N/A' }}</p>
                                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Check-in:</span>
                                        <p class="font-medium">{{ $activeBooking->check_in_date ? $activeBooking->check_in_date->format('d M Y') : 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Check-out:</span>
                                        <p class="font-medium">{{ $activeBooking->check_out_date ? $activeBooking->check_out_date->format('d M Y') : 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($upcomingBooking)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                        <h3 class="text-lg font-semibold text-blue-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Booking Mendatang
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-6 0h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-xl font-semibold text-gray-900">{{ $upcomingBooking->room->name ?? 'N/A' }}</h4>
                                <p class="text-gray-600">{{ $upcomingBooking->room->boardingHouse->name ?? 'N/A' }}</p>
                                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Check-in:</span>
                                        <p class="font-medium">{{ $upcomingBooking->check_in_date ? $upcomingBooking->check_in_date->format('d M Y') : 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Durasi:</span>
                                        <p class="font-medium">{{ $upcomingBooking->duration_months ?? 0 }} bulan</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $upcomingBooking->check_in_date ? $upcomingBooking->check_in_date->diffForHumans() : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Booking Aktif</h3>
                        <p class="text-gray-600 mb-4">Anda belum memiliki booking yang aktif saat ini</p>
                        <a href="{{ route('tenant.bookings.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Cari Kamar
                        </a>
                    </div>
                </div>
                @endif

                <!-- Recent Activities -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                    </div>
                    <div class="p-6">
                        @if($recentActivities->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentActivities->take(5) as $activity)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                            @if($activity['type'] === 'booking') bg-blue-100
                                            @elseif($activity['type'] === 'payment') bg-green-100
                                            @else bg-orange-100
                                            @endif">
                                            @if($activity['icon'] === 'calendar')
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-6 0h6"></path>
                                                </svg>
                                            @elseif($activity['icon'] === 'credit-card')
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $activity['description'] }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity['time']->diffForHumans() }}</p>
                                    </div>
                                    @if($activity['link'] !== '#')
                                    <div class="flex-shrink-0">
                                        <a href="{{ $activity['link'] }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Lihat
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('browse') }}" class="flex items-center p-3 rounded-lg bg-gradient-to-r from-blue-500 to-cyan-600 text-white hover:from-blue-600 hover:to-cyan-700 transition-all duration-300 shadow-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari Kost Lainnya
                        </a>
                        <a href="{{ route('tenant.bookings.create') }}" class="flex items-center p-3 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Booking Baru
                        </a>
                        <a href="{{ route('tenant.bookings.index') }}" class="flex items-center p-3 rounded-lg bg-cyan-50 text-cyan-700 hover:bg-cyan-100 transition-colors border border-cyan-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Lihat Booking
                        </a>
                        <a href="{{ route('tenant.payments.midtrans.create') }}" class="flex items-center p-3 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors border border-emerald-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Bayar Booking
                        </a>
                        <a href="{{ route('tenant.tickets.create') }}" class="flex items-center p-3 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors border border-amber-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            Buat Tiket
                        </a>
                        <a href="{{ route('tenant.payments.index') }}" class="flex items-center p-3 rounded-lg bg-violet-50 text-violet-700 hover:bg-violet-100 transition-colors border border-violet-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Riwayat Pembayaran
                        </a>
                        <a href="{{ route('tenant.profile') }}" class="flex items-center p-3 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition-colors border border-rose-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Edit Profil
                        </a>
                    </div>
                </div>

                <!-- Pending Payments -->
                @if($pendingPayments->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Menunggu Verifikasi
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($pendingPayments as $payment)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-600">Booking #{{ $payment->booking->id }}</p>
                            </div>
                            <a href="{{ route('tenant.payments.show', $payment) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                Lihat
                            </a>
                        </div>
                        @endforeach
                        <a href="{{ route('tenant.payments.index') }}" class="block text-center text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                @endif

                <!-- Open Tickets -->
                @if($openTickets->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            Tiket Terbuka
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($openTickets as $ticket)
                        <div class="p-3 border border-gray-200 rounded-lg">
                            <p class="font-medium text-gray-900 text-sm">{{ $ticket->title }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $ticket->created_at->diffForHumans() }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $ticket->priority === 'high' ? 'red' : ($ticket->priority === 'medium' ? 'yellow' : 'gray') }}-100 text-{{ $ticket->priority === 'high' ? 'red' : ($ticket->priority === 'medium' ? 'yellow' : 'gray') }}-800">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                                <a href="{{ route('tenant.tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                    Lihat
                                </a>
                            </div>
                        </div>
                        @endforeach
                        <a href="{{ route('tenant.tickets.index') }}" class="block text-center text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                @endif

                <!-- Help & Support -->
                <div class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">Butuh Bantuan?</h3>
                    <p class="text-purple-100 text-sm mb-4">Tim support kami siap membantu Anda 24/7</p>
                    <div class="space-y-2">
                        <a href="{{ route('tenant.tickets.create') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg px-4 py-2 text-center text-sm font-medium transition-colors">
                            Buat Tiket Support
                        </a>
                        <a href="https://wa.me/6281234567890" target="_blank" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg px-4 py-2 text-center text-sm font-medium transition-colors">
                            WhatsApp Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection