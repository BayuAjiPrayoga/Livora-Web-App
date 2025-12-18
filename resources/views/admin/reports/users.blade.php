@extends('layouts.admin')

@section('title', 'User Analytics - LIVORA')

@section('page-title', 'User Analytics')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-semibold text-gray-900">User Analytics</h1>
            <p class="text-sm text-gray-600 mt-1">Analisis behavior dan demographics pengguna platform</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportReport()" class="btn btn-primary">
                Export Report
            </button>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers ?? 0 }}</p>
                    <p class="text-sm text-blue-600">+{{ $userGrowth ?? 0 }}% this month</p>
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
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $userStats['active_users'] ?? 0 }}</p>
                    <p class="text-sm text-green-600">{{ $totalUsers > 0 ? round(($userStats['active_users'] / $totalUsers) * 100, 1) : 0 }}% activity rate</p>
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
                    <p class="text-sm font-medium text-gray-600">Property Owners</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $propertyOwners ?? 0 }}</p>
                    <p class="text-sm text-purple-600">{{ $ownerPercentage ?? 0 }}% of users</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tenants</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tenants ?? 0 }}</p>
                    <p class="text-sm text-orange-600">{{ $tenantPercentage ?? 0 }}% of users</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- User Registration Trend -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">User Registration Trend</h3>
                <div class="text-sm text-gray-500">Last 12 months</div>
            </div>
            <div class="h-80">
                <canvas id="registrationChart" 
                        data-labels='{{ json_encode($registrationChartLabels ?? ["Jan", "Feb", "Mar", "Apr", "May", "Jun"]) }}' 
                        data-values='{{ json_encode($registrationChartData ?? [25, 32, 28, 45, 38, 52]) }}'></canvas>
            </div>
        </div>

        <!-- User Role Distribution -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">User Role Distribution</h3>
                <div class="text-sm text-gray-500">Current breakdown</div>
            </div>
            <div class="h-80">
                <canvas id="roleDistributionChart" 
                        data-role-values='{{ json_encode($roleDistributionData ?? [75, 20, 5]) }}'></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Geographic Distribution -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Geographic Distribution</h3>
                <p class="text-sm text-gray-500 mt-1">Users by city</p>
            </div>
            <div class="px-6 py-4">
                @if(isset($usersByCity) && $usersByCity->count() > 0)
                    <div class="space-y-4">
                        @foreach($usersByCity as $cityData)
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $cityData->city }}</h4>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-[#ff6900] h-2 rounded-full progress-bar" data-width="{{ $totalUsers > 0 ? (($cityData->user_count / $totalUsers) * 100) : 0 }}"></div>
                                </div>
                            </div>
                            <div class="text-right ml-4">
                                <p class="text-sm font-semibold text-gray-900">{{ $cityData->user_count }}</p>
                                <p class="text-xs text-gray-500">{{ round(($cityData->user_count / $totalUsers) * 100, 1) }}%</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No location data</h3>
                        <p class="mt-1 text-sm text-gray-500">Location data will appear here.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Activity -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">User Activity</h3>
                <p class="text-sm text-gray-500 mt-1">Engagement metrics</p>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Daily Active Users</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $dailyActiveUsers ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full progress-bar" data-width="{{ $totalUsers > 0 ? (($dailyActiveUsers ?? 0) / $totalUsers * 100) : 0 }}"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Weekly Active Users</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $weeklyActiveUsers ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full progress-bar" data-width="{{ $totalUsers > 0 ? (($weeklyActiveUsers ?? 0) / $totalUsers * 100) : 0 }}"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Monthly Active Users</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $monthlyActiveUsers ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full progress-bar" data-width="{{ $totalUsers > 0 ? (($monthlyActiveUsers ?? 0) / $totalUsers * 100) : 0 }}"></div>
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Session Metrics</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Avg. Session Duration</span>
                            <span class="text-sm font-medium text-gray-900">{{ $avgSessionDuration ?? 0 }}m</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Bounce Rate</span>
                            <span class="text-sm font-medium text-gray-900">{{ $bounceRate ?? 0 }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Pages per Session</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pagesPerSession ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Users -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Most Active Users</h3>
                <p class="text-sm text-gray-500 mt-1">By booking activity</p>
            </div>
            <div class="px-6 py-4">
                @if(isset($topUsers) && $topUsers->count() > 0)
                    <div class="space-y-4">
                        @foreach($topUsers as $user)
                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                            <div class="h-10 w-10 flex-shrink-0">
                                <div class="h- w- bg-gradient-to-br from-[#ff6900] to-[#ff8533] rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($user->role) }}</p>
                                <p class="text-xs text-gray-400">Joined {{ $user->created_at->format('M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ $user->bookings_count ?? 0 }}</p>
                                <p class="text-xs text-gray-500">bookings</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No active users</h3>
                        <p class="mt-1 text-sm text-gray-500">Active users will appear here.</p>
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
        // Registration Trend Chart
        const registrationCanvas = document.getElementById('registrationChart');
        const registrationCtx = registrationCanvas.getContext('2d');
        const registrationLabels = JSON.parse(registrationCanvas.dataset.labels);
        const registrationValues = JSON.parse(registrationCanvas.dataset.values);
        
        const registrationChart = new Chart(registrationCtx, {
            type: 'line',
            data: {
                labels: registrationLabels,
                datasets: [{
                    label: 'New Registrations',
                    data: registrationValues,
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
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'New Users: ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });

        // Role Distribution Chart
        const roleCanvas = document.getElementById('roleDistributionChart');
        const roleCtx = roleCanvas.getContext('2d');
        const roleValues = JSON.parse(roleCanvas.dataset.roleValues);
        
        const roleChart = new Chart(roleCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tenants', 'Property Owners', 'Admins'],
                datasets: [{
                    data: roleValues,
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(168, 85, 247)',
                        'rgb(59, 130, 246)'
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
        window.open('{{ route("admin.reports.users.export") }}?' + params.toString(), '_blank');
    }
</script>
@endpush
@endsection