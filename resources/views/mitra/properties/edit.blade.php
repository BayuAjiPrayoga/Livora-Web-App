@extends('layouts.mitra')

@section('title', 'Edit ' . $property->name . ' - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('mitra.properties.index') }}" class="hover:text-livora-primary">Properti Saya</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('mitra.properties.show', $property->id) }}" class="hover:text-livora-primary">{{ $property->name }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-livora-primary font-medium">Edit Properti</span>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-livora-text">Edit Properti</h1>
                    <p class="text-gray-600 mt-1">Update informasi properti {{ $property->name }}</p>
                </div>
                <a href="{{ route('mitra.properties.show', $property->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <div class="font-bold">Terjadi kesalahan:</div>
            <ul class="mt-2 list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('mitra.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-lg shadow-md">
                <!-- Section 1: Informasi Dasar -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-livora-text flex items-center">
                        <svg class="w-5 h-5 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Informasi Dasar Properti
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Informasi utama tentang properti kost Anda</p>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Properti -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Properti <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $property->name) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('name') border-red-300 @enderror"
                                   placeholder="Contoh: Kost Nyaman Pusat Kota"
                                   onkeyup="generateSlug()">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="md:col-span-2">
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Slug <span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    livora.com/kost/
                                </span>
                                <input type="text" name="slug" id="slug" 
                                       value="{{ old('slug', $property->slug) }}"
                                       class="flex-1 rounded-r-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('slug') border-red-300 @enderror"
                                       placeholder="kost-nyaman-pusat-kota">
                            </div>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">URL ini akan digunakan untuk halaman publik properti Anda</p>
                        </div>

                        <!-- Kota -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="city" id="city" 
                                   value="{{ old('city', $property->city) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('city') border-red-300 @enderror"
                                   placeholder="Contoh: Jakarta">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Properti
                            </label>
                            <select name="is_active" id="is_active" 
                                    class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('is_active') border-red-300 @enderror">
                                <option value="1" {{ old('is_active', $property->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $property->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" id="address" rows="3" 
                                  class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('address') border-red-300 @enderror"
                                  placeholder="Masukkan alamat lengkap properti...">{{ old('address', $property->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Properti
                        </label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('description') border-red-300 @enderror"
                                  placeholder="Ceritakan tentang properti Anda, fasilitas yang tersedia, dan keunggulannya...">{{ old('description', $property->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Deskripsi yang menarik dapat meningkatkan minat calon penyewa</p>
                    </div>
                </div>
                
                <!-- Section 2: Lokasi & Koordinat -->
                <div class="px-6 py-4 border-t border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-livora-text flex items-center">
                        <svg class="w-5 h-5 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Koordinat Lokasi
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Koordinat GPS untuk memudahkan calon penyewa menemukan lokasi (opsional)</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Latitude -->
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                                Latitude
                            </label>
                            <input type="number" name="latitude" id="latitude" step="any"
                                   value="{{ old('latitude', $property->latitude) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('latitude') border-red-300 @enderror"
                                   placeholder="Contoh: -6.2088">
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Longitude -->
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                                Longitude
                            </label>
                            <input type="number" name="longitude" id="longitude" step="any"
                                   value="{{ old('longitude', $property->longitude) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-livora-accent focus:ring-livora-accent @error('longitude') border-red-300 @enderror"
                                   placeholder="Contoh: 106.8456">
                            @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        <strong>Tips:</strong> Anda bisa mendapatkan koordinat dari Google Maps dengan klik kanan pada lokasi dan pilih koordinat yang muncul
                    </p>
                </div>

                <!-- Section 3: Foto Properti -->
                <div class="px-6 py-4 border-t border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-livora-text flex items-center">
                        <svg class="w-5 h-5 mr-2 text-livora-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Foto Properti
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Upload foto terbaru atau tambah foto baru untuk properti</p>
                </div>
                
                <div class="p-6">
                    <!-- Current Images -->
                    @if($property->images && count($property->images) > 0)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Foto Saat Ini</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($property->images as $index => $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image) }}" alt="Property Image" 
                                     class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <button type="button" onclick="removeCurrentImage(this)" data-image-index="{{ $index }}"
                                            class="text-white hover:text-red-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" name="existing_images[]" value="{{ $image }}" id="existing_image_{{ $index }}">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- New Images Upload -->
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                            Tambah Foto Baru
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-livora-accent transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-livora-accent hover:text-livora-primary focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-livora-accent">
                                        <span>Upload foto</span>
                                        <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, JPEG hingga 2MB per file
                                </p>
                            </div>
                        </div>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden">
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
                    <a href="{{ route('mitra.properties.show', $property->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-livora-primary border border-transparent rounded-md font-semibold text-white hover:bg-blue-800 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Properti
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for dynamic functionality -->
<script>
// Generate slug from name
function generateSlug() {
    const name = document.getElementById('name').value;
    const slug = name
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
        .replace(/\s+/g, '-')         // Replace spaces with hyphens
        .replace(/-+/g, '-')          // Replace multiple hyphens with single
        .trim();
    
    document.getElementById('slug').value = slug;
}

// Preview uploaded images
function previewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" 
                             class="w-full h-24 object-cover rounded-lg border border-gray-200">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                            <button type="button" onclick="removePreviewImage(this, ${index})"
                                    class="text-white hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        preview.classList.add('hidden');
    }
}

// Remove preview image
function removePreviewImage(button, index) {
    const preview = document.getElementById('imagePreview');
    const input = document.getElementById('images');
    
    // Remove the preview element
    button.closest('.relative').remove();
    
    // Create new FileList without the removed file
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    
    // Hide preview if no more files
    if (input.files.length === 0) {
        preview.classList.add('hidden');
    }
}

// Remove current image
function removeCurrentImage(button) {
    const index = button.dataset.imageIndex;
    const imageInput = document.getElementById('existing_image_' + index);
    if (imageInput) {
        imageInput.remove();
    }
    
    // Remove the image container
    const container = button.closest('.relative');
    if (container) {
        container.remove();
    }
}
</script>
@endsection