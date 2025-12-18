@extends('layouts.admin')

@section('title', 'Detail User - LIVORA')

@section('page-title', 'Detail User')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Detail User</h1>
            <p class="text-sm text-gray-600 mt-1">Informasi lengkap user {{ $user->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- User Info and Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="text-center">
                    <!-- Avatar -->
                    <div class="mx-auto h-24 w-24 mb-4">
                        @if($user->avatar)
                            <img class="h-24 w-24 rounded-full object-cover mx-auto" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                        @else
                            <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center mx-auto">
                                <span class="text-xl font-medium text-gray-700">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Name and Role -->
                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2
                        {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 
                           ($user->role === 'mitra' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    
                    <!-- Status -->
                    <div class="mt-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                @if($user->is_active)
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mt-6 border-t border-gray-200 pt-6 space-y-3">
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-600">{{ $user->email }}</span>
                    </div>
                    
                    @if($user->phone)
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-600">{{ $user->phone }}</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-600">Joined {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    @if($user->last_login_at)
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-600">Last login {{ $user->last_login_at->diffForHumans() }}</span>
                        </div>
                    @endif
                </div>

                <!-- Additional Info -->
                @if($user->address || $user->date_of_birth || $user->gender)
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Additional Information</h4>
                        <div class="space-y-2">
                            @if($user->date_of_birth)
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-600">{{ $user->date_of_birth->format('M d, Y') }}</span>
                                </div>
                            @endif
                            
                            @if($user->gender)
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-gray-600">{{ $user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</span>
                                </div>
                            @endif
                            
                            @if($user->address)
                                <div class="flex items-start text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-gray-600">{{ $user->address }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Overview -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['total_bookings'] }}</div>
                        <div class="text-sm text-gray-600">Total Bookings</div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['active_bookings'] }}</div>
                        <div class="text-sm text-gray-600">Active Bookings</div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">Rp {{ number_format($stats['total_payments'], 0, ',', '.') }}</div>
                        <div class="text-sm text-gray-600">Total Payments</div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $stats['total_tickets'] }}</div>
                        <div class="text-sm text-gray-600">Support Tickets</div>
                    </div>
                </div>
            </div>

            @if($user->role === 'mitra' && isset($stats['total_properties']))
                <!-- Mitra Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Property Statistics</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-600">{{ $stats['total_properties'] }}</div>
                            <div class="text-sm text-gray-600">Properties</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-blue-600">{{ $stats['total_rooms'] }}</div>
                            <div class="text-sm text-gray-600">Total Rooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-orange-600">{{ $stats['occupied_rooms'] }}</div>
                            <div class="text-sm text-gray-600">Occupied Rooms</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
                </div>
                
                <div class="space-y-4">
                    @if($user->bookings->count() > 0)
                        @foreach($user->bookings->take(5) as $booking)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        Booking {{ $booking->room->boardingHouse->name ?? 'Property' }} - {{ $booking->room->name ?? 'Room' }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $booking->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500">No recent activities</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection