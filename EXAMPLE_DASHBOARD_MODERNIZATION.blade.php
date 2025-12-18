<!-- 
  LIVORA Dashboard - BEFORE vs AFTER Example
  File: resources/views/mitra/dashboard.blade.php
  
  Ini contoh bagaimana menggunakan modern components di dashboard existing.
  TIDAK PERLU RUSH! Migrate gradually page by page.
-->

<!-- ============================================
     BEFORE (Current - Masih Works Fine!)
     ============================================ -->
<div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-livora-accent">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">Pendapatan Bulan Ini</p>
            <p class="text-2xl font-bold text-livora-text">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
            <div class="flex items-center mt-2">
                <span class="text-xs font-medium {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%
                </span>
                <span class="text-xs text-gray-500 ml-1">dari bulan lalu</span>
            </div>
        </div>
        <div class="p-3 bg-livora-accent bg-opacity-10 rounded-full">
            <svg class="w-8 h-8 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
        </div>
    </div>
</div>

<!-- ============================================
     AFTER (Modern - Recommended for New/Updated Pages)
     ============================================ -->
<div class="stats-card animate-fade-in">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-gray-500 text-sm font-medium">Pendapatan Bulan Ini</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
            <div class="flex items-center mt-2">
                <span class="badge {{ $revenueGrowth >= 0 ? 'badge-success' : 'badge-danger' }}">
                    {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ abs($revenueGrowth) }}%
                </span>
                <span class="text-xs text-gray-500 ml-2">dari bulan lalu</span>
            </div>
        </div>
        <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-100 rounded-2xl">
            <svg class="w-8 h-8 text-livora-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
        </div>
    </div>
</div>

<!-- ============================================
     WHY MODERNIZE?
     ============================================
     
     ✅ Benefits of Modern Version:
     
     1. SIMPLER CODE
        - border-livora-accent → Otomatis di stats-card
        - shadow-md → Otomatis di stats-card
        - rounded-lg → Otomatis di stats-card (rounded-2xl lebih smooth)
     
     2. CONSISTENCY
        - Semua stats cards punya style yang sama
        - Tidak perlu repeat border-l-4, shadow, padding
     
     3. ANIMATIONS
        - animate-fade-in → Smooth entrance
        - Bisa tambah stagger: style="animation-delay: 0.1s"
     
     4. MODERN AESTHETICS
        - Rounded corners lebih besar (2xl vs lg)
        - Gradient backgrounds (from-orange-100 to-amber-100)
        - Badge components untuk growth indicators
     
     5. ACCESSIBILITY
        - Better semantic HTML structure
        - Better color contrast
        - Focus states handled automatically
     
     ============================================ -->

<!-- ============================================
     FULL DASHBOARD EXAMPLE - Modern Version
     ============================================ -->
@extends('layouts.mitra')

@section('title', 'Dashboard Mitra - LIVORA')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b sticky top-0 z-10 animate-slide-down">
        <div class="container-custom py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard Mitra</h1>
                    <p class="text-gray-500 mt-1">Selamat datang di LIVORA - Live Better, Stay Better</p>
                </div>
                <button class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Kost
                </button>
            </div>
        </div>
    </div>

    <div class="container-custom py-8 space-y-8">
        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Monthly Revenue Card -->
            <div class="stats-card animate-fade-in">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-gray-500 text-sm font-medium">Pendapatan Bulan Ini</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                        </p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="badge {{ $revenueGrowth >= 0 ? 'badge-success' : 'badge-danger' }}">
                                {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ abs($revenueGrowth) }}%
                            </span>
                            <span class="text-xs text-gray-500">dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-100 rounded-2xl">
                        <svg class="w-8 h-8 text-livora-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="stats-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-gray-500 text-sm font-medium">Total Pendapatan</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-2">Keseluruhan waktu</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-emerald-100 to-green-100 rounded-2xl">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Tenants Card -->
            <div class="stats-card animate-fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-gray-500 text-sm font-medium">Penyewa Aktif</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeTenants }}</p>
                        <p class="text-xs text-gray-500 mt-2">Total {{ $totalRooms }} kamar</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Payments Card -->
            <div class="stats-card animate-fade-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-gray-500 text-sm font-medium">Pembayaran Pending</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingPayments }}</p>
                        <p class="text-xs text-gray-500 mt-2">Perlu verifikasi</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-amber-100 to-yellow-100 rounded-2xl">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="modern-card animate-slide-up">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('mitra.payments.index') }}" 
                   class="group p-4 rounded-xl border-2 border-gray-200 hover:border-livora-500 transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-orange-100 rounded-xl group-hover:bg-livora-500 transition-colors">
                            <svg class="w-6 h-6 text-livora-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Verifikasi Pembayaran</p>
                            <p class="text-sm text-gray-500">{{ $pendingPayments }} pending</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('mitra.bookings.index') }}" 
                   class="group p-4 rounded-xl border-2 border-gray-200 hover:border-livora-500 transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-100 rounded-xl group-hover:bg-blue-600 transition-colors">
                            <svg class="w-6 h-6 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Kelola Booking</p>
                            <p class="text-sm text-gray-500">Lihat semua booking</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('mitra.reports.index') }}" 
                   class="group p-4 rounded-xl border-2 border-gray-200 hover:border-livora-500 transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-emerald-100 rounded-xl group-hover:bg-emerald-600 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Laporan Keuangan</p>
                            <p class="text-sm text-gray-500">Download laporan</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="modern-card animate-slide-up" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Aktivitas Terbaru</h2>
                <a href="#" class="btn btn-outline btn-sm">Lihat Semua</a>
            </div>

            @if($recentActivities->isEmpty())
                <div class="empty-state">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <h3 class="empty-state-title">Belum Ada Aktivitas</h3>
                    <p class="empty-state-text">
                        Aktivitas terbaru akan muncul di sini
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Aktivitas</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $activity)
                                <tr>
                                    <td>
                                        <span class="text-sm text-gray-600">
                                            {{ $activity->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $activity->title }}</p>
                                            <p class="text-sm text-gray-500">{{ $activity->description }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $activity->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-semibold text-gray-900">
                                            Rp {{ number_format($activity->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

<!-- ============================================
     MIGRATION TIPS
     ============================================
     
     🎯 HOW TO MIGRATE GRADUALLY:
     
     1. START WITH NEW FEATURES
        - Feature baru? Langsung pakai modern components
        - Tidak perlu touch existing code
     
     2. UPDATE HIGH-IMPACT PAGES FIRST
        - Dashboard (most visible)
        - Login/Register pages
        - Landing pages
     
     3. LEAVE LOW-PRIORITY PAGES
        - Old admin pages? Biarkan dulu
        - Internal tools? Migrate later
        - Reports? As-is OK
     
     4. TEST EACH PAGE
        - Visual check
        - Functional test
        - Mobile responsive
     
     5. NO RUSH!
        - Existing code masih works
        - Migrate 1-2 pages per sprint
        - Focus on new features
     
     ============================================ -->
