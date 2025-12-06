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
        <div id="today-schedule-container">
            @include('dispatch.dashboard.partials.today-schedule', ['todaySchedule' => $todaySchedule])
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
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
            <div class="p-6" id="active-trips-container">
                @include('dispatch.dashboard.partials.active-trips', ['activeTrips' => $activeTrips])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Recent Requests Pagination
        const recentRequestsContainer = document.querySelector('#recent-requests-container');
        if (recentRequestsContainer) {
            const setLoadingState = (container, isLoading) => {
                container.classList.toggle('opacity-50', isLoading);
                container.classList.toggle('pointer-events-none', isLoading);
            };

            const loadRecentRequests = async (url) => {
                try {
                    setLoadingState(recentRequestsContainer, true);
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
                        recentRequestsContainer.innerHTML = data.html;
                    }
                } catch (error) {
                    console.error('Failed to load recent requests:', error);
                } finally {
                    setLoadingState(recentRequestsContainer, false);
                }
            };

            recentRequestsContainer.addEventListener('click', (event) => {
                const paginationLink = event.target.closest('a[data-pagination="recent-requests"]');
                if (!paginationLink) {
                    return;
                }

                event.preventDefault();
                loadRecentRequests(paginationLink.href);
            });
        }

        // Active Trips Pagination
        const activeTripsContainer = document.querySelector('#active-trips-container');
        if (activeTripsContainer) {
            const setLoadingState = (container, isLoading) => {
                container.classList.toggle('opacity-50', isLoading);
                container.classList.toggle('pointer-events-none', isLoading);
            };

            const loadActiveTrips = async (url) => {
                try {
                    setLoadingState(activeTripsContainer, true);
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
                        activeTripsContainer.innerHTML = data.html;
                    }
                } catch (error) {
                    console.error('Failed to load active trips:', error);
                } finally {
                    setLoadingState(activeTripsContainer, false);
                }
            };

            activeTripsContainer.addEventListener('click', (event) => {
                const paginationLink = event.target.closest('a[data-pagination="active-trips"]');
                if (!paginationLink) {
                    return;
                }

                event.preventDefault();
                loadActiveTrips(paginationLink.href);
            });
        }

        // Today's Schedule Pagination
        const todayScheduleContainer = document.querySelector('#today-schedule-container');
        if (todayScheduleContainer) {
            const setLoadingState = (container, isLoading) => {
                container.classList.toggle('opacity-50', isLoading);
                container.classList.toggle('pointer-events-none', isLoading);
            };

            const loadTodaySchedule = async (url) => {
                try {
                    setLoadingState(todayScheduleContainer, true);
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
                        todayScheduleContainer.innerHTML = data.html;
                    }
                } catch (error) {
                    console.error('Failed to load today schedule:', error);
                } finally {
                    setLoadingState(todayScheduleContainer, false);
                }
            };

            todayScheduleContainer.addEventListener('click', (event) => {
                const paginationLink = event.target.closest('a[data-pagination="today-schedule"]');
                if (!paginationLink) {
                    return;
                }

                event.preventDefault();
                loadTodaySchedule(paginationLink.href);
            });
        }
    });
</script>
@endpush