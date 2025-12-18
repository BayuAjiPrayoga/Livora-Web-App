@extends('layouts.admin')

@section('title', 'Occupancy Reports - LIVORA')

@section('page-title', 'Occupancy Reports')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-semibold text-gray-900">Occupancy Reports</h1>
            <p class="text-sm text-gray-600 mt-1">Analisis tingkat hunian dan utilisasi properti</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportReport()" class="btn btn-primary">
                Export Report
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Overall Occupancy</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $overallOccupancy ?? 0 }}%</p>
                    <p class="text-sm {{ ($occupancyChange ?? 0) > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ ($occupancyChange ?? 0) > 0 ? '+' : '' }}{{ $occupancyChange ?? 0 }}% from last month
                    </p>
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
                    <p class="text-sm font-medium text-gray-600">Occupied Rooms</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $occupiedRooms ?? 0 }}</p>
                    <p class="text-sm text-gray-500">of {{ $totalRooms ?? 0 }} total rooms</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg. Stay Duration</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $avgStayDuration ?? 0 }}</p>
                    <p class="text-sm text-gray-500">days per booking</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Turnover Rate</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $turnoverRate ?? 0 }}%</p>
                    <p class="text-sm text-gray-500">monthly turnover</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="period" class="block text-sm font-medium text-gray-700 mb-2">Time Period</label>
                <select name="period" id="period" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                    <option value="current" {{ request('period') == 'current' ? 'selected' : '' }}>Current Month</option>
                    <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="3months" {{ request('period') == '3months' ? 'selected' : '' }}>Last 3 Months</option>
                    <option value="6months" {{ request('period') == '6months' ? 'selected' : '' }}>Last 6 Months</option>
                    <option value="1year" {{ request('period') == '1year' ? 'selected' : '' }}>Last Year</option>
                </select>
            </div>

            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                <select name="city" id="city" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                    <option value="">All Cities</option>
                    @if(isset($cities))
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div>
                <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                <select name="property_type" id="property_type" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                    <option value="">All Types</option>
                    <option value="kos_putra" {{ request('property_type') == 'kos_putra' ? 'selected' : '' }}>Kos Putra</option>
                    <option value="kos_putri" {{ request('property_type') == 'kos_putri' ? 'selected' : '' }}>Kos Putri</option>
                    <option value="kos_campur" {{ request('property_type') == 'kos_campur' ? 'selected' : '' }}>Kos Campur</option>
                </select>
            </div>

            <div>
                <label for="min_occupancy" class="block text-sm font-medium text-gray-700 mb-2">Min. Occupancy (%)</label>
                <input type="number" name="min_occupancy" id="min_occupancy" min="0" max="100" 
                       value="{{ request('min_occupancy') }}" placeholder="0"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
            </div>

            <div class="flex items-end">
                <button type="submit" class="btn btn-primary">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Occupancy Trend Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Occupancy Trend</h3>
                <div class="text-sm text-gray-500">Last 6 months</div>
            </div>
            <div class="h-80">
                <canvas id="occupancyChart" 
                        data-labels='{{ json_encode($occupancyChartLabels ?? ["Jan", "Feb", "Mar", "Apr", "May", "Jun"]) }}' 
                        data-values='{{ json_encode($occupancyChartData ?? [75, 82, 68, 90, 85, 88]) }}'></canvas>
            </div>
        </div>

        <!-- Room Status Distribution -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Room Status Distribution</h3>
                <div class="text-sm text-gray-500">Current status</div>
            </div>
            <div class="h-80">
                <canvas id="roomStatusChart" 
                        data-status-values='{{ json_encode($roomStatusData ?? [65, 30, 5]) }}'></canvas>
            </div>
        </div>
    </div>

    <!-- Property Performance Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Property Occupancy Performance</h3>
        </div>
        @if(isset($propertyStats) && $propertyStats->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Rooms</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Occupied</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Occupancy Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($propertyStats as $stat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $stat->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $stat->owner->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $stat->city }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($stat->address, 30) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $stat->total_rooms ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $stat->occupied_rooms ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $occupancyRate = $stat->total_rooms > 0 ? round(($stat->occupied_rooms / $stat->total_rooms) * 100, 1) : 0;
                                    $rateColor = $occupancyRate >= 80 ? 'text-green-600' : ($occupancyRate >= 60 ? 'text-yellow-600' : 'text-red-600');
                                    $widthClass = $occupancyRate >= 75 ? 'w-full' : ($occupancyRate >= 50 ? 'w-3/4' : ($occupancyRate >= 25 ? 'w-1/2' : 'w-1/4'));
                                    $bgColor = $occupancyRate >= 80 ? 'bg-green-600' : ($occupancyRate >= 60 ? 'bg-yellow-600' : 'bg-red-600');
                                @endphp
                                <div class="flex items-center">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium {{ $rateColor }}">{{ $occupancyRate }}%</div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1 relative">
                                            <div class="h-2 rounded-full {{ $bgColor }} absolute left-0 top-0 transition-all" data-width="{{ $occupancyRate }}"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($stat->monthly_revenue ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $performance = $occupancyRate >= 80 ? 'Excellent' : ($occupancyRate >= 60 ? 'Good' : 'Needs Improvement');
                                    $perfColor = $occupancyRate >= 80 ? 'bg-green-100 text-green-800' : ($occupancyRate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $perfColor }}">
                                    {{ $performance }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No properties found</h3>
                <p class="mt-1 text-sm text-gray-500">Properties will appear here once they have rooms and bookings.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Occupancy Trend Chart
        const occupancyCanvas = document.getElementById('occupancyChart');
        const occupancyCtx = occupancyCanvas.getContext('2d');
        const occupancyLabels = JSON.parse(occupancyCanvas.dataset.labels);
        const occupancyValues = JSON.parse(occupancyCanvas.dataset.values);
        
        const occupancyChart = new Chart(occupancyCtx, {
            type: 'line',
            data: {
                labels: occupancyLabels,
                datasets: [{
                    label: 'Occupancy Rate (%)',
                    data: occupancyValues,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Occupancy: ' + context.parsed.y + '%';
                            }
                        }
                    }
                }
            }
        });

        // Room Status Chart
        const statusCanvas = document.getElementById('roomStatusChart');
        const statusCtx = statusCanvas.getContext('2d');
        const statusValues = JSON.parse(statusCanvas.dataset.statusValues);
        
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Occupied', 'Available', 'Maintenance'],
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(239, 68, 68)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
        
        // Set progress bar widths
        document.querySelectorAll('.progress-bar').forEach(function(bar) {
            const width = bar.dataset.width;
            bar.style.width = width + '%';
        });
    });

    function exportReport() {
        const params = new URLSearchParams(window.location.search);
        window.open('{{ route("admin.reports.occupancy.export") }}?' + params.toString(), '_blank');
    }
</script>
@endpush
@endsection