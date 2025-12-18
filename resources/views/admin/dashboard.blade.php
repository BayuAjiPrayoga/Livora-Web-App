@extends('layouts.admin')

@section('title', 'Admin Dashboard - LIVORA')

@section('page-title', 'Dashboard Administrator')

@section('content')
<div class="p-6">
    <!-- System Health Alerts -->
    @if(isset($systemHealth['database_status']) && $systemHealth['database_status'] !== 'healthy')
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L5.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div>
                    <p class="font-medium">System Alert</p>
                    <p class="text-sm">Database connection issue detected. Please check system status.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Key Metrics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <div class="flex items-center">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['users']['total'] ?? 0) }}</p>
                        @if(($statistics['users']['growth_rate'] ?? 0) > 0)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                                +{{ $statistics['users']['growth_rate'] ?? 0 }}%
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $statistics['users']['new_this_month'] ?? 0 }} baru bulan ini</p>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($statistics['payments']['total_amount'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($statistics['payments']['this_month_amount'] ?? 0, 0, ',', '.') }} bulan ini</p>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['bookings']['total'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $statistics['bookings']['this_month'] ?? 0 }} baru bulan ini</p>
                </div>
            </div>
        </div>

        <!-- Occupancy Rate -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Tingkat Okupansi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['rooms']['occupancy_rate'] ?? 0 }}%</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $statistics['rooms']['occupied'] ?? 0 }}/{{ $statistics['rooms']['total'] ?? 0 }} kamar terisi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- User Statistics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik User</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Admin</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $statistics['users']['admins'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Mitra</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $statistics['users']['owners'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Tenant</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $statistics['users']['tenants'] ?? 0 }}</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="text-sm text-livora-primary hover:text-blue-700 font-medium">
                    Kelola Semua User →
                </a>
            </div>
        </div>

        <!-- Booking Statistics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Booking</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Pending</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $statistics['bookings']['pending'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Confirmed</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $statistics['bookings']['confirmed'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Active</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $statistics['bookings']['active'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Cancelled</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $statistics['bookings']['cancelled'] ?? 0 }}</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.bookings.index') }}" class="text-sm text-livora-primary hover:text-blue-700 font-medium">
                    Kelola Semua Booking →
                </a>
            </div>
        </div>

        <!-- Payment Statistics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pembayaran</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Pending</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $statistics['payments']['pending'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Verified</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $statistics['payments']['verified'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Rejected</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $statistics['payments']['rejected'] ?? 0 }}</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.payments.index') }}" class="text-sm text-livora-primary hover:text-blue-700 font-medium">
                    Kelola Semua Pembayaran →
                </a>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics Row -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Trends Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Revenue Trends</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">6M</button>
                    <button class="px-3 py-1 text-xs font-medium text-white bg-livora-primary rounded-lg">12M</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- User Growth Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">User Growth</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">6M</button>
                    <button class="px-3 py-1 text-xs font-medium text-white bg-livora-primary rounded-lg">12M</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities and System Health -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <!-- Recent Activities -->
        <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
            </div>
            <div class="p-6">
                @if(isset($recentActivities) && $recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities->take(8) as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($activity['icon'] === 'user-plus')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                            @elseif($activity['icon'] === 'calendar-plus')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            @elseif($activity['icon'] === 'credit-card')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            @elseif($activity['icon'] === 'headphones')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z"></path>
                                            @endif
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                    <p class="text-sm text-gray-600 truncate">{{ $activity['description'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $activity['time']->diffForHumans() }}</p>
                                </div>
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

        <!-- System Health Panel -->
        <div class="space-y-6">
            <!-- System Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">System Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Database</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ ($systemHealth['database_status'] ?? 'unknown') === 'healthy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($systemHealth['database_status'] ?? 'Unknown') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Uptime</span>
                        <span class="text-sm font-medium text-gray-900">{{ $systemHealth['uptime'] ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Response Time</span>
                        <span class="text-sm font-medium text-gray-900">{{ $systemHealth['response_time'] ?? 0 }}ms</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Active Users (24h)</span>
                        <span class="text-sm font-medium text-gray-900">{{ $systemHealth['active_users_24h'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Storage Usage -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Storage Usage</h3>
                <div class="space-y-3">
                    @php
                        $storagePercentage = min(($systemHealth['storage_usage']['used_percentage'] ?? 0), 100);
                    @endphp
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Storage Used</span>
                        <span class="text-sm font-medium text-gray-900">{{ $storagePercentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-livora-primary h-2 rounded-full progress-bar" 
                             data-width="{{ $storagePercentage }}"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Free: {{ $systemHealth['storage_usage']['free_space'] ?? 'N/A' }}</span>
                        <span>Total: {{ $systemHealth['storage_usage']['total_space'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.users.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Tambah User
                    </a>
                    <a href="{{ route('admin.properties.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Tambah Properti
                    </a>
                    <a href="{{ route('admin.reports.revenue') }}" class="w-full flex items-center justify-center px-4 py-2 bg-livora-primary text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Dashboard Data for Charts -->
@if(isset($analytics['revenue_trends']) && isset($analytics['user_growth']))
<script id="dashboard-data" type="application/json">
{
    "revenueData": {
        "labels": @json($analytics['revenue_trends']->pluck('month') ?? []),
        "values": @json($analytics['revenue_trends']->pluck('revenue') ?? [])
    },
    "userGrowthData": {
        "labels": @json($analytics['user_growth']->pluck('month') ?? []),
        "values": @json($analytics['user_growth']->pluck('users') ?? [])
    }
}
</script>
@endif
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
</script>
@endpush
@endsection