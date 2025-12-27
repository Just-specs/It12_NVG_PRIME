@extends('layouts.app')

@section('title', 'Audit Log Details')

@section('content')
<div class="container mx-auto px-4 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.audit-logs.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i> Back to Audit Logs
        </a>
        <h1 class="text-3xl font-bold text-gray-800 mt-4">
            <i class="fas fa-file-alt text-blue-600"></i> Audit Log Details
        </h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gray-50 border-b">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm text-gray-600">Log ID:</span>
                    <span class="font-mono font-semibold">#{{ $auditLog->id }}</span>
                </div>
                @php
                    $actionColors = [
                        'created' => 'green',
                        'updated' => 'blue',
                        'deleted' => 'red',
                        'restored' => 'purple',
                        'permanently_deleted' => 'black'
                    ];
                    $color = $actionColors[$auditLog->action] ?? 'gray';
                @endphp
                <span class="px-3 py-1 rounded-full bg-{{ $color }}-100 text-{{ $color }}-800 font-semibold">
                    {{ ucfirst(str_replace('_', ' ', $auditLog->action)) }}
                </span>
            </div>
        </div>

        <!-- Details -->
        <div class="p-6 space-y-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">User</label>
                    <p class="text-lg">
                        @if($auditLog->user)
                        <span class="text-blue-600">{{ $auditLog->user->name }}</span>
                        <span class="text-sm text-gray-500">({{ $auditLog->user->role }})</span>
                        @else
                        <span class="text-gray-500">{{ $auditLog->user_name ?? 'System' }}</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Date & Time</label>
                    <p class="text-lg">{{ $auditLog->created_at->format('F d, Y h:i:s A') }}</p>
                    <p class="text-sm text-gray-500">{{ $auditLog->created_at->diffForHumans() }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Model</label>
                    <p class="text-lg">
                        {{ class_basename($auditLog->model_type) }} 
                        <span class="font-mono text-sm text-gray-600">#{{ $auditLog->model_id }}</span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">IP Address</label>
                    <p class="text-lg font-mono">{{ $auditLog->ip_address ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Description</label>
                <p class="text-lg bg-gray-50 p-4 rounded-lg">{{ $auditLog->description }}</p>
            </div>

            <!-- Old Values -->
            @if($auditLog->old_values)
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">
                    <i class="fas fa-history"></i> Old Values
                </label>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <pre class="text-sm overflow-x-auto">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif

            <!-- New Values -->
            @if($auditLog->new_values)
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">
                    <i class="fas fa-plus-circle"></i> New Values
                </label>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <pre class="text-sm overflow-x-auto">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif

            <!-- User Agent -->
            @if($auditLog->user_agent)
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">User Agent</label>
                <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg break-all">{{ $auditLog->user_agent }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
