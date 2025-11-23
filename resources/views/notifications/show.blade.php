@extends('layouts.app')

@section('title', 'Notification - ' . $notification->title)

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
                    <h1 class="text-3xl font-bold">{{ $notification->title }}</h1>
                    <p class="mt-2 text-blue-100">
                        <i class="fas fa-clock mr-2"></i>{{ $notification->created_at->format('F j, Y \a\t g:i A') }}
                    </p>
                </div>
                <div class="flex-shrink-0">
                    @if(!$notification->read_at)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-200 text-blue-800">
                        <i class="fas fa-circle mr-2 text-xs"></i>Unread
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-200 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>Read
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-6">
            <!-- Type Badge -->
            <div class="mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    <i class="fas fa-tag mr-2"></i>{{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                </span>
            </div>

            <!-- Message -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Message</h2>
                <div class="bg-gray-50 rounded-lg p-4 text-gray-700 border-l-4 border-blue-500">
                    <p class="text-base leading-relaxed">{{ $notification->message }}</p>
                </div>
            </div>

            <!-- Related Information -->
            @if($notification->trip)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Trip Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Trip ID</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $notification->trip->id }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium @if($notification->trip->status === 'completed') bg-green-100 text-green-800 @elseif($notification->trip->status === 'cancelled') bg-red-100 text-red-800 @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($notification->trip->status) }}
                            </span>
                        </p>
                    </div>
                    @if($notification->trip->deliveryRequest)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Client</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $notification->trip->deliveryRequest->client->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Delivery Request</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $notification->trip->deliveryRequest->id }}</p>
                    </div>
                    @endif
                </div>
                <a href="{{ route('trips.show', $notification->trip) }}" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-700">
                    View Trip Details <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            @endif

            @if($notification->client)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Client Information</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Client Name</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $notification->client->name }}</p>
                    <p class="text-sm text-gray-600 mt-3">Email</p>
                    <p class="text-base text-gray-900 mt-1">{{ $notification->client->email }}</p>
                </div>
            </div>
            @endif

            <!-- Additional Data -->
            @if($notification->data)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Additional Information</h2>
                <div class="bg-gray-50 rounded-lg p-4 overflow-auto">
                    <pre class="text-sm text-gray-700">{{ json_encode(json_decode($notification->data), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
            @endif
        </div>

        <!-- Footer with Actions -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex gap-3 justify-end">
            @if(!$notification->read_at)
            <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-envelope-open mr-2"></i>Mark as Read
                </button>
            </form>
            @endif
            <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline" onsubmit="return confirm('Delete this notification?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
            <a href="{{ route('notifications.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                Back
            </a>
        </div>
    </div>
</div>
@endsection