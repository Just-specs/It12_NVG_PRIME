@extends('layouts.app')

@section('title', 'Weekly Report')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Weekly Report</h1>
            <p class="text-gray-600 mt-1">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('reports.weekly') }}" class="flex items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Week Start Date</label>
                <input type="date"
                    name="start_date"
                    value="{{ $startDate->format('Y-m-d') }}"
                    class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-search"></i> Generate Report
            </button>
            <a href="{{ route('reports.export-weekly', ['start_date' => $startDate->format('Y-m-d')]) }}"
                target="_blank"
                class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </form>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Total Trips</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['total_trips'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Completed</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">In Transit</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['in_transit'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Scheduled</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['scheduled'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Cancelled</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
        </div>
    </div>


    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Status Distribution Pie Chart -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Distribution</h3>
            <div style="height: 300px; position: relative;">
                <canvas id="statusPieChart"></canvas>
            </div>
        </div>

        <!-- Daily Trend Line Chart -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Trend</h3>
            <div style="height: 300px; position: relative;">
                <canvas id="trendLineChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Trips by Day -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Trips by Day</h2>
        </div>

        @if($trips->isEmpty())
        <div class="p-8 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No trips found for this week</p>
        </div>
        @else
        @foreach($tripsByDay as $date => $dayTrips)
        <div class="border-b border-gray-200">
            <div class="bg-gray-50 px-6 py-3">
                <h3 class="font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}
                    <span class="text-sm text-gray-600 font-normal ml-2">({{ $dayTrips->count() }} trips)</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">ATW Reference</th>
                            <th class="px-4 py-3 text-left">Client</th>
                            <th class="px-4 py-3 text-left">Driver</th>
                            <th class="px-4 py-3 text-left">Vehicle</th>
                            <th class="px-4 py-3 text-left">Time</th>
                            <th class="px-4 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dayTrips as $trip)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('trips.show', $trip) }}" class="text-blue-600 hover:underline">
                                    {{ $trip->deliveryRequest?->atw_reference ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ $trip->deliveryRequest?->client?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $trip->driver->name }}</td>
                            <td class="px-4 py-3">{{ $trip->vehicle ? $trip->vehicle->plate_number : 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $trip->scheduled_time->format('h:i A') }}</td>
                            <td class="px-4 py-3">
                                @php
                                $statusColors = [
                                'scheduled' => 'bg-blue-100 text-blue-800',
                                'in-transit' => 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md font-bold',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$trip->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($trip->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Weekly report charts loading...');

        if (typeof Chart === 'undefined') {
            console.error('Chart.js not loaded!');
            return;
        }

        // Status Pie Chart
        const statusCtx = document.getElementById('statusPieChart');
        if (statusCtx) {
            try {
                new Chart(statusCtx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($statusChartData['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($statusChartData['data']) !!},
                            backgroundColor: {!! json_encode($statusChartData['colors']) !!},
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
                console.log('Status pie chart created');
            } catch (e) {
                console.error('Status chart error:', e);
            }
        }

        // Daily Trend Line Chart
        const trendCtx = document.getElementById('trendLineChart');
        if (trendCtx) {
            try {
                const dailyData = {!! json_encode($dailyData) !!};
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: dailyData.map(d => d.date),
                        datasets: [{
                            label: 'Trips',
                            data: dailyData.map(d => d.count),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
                console.log('Trend line chart created');
            } catch (e) {
                console.error('Trend chart error:', e);
            }
        }
    });
</script>
@endpush
@endsection




