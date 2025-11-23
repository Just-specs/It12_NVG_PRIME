@extends('layouts.app')

@section('title', 'Assign Driver')

@section('content')
<div class="container mx-auto px-4 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('requests.show', $deliveryRequest) }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i> Back to Request
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-user-plus text-blue-600"></i> Assign Driver & Vehicle
                </h1>

                <form method="POST" action="{{ route('trips.store') }}">
                    @csrf
                    <input type="hidden" name="delivery_request_id" value="{{ $deliveryRequest->id }}">

                    <!-- Driver Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Driver <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            @forelse($drivers as $driver)
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                                <input type="radio" name="driver_id" value="{{ $driver->id }}" required class="mr-3">
                                <div class="flex-1">
                                    <p class="font-semibold">{{ $driver->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $driver->mobile }}</p>
                                    <p class="text-xs text-gray-500">License: {{ $driver->license_number }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-circle text-xs"></i> Available
                                </span>
                            </label>
                            @empty
                            <p class="text-center py-4 text-gray-500">No available drivers</p>
                            @endforelse
                        </div>
                        @error('driver_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Vehicle <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            @forelse($vehicles as $vehicle)
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                                <input type="radio" name="vehicle_id" value="{{ $vehicle->id }}" required class="mr-3">
                                <div class="flex-1">
                                    <p class="font-semibold">{{ $vehicle->plate_number }}</p>
                                    <p class="text-sm text-gray-600">{{ $vehicle->vehicle_type }}</p>
                                    <p class="text-xs text-gray-500">Trailer: {{ $vehicle->trailer_type }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-circle text-xs"></i> Available
                                </span>
                            </label>
                            @empty
                            <p class="text-center py-4 text-gray-500">No available vehicles</p>
                            @endforelse
                        </div>
                        @error('vehicle_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Schedule -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Scheduled Time <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="scheduled_time" required value="{{ $deliveryRequest->preferred_schedule->format('Y-m-d\TH:i') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('scheduled_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Route Instructions -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Route Instructions
                        </label>
                        <textarea name="route_instructions" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter route instructions, toll gates, or special directions"></textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('requests.show', $deliveryRequest) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-check"></i> Assign & Notify Driver
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Request Summary -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h3 class="font-semibold text-gray-800 mb-4">Request Summary</h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Client</p>
                        <p class="font-semibold">{{ $deliveryRequest->client->name }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">ATW Reference</p>
                        <p class="font-semibold">{{ $deliveryRequest->atw_reference }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Container</p>
                        <p class="font-semibold">{{ $deliveryRequest->container_size }} - {{ $deliveryRequest->container_type }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Pickup</p>
                        <p class="font-semibold text-xs">{{ $deliveryRequest->pickup_location }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Delivery</p>
                        <p class="font-semibold text-xs">{{ $deliveryRequest->delivery_location }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection