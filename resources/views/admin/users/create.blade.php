@extends('layouts.admin')

@section('title', 'Tambah User - LIVORA')

@section('page-title', 'Tambah User')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-semibold text-gray-900">Tambah User Baru</h1>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                    <select name="role" id="role" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('role') border-red-500 @enderror">
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Property Owner</option>
                        <option value="tenant" {{ old('role') === 'tenant' ? 'selected' : '' }}>Tenant</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input type="password" name="password" id="password" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('date_of_birth') border-red-500 @enderror">
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <select name="gender" id="gender"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('gender') border-red-500 @enderror">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <textarea name="address" id="address" rows="3"
                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('address') border-red-500 @enderror"
                          placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Avatar Upload -->
            <div>
                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Avatar</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="avatar" class="relative cursor-pointer bg-white rounded-md font-medium text-livora-primary hover:text-blue-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-livora-primary">
                                <span>Upload foto</span>
                                <input id="avatar" name="avatar" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                    </div>
                </div>
                @error('avatar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="h-4 w-4 text-livora-primary focus:ring-livora-primary border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    User aktif
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-livora-primary text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview avatar
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.className = 'mt-4 h-32 w-32 object-cover rounded-full mx-auto';
                
                // Remove existing preview
                const existingPreview = document.querySelector('.avatar-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }
                
                // Add new preview
                preview.classList.add('avatar-preview');
                e.target.parentElement.parentElement.parentElement.appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection