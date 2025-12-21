@extends('layouts.app')

@section('title', 'Notification Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to Notifications
        </a>
    </div>

    <!-- Notification Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-6 text-white">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-200 text-blue-800">
                            <i class="fas fa-tag mr-2"></i>{{ ucfirst($notification->notification_type) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-blue-800">
                            <i class="fas fa-{{ $notification->method === 'sms' ? 'sms' : 'envelope' }} mr-2"></i>{{ strtoupper($notification->method) }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold">Notification #{{ $notification->id }}</h1>
                    <p class="mt-2 text-blue-100">
                        <i class="fas fa-clock mr-2"></i>{{ $notification->created_at->format('F j, Y \a\t g:i A') }}
                    </p>
                </div>
                <div class="flex-shrink-0">
                    @if(!$notification->sent)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-orange-200 text-orange-800">
                        <i class="fas fa-circle mr-2 text-xs animate-pulse"></i>Pending
                    </span>
                    @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-200 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>Sent
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-6">
            <!-- Message -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-comment-alt mr-2 text-blue-600"></i>Message
                </h2>
                <div class="bg-blue-50 rounded-lg p-5 text-gray-800 border-l-4 border-blue-600 shadow-sm">
                    <p class="text-base leading-relaxed">{{ $notification->message }}</p>
                </div>
            </div>

            <!-- Client Information -->
            @if($notification->client)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-user-tie mr-2 text-blue-600"></i>Client Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">Client Name</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $notification->client->name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="text-base text-gray-900">{{ $notification->client->email }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">Phone</p>
                        <p class="text-base text-gray-900">{{ $notification->client->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Trip Information -->
            @if($notification->trip)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-truck mr-2 text-blue-600"></i>Trip Information
                </h2>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                        <div class="p-4">
                            <p class="text-sm text-gray-600 mb-1">Trip ID</p>
                            <p class="text-xl font-bold text-blue-600">#{{ $notification->trip->id }}</p>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                {{ $notification->trip->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($notification->trip->status === 'in-transit' ? 'bg-blue-100 text-blue-800' : 
                                   ($notification->trip->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($notification->trip->status) }}
                            </span>
                        </div>
                        @if($notification->trip->deliveryRequest)
                        <div class="p-4">
                            <p class="text-sm text-gray-600 mb-1">ATW Reference</p>
                            <p class="text-base font-semibold text-gray-900">{{ $notification->trip->deliveryRequest->atw_reference }}</p>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600 mb-1">Scheduled Time</p>
                            <p class="text-base font-semibold text-gray-900">{{ $notification->trip->scheduled_time->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                    </div>
                    
                    @if($notification->trip->deliveryRequest)
                    <div class="bg-gray-50 p-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>Pickup Location
                                </p>
                                <p class="text-sm text-gray-900">{{ $notification->trip->deliveryRequest->pickup_location }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-600"></i>Delivery Location
                                </p>
                                <p class="text-sm text-gray-900">{{ $notification->trip->deliveryRequest->delivery_location }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($notification->trip->driver && $notification->trip->vehicle)
                    <div class="bg-gray-50 p-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Driver</p>
                                <p class="text-base font-semibold text-gray-900">{{ $notification->trip->driver->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Vehicle</p>
                                <p class="text-base font-semibold text-gray-900">{{ $notification->trip->vehicle->plate_number }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <a href="{{ route('trips.show', $notification->trip) }}" 
                   class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-eye mr-2"></i>View Full Trip Details
                </a>
            </div>
            @endif
        </div>

        <!-- Footer with Actions -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex gap-3 justify-end">
            @if(!$notification->sent)
            <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition flex items-center">
                    <i class="fas fa-check mr-2"></i>Mark as Read
                </button>
            </form>
            @endif
            <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this notification?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg transition flex items-center">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
            <a href="{{ route('notifications.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>
</div>
@endsection
