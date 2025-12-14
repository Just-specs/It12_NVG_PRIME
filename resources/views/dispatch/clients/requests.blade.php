@extends('layouts.app')

@section('title', $client->name . ' - All Requests')

@section('content')
<div class="container mx-auto px-4 max-w-7xl">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white bg-[#2563EB] rounded-full hover:bg-blue-700 transition-colors mb-3">
            <i class="fas fa-arrow-left"></i>
            Back to Client
        </a>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-clipboard-list text-blue-600"></i>
            All Requests - {{ $client->name }}
        </h1>
        @if($client->company)
        <p class="text-gray-600 mt-1">{{ $client->company }}</p>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Requests -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Requests</p>
                    <p class="text-4xl font-bold text-gray-800">{{ $requests->total() }}</p>
                </div>
                <div class="bg-blue-500 p-3 rounded-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Pending</p>
                    <p class="text-4xl font-bold text-gray-800">
                        {{ $requests->where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="bg-yellow-500 p-3 rounded-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">In Progress</p>
                    <p class="text-4xl font-bold text-gray-800">
                        {{ $requests->whereIn('status', ['verified', 'assigned', 'in-transit'])->count() }}
                    </p>
                </div>
                <div class="bg-purple-500 p-3 rounded-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Completed</p>
                    <p class="text-4xl font-bold text-gray-800">
                        {{ $requests->where('status', 'completed')->count() }}
                    </p>
                </div>
                <div class="bg-green-500 p-3 rounded-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($requests->count() > 0)
        
        <!-- Pagination Above Table -->
        @if($requests->hasPages())
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ $requests->firstItem() }}</span> 
                    to <span class="font-medium">{{ $requests->lastItem() }}</span> 
                    of <span class="font-medium">{{ $requests->total() }}</span> results
                </div>
                <div>
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ATW Reference
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pickup Location
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Delivery Location
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Container
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Schedule
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trip
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($requests as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $request->atw_reference }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $request->created_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ Str::limit($request->pickup_location, 30) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ Str::limit($request->delivery_location, 30) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $request->container_size }}'
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ ucfirst($request->container_type) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $request->preferred_schedule->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $request->preferred_schedule->format('h:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'verified' => 'bg-blue-100 text-blue-800',
                                    'assigned' => 'bg-purple-100 text-purple-800',
                                    'in-transit' => 'bg-indigo-100 text-indigo-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($request->trip)
                                <a href="{{ route('trips.show', $request->trip) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-truck"></i>
                                    Trip #{{ $request->trip->id }}
                                </a>
                                @if($request->trip->driver)
                                <div class="text-xs text-gray-500">
                                    {{ $request->trip->driver->name }}
                                </div>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('requests.show', $request) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3"
                               title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Below Table -->
        @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ $requests->firstItem() }}</span> 
                    to <span class="font-medium">{{ $requests->lastItem() }}</span> 
                    of <span class="font-medium">{{ $requests->total() }}</span> results
                </div>
                <div>
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No delivery requests found for this client.</p>
            <a href="{{ route('requests.create') }}" class="mt-4 inline-flex items-center gap-2 px-6 py-2 bg-[#2563EB] text-white rounded-full hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus"></i>
                Create New Request
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
