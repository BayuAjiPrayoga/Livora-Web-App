@extends('layouts.mitra')

@section('title', 'Tambah Kamar Baru - ' . $property->name . ' - LIVORA')

@section('content')
<div class="bg-livora-background min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('mitra.properties.index') }}" class="hover:text-[#ff6900]">Properti Saya</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('mitra.properties.show', $property->id) }}" class="hover:text-[#ff6900]">{{ $property->name }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('mitra.rooms.index', $property->id) }}" class="hover:text-[#ff6900]">Kelola Kamar</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#ff6900] font-medium">Tambah Kamar</span>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-livora-text">Tambah Kamar Baru</h1>
                    <p class="text-gray-600 mt-1">Tambahkan kamar baru untuk {{ $property->name }}</p>
                </div>
                <a href="{{ route('mitra.rooms.index', $property->id) }}" 
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

        <form action="{{ route('mitra.rooms.store', $property->id) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
            @csrf
            
            <div class="bg-white rounded-lg shadow-md">
                <!-- Section 1: Informasi Dasar Kamar -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-livora-text flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Informasi Dasar Kamar
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Detail utama tentang kamar yang akan ditambahkan</p>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Kamar -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Kamar <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-[#ff6900] focus:ring-livora-accent @error('name') border-red-300 @enderror"
                                   placeholder="Contoh: Kamar A1, Kamar Deluxe, Studio Premium">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga per Bulan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" name="price" id="price" 
                                       value="{{ old('price') }}"
                                       min="0" step="1000"
                                       class="w-full pl-10 rounded-lg border-gray-300 focus:border-[#ff6900] focus:ring-livora-accent @error('price') border-red-300 @enderror"
                                       placeholder="1500000">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kapasitas -->
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                Kapasitas <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="capacity" id="capacity" 
                                        class="w-full rounded-lg border-gray-300 focus:border-[#ff6900] focus:ring-livora-accent @error('capacity') border-red-300 @enderror">
                                    <option value="">Pilih kapasitas</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('capacity') == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'orang' : 'orang' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ukuran -->
                        <div>
                            <label for="size" class="block text-sm font-medium text-gray-700 mb-2">
                                Ukuran Kamar (m²)
                            </label>
                            <div class="relative">
                                <input type="number" name="size" id="size" 
                                       value="{{ old('size') }}"
                                       min="0" step="0.1"
                                       class="w-full rounded-lg border-gray-300 focus:border-[#ff6900] focus:ring-livora-accent @error('size') border-red-300 @enderror"
                                       placeholder="25.5">
                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">m²</span>
                            </div>
                            @error('size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Ketersediaan -->
                        <div>
                            <label for="is_available" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Ketersediaan <span class="text-red-500">*</span>
                            </label>
                            <select name="is_available" id="is_available" 
                                    class="w-full rounded-lg border-gray-300 focus:border-[#ff6900] focus:ring-livora-accent @error('is_available') border-red-300 @enderror">
                                <option value="">Pilih status</option>
                                <option value="1" {{ old('is_available') == '1' ? 'selected' : '' }}>Tersedia</option>
                                <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>Terisi</option>
                            </select>
                            @error('is_available')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Kamar
                        </label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full rounded-lg border-gray-300 focus:border-[#ff6900] focus:ring-livora-accent @error('description') border-red-300 @enderror"
                                  placeholder="Deskripsikan fasilitas kamar, kondisi, dan keunggulannya...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Deskripsi yang detail dapat menarik minat calon penyewa</p>
                    </div>
                </div>

                <!-- Section 1.5: Fasilitas Kamar -->
                <div class="px-6 py-4 border-t border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-livora-text flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Fasilitas Kamar
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Pilih fasilitas yang tersedia di kamar ini</p>
                </div>
                
                <div class="p-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Pilih Fasilitas <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @forelse($facilities as $facility)
                                <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors group">
                                    <input type="checkbox" 
                                           name="facilities[]" 
                                           value="{{ $facility->id }}"
                                           {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                    <span class="ml-3 text-sm">
                                        @if($facility->icon)
                                            <span class="mr-1">{!! $facility->icon !!}</span>
                                        @endif
                                        <span class="font-medium text-gray-700 group-hover:text-orange-600">{{ $facility->name }}</span>
                                    </span>
                                </label>
                            @empty
                                <div class="col-span-full text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Belum ada fasilitas yang tersedia.</p>
                                    <p class="text-xs text-gray-400 mt-1">Hubungi admin untuk menambahkan fasilitas.</p>
                                </div>
                            @endforelse
                        </div>
                        @error('facilities')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Pilih minimal 1 fasilitas yang tersedia di kamar ini</p>
                    </div>
                </div>

                <!-- Section 2: Foto Kamar -->
                <div class="px-6 py-4 border-t border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-livora-text flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Foto Kamar
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Upload foto-foto kamar untuk menarik calon penyewa</p>
                </div>
                
                <div class="p-6">
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Foto Kamar
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#ff6900] transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                        <span>Upload foto</span>
                                        <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, JPEG hingga 2MB per file (maksimal 10 foto)
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
                    <a href="{{ route('mitra.rooms.index', $property->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Kamar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for dynamic functionality -->
<script>
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
</script>
@endsection