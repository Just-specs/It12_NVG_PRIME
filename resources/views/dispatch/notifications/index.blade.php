@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Notifications</h1>
            <p class="text-gray-600 mt-1">Manage client and driver notifications</p>
        </div>
        <div class="flex gap-2">
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Mark All as Read
                </button>
            </form>
            <form action="{{ route('notifications.clear-all') }}" method="POST"
                onsubmit="return confirm('Are you sure you want to clear all notifications?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    Clear All
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Notifications</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Sent</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['sent'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Recent Notifications</h2>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
            <div class="p-6 hover:bg-gray-50 transition {{ !$notification->sent ? 'bg-blue-50' : '' }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $notification->notification_type === 'status' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ucfirst($notification->notification_type) }}
                            </span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $notification->method === 'sms' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ strtoupper($notification->method) }}
                            </span>
                            @if(!$notification->sent)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                Pending
                            </span>
                            @endif
                        </div>

                        <p class="text-gray-800 font-medium mb-2">{{ $notification->message }}</p>

                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Client:</strong> {{ $notification->client->name }}</p>
                            @if($notification->trip)
                            <p><strong>ATW Reference:</strong> {{ $notification->trip->deliveryRequest->atw_reference }}</p>
                            <p><strong>Trip Status:</strong>
                                <span class="px-2 py-1 text-xs rounded-full
                                            {{ $notification->trip->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($notification->trip->status === 'in-transit' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($notification->trip->status) }}
                                </span>
                            </p>
                            @endif
                            <p><strong>Created:</strong> {{ $notification->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2 ml-4">
                        <a href="{{ route('notifications.show', $notification) }}"
                            class="text-blue-600 hover:text-blue-800 px-3 py-1 text-sm font-medium">
                            View
                        </a>
                        @if(!$notification->sent)
                        <form action="{{ route('notifications.mark-read', $notification) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-green-600 hover:text-green-800 px-3 py-1 text-sm font-medium">
                                Mark Read
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST"
                            onsubmit="return confirm('Delete this notification?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 px-3 py-1 text-sm font-medium">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <p class="text-gray-500 text-lg">No notifications found</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <span class="text-sm text-gray-700">
                Showing <span class="font-semibold">{{ $notifications->firstItem() }}</span>
                to <span class="font-semibold">{{ $notifications->lastItem() }}</span>
                of <span class="font-semibold">{{ $notifications->total() }}</span> notifications
            </span>
            <div class="flex gap-3">
                @if($notifications->onFirstPage())
                <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Previous</span>
                @else
                <a href="{{ $notifications->previousPageUrl() }}"
                    class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Previous
                </a>
                @endif

                @if($notifications->hasMorePages())
                <a href="{{ $notifications->nextPageUrl() }}"
                    class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Next
                </a>
                @else
                <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection