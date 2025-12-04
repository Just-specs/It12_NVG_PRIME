@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-chart-line text-blue-600"></i> Dispatch Dashboard
        </h1>
        <div class="text-black">
            <i class="far fa-calendar-alt"></i> {{ now()->format('F d, Y') }}
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <!-- Pending Requests Card -->
        <a href="{{ route('requests.index', ['status' => 'pending']) }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending Requests</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_requests'] }}</p>
                </div>
                <i class="fas fa-hourglass-half text-4xl text-yellow-500"></i>
            </div>
        </a>

        <!-- Active Trips Card -->
        <a href="{{ route('trips.index', ['status' => 'in-transit']) }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Trips</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['active_trips'] }}</p>
                </div>
                <i class="fas fa-truck-moving text-4xl text-blue-500"></i>
            </div>
        </a>

        <!-- Available Drivers Card -->
        <a href="{{ route('drivers.index', ['status' => 'available']) }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Available Drivers</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['available_drivers'] }}</p>
                </div>
                <i class="fas fa-user-check text-4xl text-green-500"></i>
            </div>
        </a>

        <!-- Available Vehicles Card -->
        <a href="{{ route('vehicles.index', ['status' => 'available']) }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Available Vehicles</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['available_vehicles'] }}</p>
                </div>
                <i class="fas fa-truck text-4xl text-purple-500"></i>
            </div>
        </a>

        <!-- Today's Trips Card -->
        <a href="{{ route('trips.index', ['date' => now()->format('Y-m-d')]) }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Today's Trips</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $stats['today_trips'] }}</p>
                </div>
                <i class="fas fa-calendar-day text-4xl text-indigo-500"></i>
            </div>
        </a>

        <!-- Completed Today Card -->
        <a href="{{ route('trips.index', ['status' => 'completed', 'date' => now()->format('Y-m-d')]) }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Completed Today</p>
                    <p class="text-3xl font-bold text-teal-600">{{ $stats['completed_today'] }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-teal-500"></i>
            </div>
        </a>
    </div>

    <!-- Today's Schedule -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-calendar-check text-blue-600"></i> Today's Schedule
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Route</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($todaySchedule as $trip)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <i class="far fa-clock text-gray-400"></i> {{ $trip->scheduled_time->format('h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $trip->driver->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $trip->vehicle->plate_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $trip->deliveryRequest->client->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="max-w-xs">
                                <i class="fas fa-map-marker-alt text-green-500"></i>
                                {{ Str::limit($trip->deliveryRequest->pickup_location, 20) }}
                                <br>
                                <i class="fas fa-flag-checkered text-red-500"></i>
                                {{ Str::limit($trip->deliveryRequest->delivery_location, 20) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $trip->status === 'scheduled' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $trip->status === 'in-transit' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $trip->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ ucfirst($trip->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('trips.show', $trip) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            No trips scheduled for today
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Requests -->
        <div class="bg-white rounded-md shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-inbox text-blue-600"></i> Recent Requests
                    </h2>
                    <a href="{{ route('requests.index') }}" class="inline-flex items-center gap-1 px-4 py-1.5 text-sm font-semibold text-white bg-[#2563EB] rounded-full shadow-sm transition hover:bg-[#1D4ED8] focus:outline-none focus:ring-2 focus:ring-blue-300">
                        View All <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
            <div class="p-6" id="recent-requests-container">
                @include('dispatch.dashboard.partials.recent-requests', ['recentRequests' => $recentRequests])
            </div>
        </div>

        <!-- Active Trips -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-truck-moving text-blue-600"></i> Active Trips
                    </h2>
                    <a href="{{ route('trips.index') }}" class="inline-flex items-center gap-1 px-4 py-1.5 text-sm font-semibold text-white bg-[#2563EB] rounded-full shadow-sm transition hover:bg-[#1D4ED8] focus:outline-none focus:ring-2 focus:ring-blue-300">
                        View All <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                @forelse($activeTrips as $trip)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $trip->driver->name }}</p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-truck"></i> {{ $trip->vehicle->plate_number }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Client: {{ $trip->deliveryRequest->client->name }}
                        </p>
                    </div>
                    <div class="text-right ml-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            <i class="fas fa-circle animate-pulse"></i> In Transit
                        </span>
                        <p class="text-xs text-gray-500 mt-1">{{ $trip->scheduled_time->format('h:i A') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No active trips</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('#recent-requests-container');
        if (!container) {
            return;
        }

        const setLoadingState = (isLoading) => {
            container.classList.toggle('opacity-50', isLoading);
            container.classList.toggle('pointer-events-none', isLoading);
        };

        const loadRecentRequests = async (url) => {
            try {
                setLoadingState(true);
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                if (data.html) {
                    container.innerHTML = data.html;
                }
            } catch (error) {
                console.error('Failed to load recent requests:', error);
            } finally {
                setLoadingState(false);
            }
        };

        container.addEventListener('click', (event) => {
            const paginationLink = event.target.closest('a[data-pagination="recent-requests"]');
            if (!paginationLink) {
                return;
            }

            event.preventDefault();
            loadRecentRequests(paginationLink.href);
        });
    });
</script>
@endpush