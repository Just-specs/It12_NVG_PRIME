@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
        <p class="mt-2 text-gray-600">Manage your delivery and trip notifications</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Notifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Notifications</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-bell text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Unread Notifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Unread</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['unread'] }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-envelope text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Read Notifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Read</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['read'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-6 flex gap-2">
        <button onclick="markAllRead()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-check-double mr-2"></i>Mark All as Read
        </button>
        <button onclick="clearAll()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-trash mr-2"></i>Clear All
        </button>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($notifications->count() > 0)
        <div class="divide-y">
            @foreach($notifications as $notification)
            <div class="p-4 hover:bg-gray-50 transition flex items-start justify-between group">
                <div class="flex-1">
                    <a href="{{ route('notifications.show', $notification) }}" class="flex items-start space-x-4">
                        <!-- Status Badge -->
                        <div class="flex-shrink-0 pt-1">
                            @if(!$notification->read_at)
                            <span class="flex h-3 w-3">
                                <span class="animate-pulse absolute inline-flex h-3 w-3 rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                            </span>
                            @else
                            <div class="h-3 w-3 rounded-full bg-gray-300"></div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $notification->title }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $notification->message }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Action Buttons -->
                <div class="flex-shrink-0 ml-4 opacity-0 group-hover:opacity-100 transition flex gap-2">
                    @if(!$notification->read_at)
                    <button onclick="markRead({{ $notification->id }})"
                        class="text-blue-600 hover:text-blue-700 text-sm px-2 py-1 rounded hover:bg-blue-50">
                        <i class="fas fa-envelope-open"></i>
                    </button>
                    @endif
                    <button onclick="deleteNotification({{ $notification->id }})"
                        class="text-red-600 hover:text-red-700 text-sm px-2 py-1 rounded hover:bg-red-50">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-bell text-4xl"></i>
            </div>
            <p class="text-gray-600 font-medium mb-2">No Notifications</p>
            <p class="text-gray-500 text-sm">You don't have any notifications yet.</p>
        </div>
        @endif
    </div>
</div>

<script>
    function markRead(notificationId) {
        fetch(`/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function markAllRead() {
        if (confirm('Mark all notifications as read?')) {
            fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }

    function deleteNotification(notificationId) {
        if (confirm('Delete this notification?')) {
            fetch(`/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }

    function clearAll() {
        if (confirm('Clear all notifications? This cannot be undone.')) {
            fetch('/notifications/clear-all', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }
</script>
@endsection