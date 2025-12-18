@extends('layouts.admin')

@section('title', 'Property Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $property->name }}</h1>
            <p class="text-gray-600 mt-1">Property details and statistics</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.properties.edit', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Property
            </a>
            <a href="{{ route('admin.properties.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Properties
            </a>
        </div>
    </div>

    <!-- Property Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Property Info -->
        <div class="lg:col-span-2">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Basic Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Property Name</label>
                            <p class="text-gray-900">{{ $property->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Owner</label>
                            <p class="text-gray-900">{{ $property->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">City</label>
                            <p class="text-gray-900">{{ $property->city }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <div class="flex items-center space-x-2">
                                @if($property->is_verified)
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Verified</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                @endif
                                
                                @if($property->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                            <p class="text-gray-900">{{ $property->address }}</p>
                        </div>
                        @if($property->description)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                            <p class="text-gray-900">{{ $property->description }}</p>
                        </div>
                        @endif
                        @if($property->price_range_start && $property->price_range_end)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Price Range</label>
                            <p class="text-gray-900">Rp {{ number_format($property->price_range_start) }} - Rp {{ number_format($property->price_range_end) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Property Images -->
            @if($property->images && count($property->images) > 0)
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Property Images</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($property->images as $image)
                            <div class="relative">
                                <img src="{{ Storage::url($image) }}" alt="Property Image" class="w-full h-32 object-cover rounded-lg">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Statistics Sidebar -->
        <div>
            <!-- Statistics Card -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Statistics</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Rooms</span>
                            <span class="font-semibold text-gray-900">{{ $stats['total_rooms'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Occupied Rooms</span>
                            <span class="font-semibold text-gray-900">{{ $stats['occupied_rooms'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Bookings</span>
                            <span class="font-semibold text-gray-900">{{ $stats['total_bookings'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Active Bookings</span>
                            <span class="font-semibold text-gray-900">{{ $stats['active_bookings'] ?? 0 }}</span>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Monthly Revenue</span>
                            <span class="font-semibold text-green-600">Rp {{ number_format($stats['monthly_revenue'] ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Revenue</span>
                            <span class="font-semibold text-green-600">Rp {{ number_format($stats['total_revenue'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Info Card -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Property Info</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div>
                            <span class="block text-sm text-gray-500">Created</span>
                            <span class="text-gray-900">{{ $property->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-500">Last Updated</span>
                            <span class="text-gray-900">{{ $property->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-500">Slug</span>
                            <span class="text-gray-900 text-xs break-all">{{ $property->slug }}</span>
                        </div>
                        @if($property->latitude && $property->longitude)
                        <div>
                            <span class="block text-sm text-gray-500">Coordinates</span>
                            <span class="text-gray-900 text-xs">{{ $property->latitude }}, {{ $property->longitude }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection