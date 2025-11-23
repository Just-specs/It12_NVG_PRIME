@extends('layouts.app')

@section('title', 'Drivers')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-tie text-blue-600"></i> Drivers Management
        </h1>
    </div>

    <!-- Drivers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($drivers as $driver)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <!-- Status Header -->
            <div class="p-4 
                {{ $driver->status === 'available' ? 'bg-green-50' : '' }}
                {{ $driver->status === 'on-trip' ? 'bg-blue-50' : '' }}
                {{ $driver->status === 'off-duty' ? 'bg-gray-50' : '' }}">
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $driver->status === 'available' ? 'bg-green-200 text-green-800' : '' }}
                    {{ $driver->status === 'on-trip' ? 'bg-blue-200 text-blue-800' : '' }}
                    {{ $driver->status === 'off-duty' ? 'bg-gray-200 text-gray-800' : '' }}">
                    <i class="fas fa-circle text-xs"></i> {{ ucfirst(str_replace('-', ' ', $driver->status)) }}
                </span>
            </div>

            <!-- Driver Info -->
            <div class="p-6 text-center">
                <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-blue-600 text-3xl"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-1">{{ $driver->name }}</h3>
                <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-phone"></i> {{ $driver->mobile }}
                </p>
                <p class="text-xs text-gray-500 mb-4">
                    License: {{ $driver->license_number }}
                </p>

                <!-- Stats -->
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600">Total Trips</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $driver->trips_count }}</p>
                </div>

                <!-- Actions -->
                <div class="mt-4 space-y-2">
                    <a href="{{ route('drivers.show', $driver) }}" class="block w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        <i class="fas fa-eye"></i> View Details
                    </a>

                    @if($driver->status === 'available')
                    <form method="POST" action="{{ route('drivers.update-status', $driver) }}">
                        @csrf
                        <input type="hidden" name="status" value="off-duty">
                        <button type="submit" class="w-full px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm">
                            Set Off-Duty
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('drivers.update-status', $driver) }}">
                        @csrf
                        <input type="hidden" name="status" value="available">
                        <button type="submit" class="w-full px-4 py-2 border border-green-300 rounded text-green-700 hover:bg-green-50 text-sm">
                            Set Available
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection