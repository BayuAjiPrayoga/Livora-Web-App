@extends('layouts.mitra')

@section('title', 'Laporan - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-livora-text">📊 Laporan & Analitik</h1>
                    <p class="text-gray-600 mt-1">Pantau performa kos dan analisis bisnis Anda</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('mitra.reports.export-pdf') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export PDF
                    </a>
                    <a href="{{ route('mitra.reports.export-excel') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-6">
        <!-- Revenue Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Current Month Revenue -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pendapatan Bulan Ini</p>
                        <h3 class="text-lg font-bold text-gray-900">Rp {{ number_format($stats['current_revenue'] ?? 0, 0, ',', '.') }}</h3>
                        @if(isset($stats['revenue_growth']))
                            @if($stats['revenue_growth'] > 0)
                                <p class="text-xs text-green-600">+{{ number_format($stats['revenue_growth'], 1) }}% dari bulan lalu</p>
                            @elseif($stats['revenue_growth'] < 0)
                                <p class="text-xs text-red-600">{{ number_format($stats['revenue_growth'], 1) }}% dari bulan lalu</p>
                            @else
                                <p class="text-xs text-gray-500">Sama dengan bulan lalu</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Current Month Bookings -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Booking Bulan Ini</p>
                        <h3 class="text-lg font-bold text-gray-900">{{ number_format($stats['current_bookings'] ?? 0) }}</h3>
                        @if(isset($stats['booking_growth']))
                            @if($stats['booking_growth'] > 0)
                                <p class="text-xs text-green-600">+{{ number_format($stats['booking_growth'], 1) }}% dari bulan lalu</p>
                            @elseif($stats['booking_growth'] < 0)
                                <p class="text-xs text-red-600">{{ number_format($stats['booking_growth'], 1) }}% dari bulan lalu</p>
                            @else
                                <p class="text-xs text-gray-500">Sama dengan bulan lalu</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Occupancy Rate -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Tingkat Okupansi</p>
                        <h3 class="text-lg font-bold text-gray-900">{{ number_format($properties['occupancy_rate'] ?? 0, 1) }}%</h3>
                        <p class="text-xs text-gray-500">{{ $properties['occupied_rooms'] ?? 0 }} dari {{ $properties['total_rooms'] ?? 0 }} kamar</p>
                    </div>
                </div>
            </div>

            <!-- Total Properties -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Properti</p>
                        <h3 class="text-lg font-bold text-gray-900">{{ number_format(count($topProperties ?? [])) }}</h3>
                        <p class="text-xs text-gray-500">{{ $properties['total_rooms'] ?? 0 }} kamar total</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Trend Chart -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Tren Pendapatan (12 Bulan Terakhir)
                </h3>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" class="w-full h-64"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Performing Properties -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Properti Terbaik
                    </h3>
                </div>
                <div class="p-6">
                    @if(isset($topProperties) && count($topProperties) > 0)
                        <div class="space-y-4">
                            @foreach($topProperties as $index => $property)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $property->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $property->bookings_count ?? 0 }} booking</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">Rp {{ number_format($property->total_revenue ?? 0, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500">{{ $property->total_rooms ?? 0 }} kamar</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-500">Belum ada data properti</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Revenue Trend Chart (Small) -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Statistik Bulanan
                    </h3>
                </div>
                <div class="p-6">
                    @if(isset($revenueTrend) && is_array($revenueTrend) && count($revenueTrend) > 0)
                        <div class="space-y-3">
                            @foreach(array_slice($revenueTrend, -6) as $month => $revenue)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">{{ $month }}</span>
                                    <span class="font-medium text-gray-900">Rp {{ number_format((float)$revenue, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-500">Belum ada data revenue</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
setTimeout(function() {
    var canvas = document.getElementById('revenueChart');
    if (canvas) {
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Nov'],
                datasets: [{
                    data: [0, 0, 0, 0, 0, 1000000],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
}, 1000);
</script>
@endpush
@endsection