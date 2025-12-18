@extends('layouts.admin')

@section('title', 'Revenue Reports - LIVORA')

@section('page-title', 'Revenue Reports')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-semibold text-gray-900">Revenue Reports</h1>
            <p class="text-sm text-gray-600 mt-1">Analisis pendapatan dan performa finansial platform</p>
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
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                    <p class="text-sm text-green-600">+{{ $revenueGrowth ?? 0 }}% from last month</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Verified Payments</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $verifiedPayments ?? 0 }}</p>
                    <p class="text-sm text-blue-600">{{ $paymentRate ?? 0 }}% verification rate</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Properties</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeProperties ?? 0 }}</p>
                    <p class="text-sm text-purple-600">{{ $propertyGrowth ?? 0 }}% new this month</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeUsers ?? 0 }}</p>
                    <p class="text-sm text-orange-600">{{ $userGrowth ?? 0 }}% growth</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="period" class="block text-sm font-medium text-gray-700 mb-2">Time Period</label>
                <select name="period" id="period" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
                    <option value="7days" {{ request('period') == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30days" {{ request('period') == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="90days" {{ request('period') == '90days' ? 'selected' : '' }}>Last 90 Days</option>
                    <option value="1year" {{ request('period') == '1year' ? 'selected' : '' }}>Last Year</option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
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
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Revenue Trend</h3>
                <div class="text-sm text-gray-500">Last 30 days</div>
            </div>
            <div class="h-80">
                <canvas id="revenueChart" 
                        data-labels='{{ json_encode($revenueChartLabels ?? ["Jan", "Feb", "Mar", "Apr", "May", "Jun"]) }}' 
                        data-values='{{ json_encode($revenueChartData ?? [10000, 20000, 15000, 25000, 18000, 30000]) }}'></canvas>
            </div>
        </div>

        <!-- Payment Status Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Payment Status Distribution</h3>
                <div class="text-sm text-gray-500">Current period</div>
            </div>
            <div class="h-80">
                <canvas id="paymentStatusChart" 
                        data-status-values='{{ json_encode([$verifiedPayments ?? 85, $pendingPayments ?? 12, $rejectedPayments ?? 3]) }}'></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performing Properties -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Properties -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Top Performing Properties</h3>
            </div>
            <div class="px-6 py-4">
                @if(isset($topProperties) && $topProperties->count() > 0)
                    <div class="space-y-4">
                        @foreach($topProperties as $property)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $property->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $property->city }}</p>
                                <p class="text-sm text-gray-500">{{ $property->rooms_count ?? 0 }} rooms</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($property->revenue ?? 0, 0, ',', '.') }}</p>
                                <p class="text-sm text-green-600">{{ $property->bookings_count ?? 0 }} bookings</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No properties found</h3>
                        <p class="mt-1 text-sm text-gray-500">Properties will appear here once bookings are made.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent High-Value Transactions</h3>
            </div>
            <div class="px-6 py-4">
                @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentTransactions as $payment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $payment->booking->user->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $payment->booking->room->boardingHouse->name }}</p>
                                <p class="text-sm text-gray-500">{{ $payment->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                @php
                                    $statusColors = [
                                        'pending' => 'text-yellow-600',
                                        'verified' => 'text-green-600',
                                        'rejected' => 'text-red-600'
                                    ];
                                @endphp
                                <p class="text-sm {{ $statusColors[$payment->status] ?? 'text-gray-600' }}">
                                    {{ ucfirst($payment->status) }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                        <p class="mt-1 text-sm text-gray-500">Recent transactions will appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revenueCanvas = document.getElementById('revenueChart');
        const revenueCtx = revenueCanvas.getContext('2d');
        const revenueLabels = JSON.parse(revenueCanvas.dataset.labels);
        const revenueValues = JSON.parse(revenueCanvas.dataset.values);
        
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: revenueValues,
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
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Payment Status Chart
        const statusCanvas = document.getElementById('paymentStatusChart');
        const statusCtx = statusCanvas.getContext('2d');
        const statusValues = JSON.parse(statusCanvas.dataset.statusValues);
        
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Verified', 'Pending', 'Rejected'],
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
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
                    }
                }
            }
        });
    });

    function exportReport() {
        const params = new URLSearchParams(window.location.search);
        window.open('{{ route("admin.reports.revenue.export") }}?' + params.toString(), '_blank');
    }

    // Auto-toggle date inputs based on period selection
    document.getElementById('period').addEventListener('change', function() {
        const isCustom = this.value === 'custom';
        document.getElementById('start_date').disabled = !isCustom;
        document.getElementById('end_date').disabled = !isCustom;
    });
</script>
@endpush
@endsection