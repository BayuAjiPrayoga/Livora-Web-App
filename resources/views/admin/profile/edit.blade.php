@extends('layouts.admin')

@section('title', 'Profile - LIVORA Admin')

@section('page-title', 'My Profile')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-semibold text-gray-900">My Profile</h1>
            <p class="text-sm text-gray-600 mt-1">Manage your personal information and account settings</p>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">There were some problems with your input:</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Profile Information</h3>
                
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-6">
                        <!-- Avatar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                            <div class="flex items-center space-x-6">
                                <div class="shrink-0">
                                    @if($user->avatar)
                                        <img class="h-16 w-16 object-cover rounded-full" src="{{ asset('storage/' . $user->avatar) }}" alt="Current avatar">
                                    @else
                                        <div class="h- w- bg-gradient-to-br from-[#ff6900] to-[#ff8533] rounded-full flex items-center justify-center">
                                            <span class="text-xl font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="avatar" id="avatar" accept="image/*" 
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#ff6900] file:text-white hover:file:bg-[#e55a00]">
                                    <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG up to 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]"
                                   required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]"
                                   required>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]"
                                   placeholder="+62 812-3456-7890">
                        </div>

                        <!-- Role (Read Only) -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <input type="text" value="{{ ucfirst($user->role) }}" 
                                   class="block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500"
                                   disabled>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="btn btn-primary">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Security</h3>
                
                <form method="POST" action="{{ route('admin.profile.update') }}" id="password-form">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="update_type" value="password">
                    
                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]"
                                   required>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" name="password" id="password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]"
                                   required>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-livora-primary focus:border-[#ff6900]"
                                   required>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Account Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Info</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Member Since</span>
                        <span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Last Login</span>
                        <span class="font-medium">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Account Status</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle password form separately
    document.getElementById('password-form').addEventListener('submit', function(e) {
        // Allow normal form submission since we're using back()->with() in controller
        console.log('Password form submitted');
    });
</script>
@endpush
@endsection