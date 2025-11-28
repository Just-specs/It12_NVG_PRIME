@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-user-edit text-blue-600"></i> Edit Profile
            </h1>
            <a href="{{ route('profile.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                <i class="fas fa-arrow-left"></i> Back to Profile
            </a>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center mb-6">
                        <div class="h-32 w-32 mx-auto rounded-full bg-blue-100 flex items-center justify-center mb-4">
                            <span class="text-5xl font-bold text-blue-600">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                    </div>

                    <nav class="space-y-2">
                        <a href="#profile-info" class="flex items-center px-4 py-2 text-gray-700 bg-blue-50 rounded-lg">
                            <i class="fas fa-user mr-3"></i>
                            Profile Information
                        </a>
                        <a href="#change-password" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                            <i class="fas fa-lock mr-3"></i>
                            Change Password
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-2 space-y-6">
                <!-- Profile Information Form -->
                <div id="profile-info" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                        Profile Information
                    </h2>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', Auth::user()->name) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', Auth::user()->email) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            <div>
                                <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mobile Number
                                </label>
                                <input type="text" 
                                    id="mobile" 
                                    name="mobile" 
                                    value="{{ old('mobile', Auth::user()->mobile ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="09XX-XXX-XXXX">
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Change Password Form -->
                <div id="change-password" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                        Change Password
                    </h2>

                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Current Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                    id="current_password" 
                                    name="current_password" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                                    New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                    id="new_password" 
                                    name="new_password" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <p class="mt-1 text-xs text-gray-500">
                                    Password must be at least 8 characters long
                                </p>
                            </div>

                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                    id="new_password_confirmation" 
                                    name="new_password_confirmation" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-blue-800 mb-2">Password Requirements:</h4>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li><i class="fas fa-check-circle"></i> Minimum 8 characters</li>
                                    <li><i class="fas fa-check-circle"></i> At least one uppercase letter</li>
                                    <li><i class="fas fa-check-circle"></i> At least one lowercase letter</li>
                                    <li><i class="fas fa-check-circle"></i> At least one number</li>
                                </ul>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
