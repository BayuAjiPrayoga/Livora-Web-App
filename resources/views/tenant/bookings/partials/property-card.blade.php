<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
    <!-- Property Image -->
    <div class="relative h-48 bg-gray-200">
        @if($property->images && is_array($property->images) && count($property->images) > 0)
            <img src="{{ Storage::url($property->images[0]) }}" alt="{{ $property->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        @endif
        
        <!-- Status Badge -->
        <div class="absolute top-3 right-3">
            @if($property->status === 'verified')
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    ✓ Verified
                </span>
            @else
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    Pending
                </span>
            @endif
        </div>

        <!-- Property Status -->
        @php
            $propertyStatus = $property->getPropertyStatus();
            $availableCount = $property->getAvailableRoomsCount();
            $totalCount = $property->getTotalRoomsCount();
        @endphp
        <div class="absolute bottom-3 left-3">
            <span class="bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                @if($propertyStatus === 'unavailable')
                    Semua Kamar Penuh
                @elseif($availableCount === 0)
                    Tidak Ada Kamar Tersedia
                @else
                    {{ $availableCount }} dari {{ $totalCount }} kamar tersedia
                @endif
            </span>
        </div>
    </div>

    <!-- Property Details -->
    <div class="p-4">
        <div class="mb-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $property->name }}</h3>
            <p class="text-sm text-gray-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $property->city }}{{ $property->district ? ', ' . $property->district : '' }}
            </p>
        </div>

        <!-- Property Description -->
        @if($property->description)
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                {{ Str::limit($property->description, 100) }}
            </p>
        @endif

        <!-- Owner Info -->
        <div class="flex items-center mb-3 text-sm text-gray-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Owner: {{ $property->owner->name }}
        </div>

        <!-- Rooms List -->
        @if($property->rooms->count() > 0)
            <div class="space-y-3">
                <h4 class="text-sm font-medium text-gray-900">Rooms Status:</h4>
                @foreach($property->rooms->take(3) as $room)
                    @php
                        $roomStatus = $room->getCurrentStatus();
                        $isAvailable = $roomStatus === 'available';
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-3 {{ $isAvailable ? 'hover:border-[#ff6900]' : 'opacity-75' }} transition-colors relative">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h5 class="font-medium text-gray-900">{{ $room->name }}</h5>
                                @if($room->description)
                                    <p class="text-sm text-gray-600">{{ Str::limit($room->description, 60) }}</p>
                                @endif
                                <!-- Room Status Badge -->
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($roomStatus === 'available') bg-green-100 text-green-800
                                        @elseif($roomStatus === 'occupied') bg-red-100 text-red-800
                                        @elseif($roomStatus === 'reserved') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $room->getStatusLabel() }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-[#ff6900]">
                                    Rp {{ number_format($room->price, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500">per month</p>
                            </div>
                        </div>
                        
                        <!-- Room Details -->
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-3">
                            <div class="flex items-center space-x-4">
                                @if($room->capacity)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        {{ $room->capacity }} person{{ $room->capacity > 1 ? 's' : '' }}
                                    </span>
                                @endif
                                @if($room->size)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                        </svg>
                                        {{ $room->size }}m²
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Book Button -->
                        @if($isAvailable)
                            <button type="button" 
                                    class="book-room-btn w-full bg-blue-600 text-white py-3 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors cursor-pointer"
                                    data-room-id="{{ $room->id }}"
                                    data-room-name="{{ $room->name }}"
                                    data-property-name="{{ $property->name }}"
                                    data-location="{{ $property->city }}"
                                    data-price="{{ $room->price }}">
                                📅 Book This Room
                            </button>
                        @else
                            <button type="button" disabled
                                    class="w-full bg-gray-400 text-white py-3 px-4 rounded-lg text-sm font-medium cursor-not-allowed">
                                @if($roomStatus === 'occupied')
                                    🔒 Currently Occupied
                                @elseif($roomStatus === 'reserved')  
                                    📋 Already Booked
                                @else
                                    ❌ Not Available
                                @endif
                            </button>
                        @endif
                    </div>
                @endforeach

                @if($property->rooms->count() > 3)
                    <div class="text-center">
                        <button type="button" class="text-[#ff6900] text-sm font-medium hover:underline">
                            View {{ $property->rooms->count() - 3 }} more rooms
                        </button>
                    </div>
                @endif

                @if($availableCount === 0 && $totalCount > 0)
                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-700 font-medium text-center">
                            🔒 Semua kamar sudah penuh atau tidak tersedia
                        </p>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-6">
                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">Property tidak memiliki kamar</p>
            </div>
        @endif

        <!-- Contact Info -->
        @if($property->phone)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="tel:{{ $property->phone }}" class="flex items-center text-sm text-gray-600 hover:text-[#ff6900]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Contact: {{ $property->phone }}
                </a>
            </div>
        @endif
    </div>
</div>