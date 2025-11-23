@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-truck text-blue-600"></i> Fleet Management
        </h1>
        <a href="{{ route('vehicles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus"></i> Add Vehicle
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Fleet</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
                <i class="fas fa-truck text-4xl text-blue-500"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Available</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['available'] }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">In Use</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['in_use'] }}</p>
                </div>
                <i class="fas fa-truck-moving text-4xl text-yellow-500"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Maintenance</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['maintenance'] }}</p>
                </div>
                <i class="fas fa-tools text-4xl text-red-500"></i>
            </div>
        </div>
    </div>

    <!-- Vehicles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($vehicles as $vehicle)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <!-- Status Header -->
            <div class="p-4 
                {{ $vehicle->status === 'available' ? 'bg-green-50' : '' }}
                {{ $vehicle->status === 'in-use' ? 'bg-yellow-50' : '' }}
                {{ $vehicle->status === 'maintenance' ? 'bg-red-50' : '' }}">
                <div class="flex justify-between items-center">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $vehicle->status === 'available' ? 'bg-green-200 text-green-800' : '' }}
                        {{ $vehicle->status === 'in-use' ? 'bg-yellow-200 text-yellow-800' : '' }}
                        {{ $vehicle->status === 'maintenance' ? 'bg-red-200 text-red-800' : '' }}">
                        <i class="fas fa-circle text-xs"></i> {{ ucfirst(str_replace('-', ' ', $vehicle->status)) }}
                    </span>
                </div>
            </div>

            <!-- Vehicle Info -->
            <div class="p-6 text-center">
                <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-blue-600 text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl text-gray-800 mb-1">{{ $vehicle->plate_number }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $vehicle->vehicle_type }}</p>
                <p class="text-xs text-gray-500 mb-4">Trailer: {{ $vehicle->trailer_type }}</p>

                <!-- Stats -->
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600">Total Trips</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $vehicle->trips_count }}</p>
                </div>

                <!-- Actions -->
                <div class="mt-4 space-y-2">
                    <a href="{{ route('vehicles.show', $vehicle) }}" class="block w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        <i class="fas fa-eye"></i> View Details
                    </a>

                    @if($vehicle->status === 'available')
                    <form method="POST" action="{{ route('vehicles.set-maintenance', $vehicle) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 border border-orange-300 rounded text-orange-700 hover:bg-orange-50 text-sm">
                            <i class="fas fa-tools"></i> Set Maintenance
                        </button>
                    </form>
                    @elseif($vehicle->status === 'maintenance')
                    <form method="POST" action="{{ route('vehicles.set-available', $vehicle) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 border border-green-300 rounded text-green-700 hover:bg-green-50 text-sm">
                            <i class="fas fa-check"></i> Set Available
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="fas fa-truck text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No vehicles found</p>
        </div>
        @endforelse
    </div>
</div>
@endsection