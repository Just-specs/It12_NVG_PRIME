@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.dispatchers.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to User Management
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit User</h1>

            <form action="{{ route('admin.dispatchers.update', $dispatcher) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $dispatcher->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $dispatcher->email) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mobile Number -->
                <div class="mb-4">
                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                    <input type="text" 
                           id="mobile" 
                           name="mobile" 
                           value="{{ old('mobile', $dispatcher->mobile) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mobile') border-red-500 @enderror"
                           placeholder="e.g., 09123456789"
                           required>
                    @error('mobile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="role" 
                            name="role" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                            required>
                        <option value="admin" {{ old('role', $dispatcher->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="head_dispatch" {{ old('role', $dispatcher->role) === 'head_dispatch' ? 'selected' : '' }}>Head Dispatcher</option>
                        <option value="dispatch" {{ old('role', $dispatcher->role) === 'dispatch' ? 'selected' : '' }}>Dispatcher</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        <span class="font-semibold">Admin:</span> Full system access<br>
                        <span class="font-semibold">Head Dispatcher:</span> Can verify requests and manage dispatchers<br>
                        <span class="font-semibold">Dispatcher:</span> Can view and assign trips
                    </p>
                </div>

                <hr class="my-6">
                <p class="text-sm text-gray-600 mb-4">Leave password fields empty to keep current password</p>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password (Optional)</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Minimum 8 characters</p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.dispatchers.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
