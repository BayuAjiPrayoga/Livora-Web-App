@extends('layouts.admin')

@section('title', 'Edit User - LIVORA')

@section('page-title', 'Edit User')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Edit User</h1>
                <p class="text-sm text-gray-600 mt-1">Update informasi user {{ $user->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Profile
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Current Avatar -->
            @if($user->avatar)
                <div class="text-center mb-6">
                    <img class="h-20 w-20 rounded-full object-cover mx-auto mb-2" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                    <p class="text-sm text-gray-500">Current avatar</p>
                </div>
            @endif

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
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
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="mitra" {{ old('role', $user->role) === 'mitra' ? 'selected' : '' }}>Mitra</option>
                        <option value="tenant" {{ old('role', $user->role) === 'tenant' ? 'selected' : '' }}>Tenant</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password (Optional) -->
            <div class="border-t border-gray-200 pt-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Change Password</h3>
                    <p class="text-sm text-gray-500">Leave blank to keep current password</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="password" id="password"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary">
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="border-t border-gray-200 pt-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
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
                            <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="address" id="address" rows="3"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-livora-primary @error('address') border-red-500 @enderror"
                              placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Avatar Upload -->
            <div class="border-t border-gray-200 pt-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Profile Picture</h3>
                </div>
                
                <div>
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Update Avatar</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="avatar" class="relative cursor-pointer bg-white rounded-md font-medium text-livora-primary hover:text-blue-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-livora-primary">
                                    <span>Upload new photo</span>
                                    <input id="avatar" name="avatar" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                    @error('avatar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-livora-primary focus:ring-livora-primary border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        User aktif
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">Nonaktif akan mencegah user login ke sistem</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-livora-primary text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview new avatar
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

    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        
        if (password && confirmation && password !== confirmation) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });

    document.getElementById('password').addEventListener('input', function() {
        const confirmation = document.getElementById('password_confirmation');
        if (confirmation.value) {
            confirmation.dispatchEvent(new Event('input'));
        }
    });
</script>
@endpush
@endsection