@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Reports & Analytics</h1>
        <p class="text-gray-600 mt-1">View comprehensive reports and statistics</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Today's Trips</p>
                    <p class="text-4xl font-bold">{{ $stats['today_trips'] }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">This Week</p>
                    <p class="text-4xl font-bold">{{ $stats['week_trips'] }}</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">This Month</p>
                    <p class="text-4xl font-bold">{{ $stats['month_trips'] }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Active Drivers</p>
                    <p class="text-4xl font-bold">{{ $stats['total_drivers'] }}</p>
                </div>
                <svg class="w-12 h-12 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm">Vehicles</p>
                    <p class="text-4xl font-bold">{{ $stats['total_vehicles'] }}</p>
                </div>
                <svg class="w-12 h-12 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm">Total Clients</p>
                    <p class="text-4xl font-bold">{{ $stats['total_clients'] }}</p>
                </div>
                <svg class="w-12 h-12 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Report Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Daily Reports -->
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Daily Reports</h3>
                </div>
                <p class="text-gray-600 text-sm">View daily trip summaries and statistics</p>
            </div>
            <div class="p-6">
                <a href="{{ route('reports.daily') }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    View Daily Report
                </a>
            </div>
        </div>

        <!-- Weekly Reports -->
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Weekly Reports</h3>
                </div>
                <p class="text-gray-600 text-sm">Analyze weekly performance trends</p>
            </div>
            <div class="p-6">
                <a href="{{ route('reports.weekly') }}" class="block w-full text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    View Weekly Report
                </a>
            </div>
        </div>

        <!-- Monthly Reports -->
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Monthly Reports</h3>
                </div>
                <p class="text-gray-600 text-sm">Monthly performance overview</p>
            </div>
            <div class="p-6">
                <a href="{{ route('reports.monthly') }}" class="block w-full text-center bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                    View Monthly Report
                </a>
            </div>
        </div>

        <!-- Driver Performance
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Driver Performance</h3>
                </div>
                <p class="text-gray-600 text-sm">Track driver efficiency and metrics</p>
            </div>
            <div class="p-6">
                <a href="{{ route('reports.driver-performance') }}" class="block w-full text-center bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
                    View Performance
                </a>
            </div>
        </div> -->

        <!-- Vehicle Utilization
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-indigo-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Vehicle Utilization</h3>
                </div>
                <p class="text-gray-600 text-sm">Monitor vehicle usage and efficiency</p>
            </div>
            <div class="p-6">
                <a href="{{ route('reports.vehicle-utilization') }}" class="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    View Utilization
                </a>
            </div>
        </div> -->

        <!-- Client Activity
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-pink-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Client Activity</h3>
                </div>
                <p class="text-gray-600 text-sm">Review client engagement and orders</p>
            </div>
            <div class="p-6">
                <a href="{{ route('reports.client-activity') }}" class="block w-full text-center bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition">
                    View Activity
                </a>
            </div>
        </div> -->

        <!-- On-Time Delivery
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-teal-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">On-Time Delivery</h3>
                </div>
                <p class="text-gray-600 text-sm">Track punctuality and timeliness</p>
            </div>
            <div class="p-6">
                <a href="{{ route('reports.on-time-delivery') }}" class="block w-full text-center bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                    View Report
                </a>
            </div>
        </div> -->

        <!-- Custom Reports -->
        <!-- <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Custom Reports</h3>
                </div>
                <p class="text-gray-600 text-sm">Create customized reports with filters</p>
            </div>
            <div class="p-6">
                <a href="{{ route('reports.custom') }}" class="block w-full text-center bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    Create Custom Report
                </a>
            </div> -->
    </div>

    <!-- Dispatch Sheet
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Dispatch Sheet</h3>
                </div>
                <p class="text-gray-600 text-sm">Generate daily dispatch sheets</p>
            </div>
            <div class="p-6">
                <a href="{{ route('reports.dispatch-sheet') }}" class="block w-full text-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    Generate Sheet
                </a>
            </div>
        </div>
    </div>
</div> -->
    @endsection