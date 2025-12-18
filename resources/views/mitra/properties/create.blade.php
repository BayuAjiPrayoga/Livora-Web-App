@extends('layouts.mitra')

@section('title', 'Tambah Properti - LIVORA')

@section('content')
<div class="bg-livora-background">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('mitra.properties.index') }}" class="hover:text-livora-primary">Properti Saya</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-livora-primary font-medium">Tambah Properti</span>
            </div>
            <h1 class="text-2xl font-bold text-livora-text">Tambah Properti Baru</h1>
            <p class="text-gray-600 mt-1">Lengkapi informasi properti kost Anda</p>
        </div>
    </div>

    <div class="p-6 pb-20">
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('mitra.properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Informasi Dasar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Property Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-livora-text mb-2">Nama Properti</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-livora-accent focus:border-transparent" 
                                   placeholder="Contoh: Kost Melati Jakarta Selatan" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="md:col-span-2">
                            <label for="slug" class="block text-sm font-medium text-livora-text mb-2">URL Slug</label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-livora-accent focus:border-transparent" 
                                   placeholder="kost-melati-jakarta-selatan">
                            <p class="mt-1 text-xs text-gray-500">URL unik untuk properti ini (opsional, akan dibuat otomatis)</p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-livora-text mb-2">Kota</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-livora-accent focus:border-transparent" 
                                   placeholder="Jakarta Selatan" required>
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-livora-text mb-2">Status</label>
                            <select id="is_active" name="is_active" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-livora-accent focus:border-transparent">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Lokasi & Alamat</h3>
                    
                    <!-- Address -->
                    <div class="mb-6">
                        <label for="address" class="block text-sm font-medium text-livora-text mb-2">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-livora-accent focus:border-transparent" 
                                  placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan" required>{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Coordinates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-livora-text mb-2">Latitude (Opsional)</label>
                            <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-livora-accent focus:border-transparent" 
                                   placeholder="-6.200000">
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-medium text-livora-text mb-2">Longitude (Opsional)</label>
                            <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-livora-accent focus:border-transparent" 
                                   placeholder="106.816666">
                            @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-livora-text mb-4">Deskripsi & Gambar</h3>
                    
                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-livora-text mb-2">Deskripsi Properti</label>
                        <textarea id="description" name="description" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-livora-accent focus:border-transparent" 
                                  placeholder="Deskripsikan properti kost Anda, fasilitas yang tersedia, dan keunggulannya...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Images -->
                    <div>
                        <label for="images" class="block text-sm font-medium text-livora-text mb-2">Gambar Properti</label>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-livora-accent focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Upload hingga 10 gambar (JPEG, PNG, JPG, GIF, max 2MB per file)</p>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('mitra.properties.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-livora-accent focus:ring-offset-2 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-livora-accent border border-transparent rounded-md font-semibold text-white hover:bg-livora-primary focus:bg-livora-primary active:bg-livora-primary focus:outline-none focus:ring-2 focus:ring-livora-accent focus:ring-offset-2 transition ease-in-out duration-150">
                        Simpan Properti
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-generate slug from property name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    
    if (document.getElementById('slug').value === '') {
        document.getElementById('slug').value = slug;
    }
});
</script>
@endsection