@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-user-circle text-blue-600"></i> My Profile
            </h1>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-32"></div>
            
            <div class="px-6 pb-6">
                <!-- Avatar -->
                <div class="flex items-end -mt-16 mb-6">
                    <div class="relative">
                        <div class="h-32 w-32 rounded-full bg-white p-2 shadow-lg">
                            <div class="h-full w-full rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-4xl font-bold text-blue-600">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <span class="absolute bottom-2 right-2 h-6 w-6 bg-green-500 border-4 border-white rounded-full"></span>
                    </div>
                    <div class="ml-6 mb-2">
                        <h2 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Personal Information</h3>
                        
                        <div>
                            <label class="text-sm text-gray-500">Full Name</label>
                            <p class="text-gray-900 font-medium">{{ Auth::user()->name }}</p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500">Email Address</label>
                            <p class="text-gray-900 font-medium">{{ Auth::user()->email }}</p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500">Role</label>
                            <p class="text-gray-900 font-medium capitalize">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    {{ Auth::user()->role ?? 'User' }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500">Member Since</label>
                            <p class="text-gray-900 font-medium">{{ Auth::user()->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Account Statistics -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Account Statistics</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ \App\Models\DeliveryRequest::count() }}
                                </div>
                                <div class="text-sm text-gray-600">Total Requests</div>
                            </div>

                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ \App\Models\Trip::where('status', 'completed')->count() }}
                                </div>
                                <div class="text-sm text-gray-600">Completed Trips</div>
                            </div>

                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600">
                                    {{ \App\Models\Driver::where('status', 'available')->count() }}
                                </div>
                                <div class="text-sm text-gray-600">Available Drivers</div>
                            </div>

                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ \App\Models\Vehicle::where('status', 'available')->count() }}
                                </div>
                                <div class="text-sm text-gray-600">Available Vehicles</div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Last Login</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ Auth::user()->updated_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Account Status</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-user-edit text-2xl text-blue-600 mb-2"></i>
                            <span class="text-sm text-gray-700">Edit Profile</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-tachometer-alt text-2xl text-green-600 mb-2"></i>
                            <span class="text-sm text-gray-700">Dashboard</span>
                        </a>
                        <a href="{{ route('requests.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-clipboard-list text-2xl text-yellow-600 mb-2"></i>
                            <span class="text-sm text-gray-700">My Requests</span>
                        </a>
                        <a href="{{ route('trips.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-truck text-2xl text-purple-600 mb-2"></i>
                            <span class="text-sm text-gray-700">My Trips</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
