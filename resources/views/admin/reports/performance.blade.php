@extends('layouts.admin')

@section('title', 'Performance Reports - LIVORA')

@section('page-title', 'Performance Reports')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-semibold text-gray-900">Performance Reports</h1>
            <p class="text-sm text-gray-600 mt-1">Analisis performa sistem dan key performance indicators</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportReport()" class="bg-livora-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                Export Report
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Booking Conversion</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $bookingConversion ?? 0 }}%</p>
                    <p class="text-sm {{ ($conversionChange ?? 0) > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ ($conversionChange ?? 0) > 0 ? '+' : '' }}{{ $conversionChange ?? 0 }}% from last month
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg. Revenue per User</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($avgRevenuePerUser ?? 0, 0, ',', '.') }}</p>
                    <p class="text-sm text-green-600">{{ $arpuGrowth ?? 0 }}% growth</p>
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
                    <p class="text-sm font-medium text-gray-600">Avg. Response Time</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $avgResponseTime ?? 0 }}h</p>
                    <p class="text-sm text-gray-500">ticket resolution</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Customer Satisfaction</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $customerSatisfaction ?? 0 }}%</p>
                    <p class="text-sm text-purple-600">{{ $satisfactionScore ?? 0 }}/5 rating</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Booking Performance -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Booking Performance</h3>
                <div class="text-sm text-gray-500">Last 30 days</div>
            </div>
            <div class="h-80">
                <canvas id="bookingPerformanceChart" 
                        data-labels='{{ json_encode($bookingChartLabels ?? ["Week 1", "Week 2", "Week 3", "Week 4"]) }}' 
                        data-successful='{{ json_encode($successfulBookings ?? [45, 52, 38, 61]) }}' 
                        data-failed='{{ json_encode($failedBookings ?? [5, 8, 3, 7]) }}'></canvas>
            </div>
        </div>

        <!-- User Acquisition -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">User Acquisition</h3>
                <div class="text-sm text-gray-500">Monthly growth</div>
            </div>
            <div class="h-80">
                <canvas id="userAcquisitionChart" 
                        data-labels='{{ json_encode($userChartLabels ?? ["Jan", "Feb", "Mar", "Apr", "May", "Jun"]) }}' 
                        data-new-users='{{ json_encode($newUsers ?? [120, 150, 180, 200, 170, 220]) }}' 
                        data-active-users='{{ json_encode($activeUsersData ?? [350, 420, 480, 520, 490, 580]) }}'></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Performance Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Performing Properties -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Top Performing Properties</h3>
                <p class="text-sm text-gray-500 mt-1">By booking conversion rate</p>
            </div>
            <div class="px-6 py-4">
                @if(isset($topPerformingProperties) && $topPerformingProperties->count() > 0)
                    <div class="space-y-4">
                        @foreach($topPerformingProperties as $property)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $property->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $property->city }}</p>
                                <div class="flex items-center mt-1">
                                    <div class="flex text-yellow-400">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-4 h-4 {{ $i < ($property->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-500 ml-2">{{ $property->rating ?? 0 }}/5</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900">{{ $property->conversion_rate ?? 0 }}%</p>
                                <p class="text-sm text-green-600">{{ $property->total_bookings ?? 0 }} bookings</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No data available</h3>
                        <p class="mt-1 text-sm text-gray-500">Performance data will appear here.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- System Performance -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">System Performance</h3>
                <p class="text-sm text-gray-500 mt-1">Technical metrics</p>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900">System Uptime</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $systemUptime ?? 99.9 }}%</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900">Page Load Time</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $pageLoadTime ?? 1.2 }}s</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900">API Response Time</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $apiResponseTime ?? 200 }}ms</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900">Error Rate</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $errorRate ?? 0.1 }}%</span>
                </div>

                <div class="pt-4 border-t">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Database Performance</span>
                        <span class="text-sm font-medium text-green-600">Optimal</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full progress-bar" data-width="{{ $dbPerformance ?? 95 }}"></div>
                    </div>
                </div>

                <div class="pt-2">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Server Resources</span>
                        <span class="text-sm font-medium text-blue-600">{{ $serverLoad ?? 65 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full progress-bar" data-width="{{ $serverLoad ?? 65 }}"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Support Metrics -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Customer Support</h3>
                <p class="text-sm text-gray-500 mt-1">Support performance metrics</p>
            </div>
            <div class="px-6 py-4 space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Ticket Resolution Rate</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $ticketResolutionRate ?? 85 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full progress-bar" data-width="{{ $ticketResolutionRate ?? 85 }}"></div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Response Time Distribution</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">< 1 hour</span>
                            <span class="text-xs font-medium text-gray-900">{{ $responseTime1h ?? 60 }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">1-4 hours</span>
                            <span class="text-xs font-medium text-gray-900">{{ $responseTime4h ?? 25 }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">4-24 hours</span>
                            <span class="text-xs font-medium text-gray-900">{{ $responseTime24h ?? 12 }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">> 24 hours</span>
                            <span class="text-xs font-medium text-gray-900">{{ $responseTimeOver24h ?? 3 }}%</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Ticket Categories</h4>
                    <div class="space-y-2">
                        @php
                            $ticketCategories = [
                                'Booking Issues' => $bookingIssues ?? 35,
                                'Payment Problems' => $paymentProblems ?? 28,
                                'Property Questions' => $propertyQuestions ?? 20,
                                'Technical Support' => $technicalSupport ?? 12,
                                'Other' => $otherTickets ?? 5
                            ];
                        @endphp
                        @foreach($ticketCategories as $category => $percentage)
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">{{ $category }}</span>
                            <span class="text-xs font-medium text-gray-900">{{ $percentage }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Booking Performance Chart
        const bookingCanvas = document.getElementById('bookingPerformanceChart');
        const bookingCtx = bookingCanvas.getContext('2d');
        const bookingLabels = JSON.parse(bookingCanvas.dataset.labels);
        const successfulBookings = JSON.parse(bookingCanvas.dataset.successful);
        const failedBookings = JSON.parse(bookingCanvas.dataset.failed);
        
        const bookingChart = new Chart(bookingCtx, {
            type: 'bar',
            data: {
                labels: bookingLabels,
                datasets: [{
                    label: 'Successful Bookings',
                    data: successfulBookings,
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                }, {
                    label: 'Failed Bookings',
                    data: failedBookings,
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: true },
                    y: { stacked: true, beginAtZero: true }
                }
            }
        });

        // User Acquisition Chart
        const userCanvas = document.getElementById('userAcquisitionChart');
        const userCtx = userCanvas.getContext('2d');
        const userLabels = JSON.parse(userCanvas.dataset.labels);
        const newUsers = JSON.parse(userCanvas.dataset.newUsers);
        const activeUsers = JSON.parse(userCanvas.dataset.activeUsers);
        
        const userChart = new Chart(userCtx, {
            type: 'line',
            data: {
                labels: userLabels,
                datasets: [{
                    label: 'New Users',
                    data: newUsers,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true
                }, {
                    label: 'Active Users',
                    data: activeUsers,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
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
        window.open('{{ route("admin.reports.performance.export") }}?' + params.toString(), '_blank');
    }
</script>
@endpush
@endsection