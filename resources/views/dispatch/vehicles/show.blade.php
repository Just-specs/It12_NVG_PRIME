@extends('layouts.app')

@section('title', 'Vehicle Details - ' . $vehicle->plate_number)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('vehicles.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to Vehicles
        </a>
    </div>

    <!-- Vehicle Profile Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header with Gradient -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center">
                        <i class="fas fa-truck text-green-600 text-4xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $vehicle->plate_number }}</h1>
                        <p class="text-green-100 mb-1">
                            <i class="fas fa-truck mr-2"></i>{{ $vehicle->vehicle_type }}
                        </p>
                        <p class="text-green-100">
                            <i class="fas fa-trailer mr-2"></i>{{ $vehicle->trailer_type }}
                        </p>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                        {{ $vehicle->status === 'available' ? 'bg-green-200 text-green-800' : '' }}
                        {{ $vehicle->status === 'in-use' ? 'bg-yellow-200 text-yellow-800' : '' }}
                        {{ $vehicle->status === 'maintenance' ? 'bg-red-200 text-red-800' : '' }}">
                        <i class="fas fa-circle mr-2 text-xs"></i>
                        {{ ucfirst(str_replace('-', ' ', $vehicle->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-gray-50 border-b">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Total Trips</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_trips'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Completed</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['completed_trips'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Utilization Rate</p>
                <p class="text-3xl font-bold text-purple-600">
                    {{ $stats['total_trips'] > 0 ? round(($stats['completed_trips'] / $stats['total_trips']) * 100) : 0 }}%
                </p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Active Trip</p>
                <p class="text-lg font-bold text-yellow-600">
                    @if($stats['active_trip'])
                        <i class="fas fa-truck-moving"></i> Yes
                    @else
                        <i class="fas fa-check"></i> None
                    @endif
                </p>
            </div>
        </div>

        <!-- Active Trip (if any) -->
        @if($stats['active_trip'])
        <div class="p-6 bg-yellow-50 border-b">
            <h2 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>Active Trip
            </h2>
            <div class="bg-white rounded-lg p-4 border-l-4 border-yellow-600">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Trip ID</p>
                        <p class="font-semibold">#{{ $stats['active_trip']->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Driver</p>
                        <p class="font-semibold">{{ $stats['active_trip']->driver->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Client</p>
                        <p class="font-semibold">{{ $stats['active_trip']->deliveryRequest->client->name }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('trips.show', $stats['active_trip']) }}" class="text-blue-600 hover:text-blue-700">
                        <i class="fas fa-eye mr-1"></i>View Trip Details
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Trips -->
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-history mr-2 text-green-600"></i>Recent Trips (Last 20)
            </h2>

            @if($vehicle->trips->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-inbox text-5xl mb-4"></i>
                <p>No trips recorded yet</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Trip ID</th>
                            <th class="px-4 py-3 text-left">ATW Reference</th>
                            <th class="px-4 py-3 text-left">Client</th>
                            <th class="px-4 py-3 text-left">Driver</th>
                            <th class="px-4 py-3 text-left">Route</th>
                            <th class="px-4 py-3 text-left">Scheduled</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($vehicle->trips as $trip)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold text-blue-600">#{{ $trip->id }}</td>
                            <td class="px-4 py-3">{{ $trip->deliveryRequest->atw_reference }}</td>
                            <td class="px-4 py-3">{{ $trip->deliveryRequest->client->name }}</td>
                            <td class="px-4 py-3">{{ $trip->driver->name }}</td>
                            <td class="px-4 py-3 text-xs">
                                {{ Str::limit($trip->deliveryRequest->pickup_location, 15) }} → 
                                {{ Str::limit($trip->deliveryRequest->delivery_location, 15) }}
                            </td>
                            <td class="px-4 py-3">{{ $trip->scheduled_time->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $trip->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $trip->status === 'in-transit' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $trip->status === 'scheduled' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $trip->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($trip->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('trips.show', $trip) }}" class="text-blue-600 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Actions Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t flex gap-3">
            @if($vehicle->status === 'available')
            <form method="POST" action="{{ route('vehicles.set-maintenance', $vehicle) }}" class="inline">
                @csrf
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2 rounded-lg transition">
                    <i class="fas fa-wrench mr-2"></i>Set Maintenance
                </button>
            </form>
            @elseif($vehicle->status === 'maintenance')
            <form method="POST" action="{{ route('vehicles.set-available', $vehicle) }}" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition">
                    <i class="fas fa-check-circle mr-2"></i>Set Available
                </button>
            </form>
            @endif
            
            <a href="{{ route('vehicles.edit', $vehicle) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                <i class="fas fa-edit mr-2"></i>Edit Vehicle
            </a>
            
            <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg transition">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
