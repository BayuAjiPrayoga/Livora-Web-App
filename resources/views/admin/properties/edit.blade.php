@extends('layouts.admin')

@section('title', 'Edit Property')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Property</h1>
            <p class="text-gray-600 mt-1">Update property information and settings</p>
        </div>
        <a href="{{ route('admin.properties.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Properties
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Property Information</h2>
        </div>
        
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-700 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-red-700 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <form id="property-form" action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Property Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $property->name) }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Property Owner *</label>
                        <select name="user_id" id="user_id" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('user_id') border-red-500 @enderror">
                            <option value="">Select Owner</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" {{ old('user_id', $property->user_id) == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }} ({{ $owner->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $property->city) }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('city') border-red-500 @enderror">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="hidden" name="is_verified" value="0">
                        <input type="checkbox" name="is_verified" id="is_verified" value="1" {{ old('is_verified', $property->is_verified) ? 'checked' : '' }}
                               class="h-4 w-4 text-[#ff6900] focus:ring-livora-primary border-gray-300 rounded">
                        <label for="is_verified" class="ml-2 block text-sm text-gray-900">
                            Verified Property
                        </label>
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Full Address *</label>
                    <textarea name="address" id="address" rows="3" required
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('address') border-red-500 @enderror">{{ old('address', $property->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('description') border-red-500 @enderror"
                              placeholder="Describe the property features, amenities, and other details...">{{ old('description', $property->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price Range -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="price_range_start" class="block text-sm font-medium text-gray-700 mb-2">Price Range Start (Rp) *</label>
                        <input type="number" name="price_range_start" id="price_range_start" value="{{ old('price_range_start', $property->price_range_start) }}" required min="0"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('price_range_start') border-red-500 @enderror"
                               placeholder="500000">
                        @error('price_range_start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price_range_end" class="block text-sm font-medium text-gray-700 mb-2">Price Range End (Rp) *</label>
                        <input type="number" name="price_range_end" id="price_range_end" value="{{ old('price_range_end', $property->price_range_end) }}" required min="0"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('price_range_end') border-red-500 @enderror"
                               placeholder="2000000">
                        @error('price_range_end')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Images -->
                @if($property->images && count($property->images) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($property->images as $image)
                            <div class="relative">
                                <img src="{{ Storage::url($image) }}" alt="Property Image" class="w-full h-24 object-cover rounded-lg">
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Add New Images -->
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Add New Images</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900] @error('images') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Upload additional images (JPG, PNG, GIF). Maximum 5MB per file.</p>
                    @error('images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Settings -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div></div>
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $property->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-[#ff6900] focus:ring-livora-primary border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Active Property
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.properties.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <span id="submit-text">Update Property</span>
                        <span id="loading-text" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Updating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Prevent double submission
    document.getElementById('property-form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const loadingText = document.getElementById('loading-text');
        
        // Disable button and show loading
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingText.classList.remove('hidden');
        
        // Re-enable after 3 seconds as fallback
        setTimeout(() => {
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            loadingText.classList.add('hidden');
        }, 3000);
    });
</script>
@endpush
@endsection