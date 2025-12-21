@extends('layouts.app')

@section('title', 'Driver Profile - ' . $driver->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('drivers.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to Drivers
        </a>
    </div>

    <!-- Driver Profile Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center">
                        <i class="fas fa-user-tie text-blue-600 text-4xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $driver->name }}</h1>
                        <p class="text-blue-100 mb-1">
                            <i class="fas fa-id-card mr-2"></i>License: {{ $driver->license_number }}
                        </p>
                        <p class="text-blue-100">
                            <i class="fas fa-phone mr-2"></i>{{ $driver->mobile }}
                        </p>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                        {{ $driver->status === 'available' ? 'bg-green-200 text-green-800' : '' }}
                        {{ $driver->status === 'on-trip' ? 'bg-yellow-200 text-yellow-800' : '' }}
                        {{ $driver->status === 'off-duty' ? 'bg-gray-200 text-gray-800' : '' }}">
                        <i class="fas fa-circle mr-2 text-xs"></i>
                        {{ ucfirst(str_replace('-', ' ', $driver->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-gray-50 border-b">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Total Trips</p>
                <p class="text-3xl font-bold text-blue-600">{{ $driver->trips_count ?? $driver->trips->count() }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Completed</p>
                <p class="text-3xl font-bold text-green-600">{{ $driver->trips->where('status', 'completed')->count() }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">In Progress</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $driver->trips->where('status', 'in-transit')->count() }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Scheduled</p>
                <p class="text-3xl font-bold text-gray-600">{{ $driver->trips->where('status', 'scheduled')->count() }}</p>
            </div>
        </div>

        <!-- Recent Trips -->
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-history mr-2 text-blue-600"></i>Recent Trips (Last 10)
            </h2>

            @if($driver->trips->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-inbox text-5xl mb-4"></i>
                <p>No trips assigned yet</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Trip ID</th>
                            <th class="px-4 py-3 text-left">ATW Reference</th>
                            <th class="px-4 py-3 text-left">Client</th>
                            <th class="px-4 py-3 text-left">Vehicle</th>
                            <th class="px-4 py-3 text-left">Scheduled</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($driver->trips as $trip)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold text-blue-600">#{{ $trip->id }}</td>
                            <td class="px-4 py-3">{{ $trip->deliveryRequest->atw_reference }}</td>
                            <td class="px-4 py-3">{{ $trip->deliveryRequest->client->name }}</td>
                            <td class="px-4 py-3">{{ $trip->vehicle->plate_number }}</td>
                            <td class="px-4 py-3">{{ $trip->scheduled_time->format('M d, Y h:i A') }}</td>
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
            @if($driver->status === 'available')
            <form method="POST" action="{{ route('drivers.update-status', $driver) }}" class="inline">
                @csrf
                <input type="hidden" name="status" value="off-duty">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg transition">
                    <i class="fas fa-pause-circle mr-2"></i>Set Off-Duty
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('drivers.update-status', $driver) }}" class="inline">
                @csrf
                <input type="hidden" name="status" value="available">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition">
                    <i class="fas fa-check-circle mr-2"></i>Set Available
                </button>
            </form>
            @endif
            
            <a href="{{ route('drivers.edit', $driver) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                <i class="fas fa-edit mr-2"></i>Edit Driver
            </a>
            
            <form method="POST" action="{{ route('drivers.destroy', $driver) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this driver?');">
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
