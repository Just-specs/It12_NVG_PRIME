@extends('layouts.app')

@section('title', 'Trips')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-route text-blue-600"></i> Trips Management
        </h1>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="#" class="border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                All Trips
            </a>
            <a href="#" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Scheduled
            </a>
            <a href="#" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                In Transit
            </a>
            <a href="#" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Completed
            </a>
        </nav>
    </div>

    <!-- Trips Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($trips as $trip)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <!-- Status Header -->
            <div class="p-4 
                {{ $trip->status === 'scheduled' ? 'bg-gray-50' : '' }}
                {{ $trip->status === 'in-transit' ? 'bg-blue-50' : '' }}
                {{ $trip->status === 'completed' ? 'bg-green-50' : '' }}
                {{ $trip->status === 'cancelled' ? 'bg-red-50' : '' }}">
                <div class="flex justify-between items-center">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $trip->status === 'scheduled' ? 'bg-gray-200 text-gray-800' : '' }}
                        {{ $trip->status === 'in-transit' ? 'bg-blue-200 text-blue-800' : '' }}
                        {{ $trip->status === 'completed' ? 'bg-green-200 text-green-800' : '' }}
                        {{ $trip->status === 'cancelled' ? 'bg-red-200 text-red-800' : '' }}">
                        {{ ucfirst($trip->status) }}
                    </span>
                    <span class="text-sm text-gray-600">
                        <i class="far fa-clock"></i> {{ $trip->scheduled_time->format('h:i A') }}
                    </span>
                </div>
            </div>

            <!-- Trip Details -->
            <div class="p-4">
                <!-- Client -->
                <div class="mb-3">
                    <p class="text-xs text-gray-500">Client</p>
                    <p class="font-semibold text-gray-800">{{ $trip->deliveryRequest->client->name }}</p>
                </div>

                <!-- Driver & Vehicle -->
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <p class="text-xs text-gray-500">Driver</p>
                        <p class="font-medium text-sm">{{ $trip->driver->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Vehicle</p>
                        <p class="font-medium text-sm">{{ $trip->vehicle->plate_number }}</p>
                    </div>
                </div>

                <!-- Route -->
                <div class="mb-4">
                    <div class="flex items-start mb-2">
                        <i class="fas fa-map-marker-alt text-green-500 mt-1 mr-2"></i>
                        <p class="text-sm text-gray-700">{{ Str::limit($trip->deliveryRequest->pickup_location, 40) }}</p>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-flag-checkered text-red-500 mt-1 mr-2"></i>
                        <p class="text-sm text-gray-700">{{ Str::limit($trip->deliveryRequest->delivery_location, 40) }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2 border-t pt-3">
                    <a href="{{ route('trips.show', $trip) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white text-center rounded hover:bg-blue-700 text-sm">
                        <i class="fas fa-eye"></i> View
                    </a>
                    @if($trip->status === 'scheduled')
                    <form method="POST" action="{{ route('trips.update-status', $trip) }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="in-transit">
                        <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                            <i class="fas fa-play"></i> Start
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="fas fa-truck text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No trips found</p>
        </div>
        @endforelse
    </div>
</div>
@endsection