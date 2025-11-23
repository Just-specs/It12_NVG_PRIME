@extends('layouts.app')

@section('title', 'Trip Details')

@section('content')
<div class="container mx-auto px-4 max-w-7xl">
    <div class="mb-6">
        <a href="{{ route('trips.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i> Back to Trips
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Trip Header -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Trip #{{ $trip->id }}</h1>
                        <p class="text-gray-600 text-sm mt-1">Scheduled for {{ $trip->scheduled_time->format('F d, Y h:i A') }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $trip->status === 'scheduled' ? 'bg-gray-200 text-gray-800' : '' }}
                        {{ $trip->status === 'in-transit' ? 'bg-blue-200 text-blue-800' : '' }}
                        {{ $trip->status === 'completed' ? 'bg-green-200 text-green-800' : '' }}
                        {{ $trip->status === 'cancelled' ? 'bg-red-200 text-red-800' : '' }}">
                        {{ ucfirst($trip->status) }}
                    </span>
                </div>

                <!-- Trip Times -->
                <div class="grid grid-cols-3 gap-4 pt-4 border-t">
                    <div>
                        <p class="text-xs text-gray-600">Scheduled</p>
                        <p class="font-semibold">{{ $trip->scheduled_time->format('h:i A') }}</p>
                    </div>
                    @if($trip->actual_start_time)
                    <div>
                        <p class="text-xs text-gray-600">Started</p>
                        <p class="font-semibold text-green-600">{{ $trip->actual_start_time->format('h:i A') }}</p>
                    </div>
                    @endif
                    @if($trip->actual_end_time)
                    <div>
                        <p class="text-xs text-gray-600">Completed</p>
                        <p class="font-semibold text-blue-600">{{ $trip->actual_end_time->format('h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Driver & Vehicle Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-truck-moving text-blue-600"></i> Assignment Details
                </h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Driver</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold">{{ $trip->driver->name }}</p>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-phone"></i> {{ $trip->driver->mobile }}
                                </p>
                                <p class="text-xs text-gray-500">License: {{ $trip->driver->license_number }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Vehicle</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                <i class="fas fa-truck text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold">{{ $trip->vehicle->plate_number }}</p>
                                <p class="text-sm text-gray-600">{{ $trip->vehicle->vehicle_type }}</p>
                                <p class="text-xs text-gray-500">Trailer: {{ $trip->vehicle->trailer_type }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-box text-blue-600"></i> Delivery Details
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Client</p>
                        <p class="font-semibold text-lg">{{ $trip->deliveryRequest->client->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">ATW Reference</p>
                        <p class="font-semibold">{{ $trip->deliveryRequest->atw_reference }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Container</p>
                        <p class="font-semibold">{{ $trip->deliveryRequest->container_size }} - {{ $trip->deliveryRequest->container_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Route</p>
                        <div class="space-y-3">
                            <div class="flex items-start p-3 bg-green-50 rounded-lg">
                                <i class="fas fa-map-marker-alt text-green-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-xs text-gray-600">Pickup Location</p>
                                    <p class="font-semibold">{{ $trip->deliveryRequest->pickup_location }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-center">
                                <i class="fas fa-arrow-down text-gray-400"></i>
                            </div>
                            <div class="flex items-start p-3 bg-red-50 rounded-lg">
                                <i class="fas fa-flag-checkered text-red-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-xs text-gray-600">Delivery Location</p>
                                    <p class="font-semibold">{{ $trip->deliveryRequest->delivery_location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($trip->route_instructions)
                    <div>
                        <p class="text-sm text-gray-600">Route Instructions</p>
                        <p class="text-gray-700 mt-1 p-3 bg-gray-50 rounded">{{ $trip->route_instructions }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Trip Updates -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-history text-blue-600"></i> Trip Updates
                    </h3>
                    @if($trip->status !== 'completed' && $trip->status !== 'cancelled')
                    <button onclick="document.getElementById('addUpdateModal').classList.remove('hidden')" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                        <i class="fas fa-plus"></i> Add Update
                    </button>
                    @endif
                </div>

                <div class="space-y-4">
                    @forelse($trip->updates as $update)
                    <div class="flex items-start border-l-4 pl-4
                        {{ $update->update_type === 'delay' ? 'border-yellow-500' : '' }}
                        {{ $update->update_type === 'incident' ? 'border-red-500' : '' }}
                        {{ $update->update_type === 'completed' ? 'border-green-500' : 'border-blue-500' }}">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $update->update_type)) }}</p>
                                <span class="text-xs text-gray-500">{{ $update->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 mt-1">{{ $update->message }}</p>
                            @if($update->location)
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-map-marker-alt"></i> {{ $update->location }}
                            </p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">
                                Reported by: {{ ucfirst($update->reported_by) }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center py-8 text-gray-500">No updates yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>

                @if($trip->status === 'scheduled')
                <form method="POST" action="{{ route('trips.update-status', $trip) }}">
                    @csrf
                    <input type="hidden" name="status" value="in-transit">
                    <input type="hidden" name="update_message" value="Trip started">
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-play"></i> Start Trip
                    </button>
                </form>
                @endif

                @if($trip->status === 'in-transit')
                <form method="POST" action="{{ route('trips.update-status', $trip) }}">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <input type="hidden" name="update_message" value="Trip completed successfully">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-check-circle"></i> Complete Trip
                    </button>
                </form>
                @endif

                <button onclick="window.print()" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-print"></i> Print Trip Sheet
                </button>

                <a href="{{ route('requests.show', $trip->deliveryRequest) }}" class="block w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center">
                    <i class="fas fa-file-alt"></i> View Request
                </a>

                <!-- Contact Info -->
                <div class="pt-4 border-t">
                    <h4 class="font-semibold text-gray-800 mb-3">Contact</h4>
                    <div class="space-y-2 text-sm">
                        <a href="tel:{{ $trip->driver->mobile }}" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-phone w-6"></i>
                            <span>Call Driver</span>
                        </a>
                        @if($trip->deliveryRequest->client->mobile)
                        <a href="tel:{{ $trip->deliveryRequest->client->mobile }}" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-phone w-6"></i>
                            <span>Call Client</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Update Modal -->
<div id="addUpdateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Add Trip Update</h3>
            <button onclick="document.getElementById('addUpdateModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('trips.add-update', $trip) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Type</label>
                    <select name="update_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="status">Status Update</option>
                        <option value="location">Location Update</option>
                        <option value="delay">Delay</option>
                        <option value="incident">Incident</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Enter update message"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Location (optional)</label>
                    <input type="text" name="location" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Enter current location">
                </div>

                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('addUpdateModal').classList.add('hidden')" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Add Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>