@extends('layouts.mitra')

@section('title', 'Dashboard Mitra - LIVORA')

@section('content')
<div class="bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b animate-fade-in">
        <div class="px-6 py-6">
            <h1 class="text-3xl font-bold gradient-text">Dashboard Mitra</h1>
            <p class="text-gray-600 mt-2">Selamat datang di LIVORA - Live Better, Stay Better</p>
        </div>
    </div>

    <div class="p-6 space-y-6">
        <!-- Revenue Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Monthly Revenue Card -->
            <div class="stats-card animate-slide-up">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pendapatan Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                        <div class="flex items-center mt-3">
                            <span class="text-sm font-semibold {{ $revenueGrowth >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ abs(number_format($revenueGrowth, 1)) }}%
                            </span>
                            <span class="text-sm text-gray-500 ml-2">dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl">
                        <svg class="w-8 h-8 text-[#ff6900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="stats-card animate-slide-up" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 mt-3">Keseluruhan waktu</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Occupancy Rate Card -->
            <div class="stats-card animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tingkat Hunian</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalRooms > 0 ? number_format(($occupiedRooms / $totalRooms) * 100, 1) : 0 }}%</p>
                        <p class="text-sm text-gray-500 mt-3">{{ $occupiedRooms }} dari {{ $totalRooms }} kamar</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Tickets Card -->
            <div class="stats-card animate-slide-up" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tiket Pending</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingTickets }}</p>
                        <p class="text-sm text-gray-500 mt-3">Perlu ditangani</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="modern-card animate-slide-up" style="animation-delay: 0.4s">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Booking Terbaru</h2>
                    <p class="text-sm text-gray-600 mt-1">10 booking terbaru dari properti Anda</p>
                </div>
                <a href="{{ route('mitra.bookings.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Penyewa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentBookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h- w- bg-gradient-to-br from-[#ff6900] to-[#ff8533] rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">{{ substr($booking->tenant->name, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-livora-text">{{ $booking->tenant->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->tenant->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-livora-text font-medium">{{ $booking->room->name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->room->boardingHouse->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->check_in_date ? $booking->check_in_date->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->duration_months ?? 'N/A' }} bulan
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-livora-text">
                                Rp {{ number_format($booking->final_amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800', 
                                        'active' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-gray-100 text-gray-800',
                                        'cancelled' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('mitra.bookings.show', $booking) }}" class="text-orange-600 hover:text-[#ff6900] transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="mt-4 text-lg font-medium">Belum ada booking</p>
                                    <p class="text-sm">Booking akan muncul di sini setelah ada penyewa.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection