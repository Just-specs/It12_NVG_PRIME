@extends('layouts.app')

@section('title', 'Financial Reports')

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-chart-line text-green-600"></i> Financial Reports
            </h1>
            <p class="text-gray-600 mt-1">Revenue, expenses, and profit analysis</p>
        </div>

        <!-- Period Filter -->
        <div class="flex items-center gap-3">
            <select id="periodFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
            </select>
            <a href="{{ route('financial.export', ['period' => $period]) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-file-excel"></i> Export
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold mt-2">₱{{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Total Expenses</p>
                    <p class="text-3xl font-bold mt-2">₱{{ number_format($stats['total_expenses'], 2) }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Profit Margin -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Profit Margin</p>
                    <p class="text-3xl font-bold mt-2">₱{{ number_format($stats['profit_margin'], 2) }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Trips -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Trips</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_trips'] }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-truck text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Daily Revenue Trend -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-bar text-blue-600"></i> 7-Day Revenue Trend
            </h2>
            <div class="space-y-3">
                @foreach($dailyTrend as $day)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 w-20">{{ $day['date'] }}</span>
                    <div class="flex-1 mx-4">
                        <div class="h-8 bg-gray-200 rounded-full overflow-hidden">
                            @php
                                $maxRevenue = collect($dailyTrend)->max('revenue');
                                $percentage = $maxRevenue > 0 ? ($day['revenue'] / $maxRevenue * 100) : 0;
                            @endphp
                            <div class="h-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-end pr-2 text-white text-xs font-bold" 
                                style="width: {{ $percentage }}%">
                                @if($percentage > 20)
                                    ₱{{ number_format($day['revenue'], 0) }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <span class="text-sm font-bold {{ $day['profit'] >= 0 ? 'text-green-600' : 'text-red-600' }} w-24 text-right">
                        ₱{{ number_format($day['profit'], 0) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Drivers (Hustling) -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-trophy text-yellow-600"></i> Top Drivers (Hustling)
            </h2>
            <div class="space-y-3">
                @forelse($topDrivers as $index => $driver)
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $driver->name }}</p>
                        <p class="text-xs text-gray-600">{{ $driver->trips_count }} trips</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-600">₱{{ number_format($driver->total_earnings, 2) }}</p>
                        <p class="text-xs text-gray-500">earnings</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No driver data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Client Revenue Breakdown -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-building text-blue-600"></i> Top Clients by Revenue
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Trips</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($clientRevenue as $index => $client)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-800 font-bold text-xs">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $client->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $client->company ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $client->trips_count }}</td>
                        <td class="px-4 py-3 text-right font-bold text-green-600">₱{{ number_format($client->total_revenue, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No client data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('periodFilter').addEventListener('change', function() {
    window.location.href = '{{ route("financial.index") }}?period=' + this.value;
});
</script>
@endsection
