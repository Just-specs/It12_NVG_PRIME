@extends('layouts.app')

@section('title', 'Driver Earnings Report')

@section('content')
<div class="container mx-auto px-4 max-w-7xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-users text-blue-600"></i> Driver Earnings Report
        </h1>
        <p class="text-gray-600 mt-2">Track driver payroll and allowances</p>
    </div>

    <!-- Date Range & Driver Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('reports.driver-earnings') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Driver (Optional)</label>
                <select name="driver_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Drivers</option>
                    @foreach($allDrivers as $driver)
                    <option value="{{ $driver->id }}" {{ $driverId == $driver->id ? 'selected' : '' }}>
                        {{ $driver->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <!-- Driver Summary Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-chart-bar text-blue-600"></i> Driver Earnings Summary
            </h2>
            <p class="text-sm text-gray-600 mt-1">{{ $driverEarnings->count() }} active drivers</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Trips</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Payroll</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Allowance</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Earnings</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($driverEarnings as $driver)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $driver->name }}</div>
                            <div class="text-xs text-gray-500">{{ $driver->license_number }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $driver->mobile }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                {{ $driver->total_trips }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-blue-600">
                            ?{{ number_format($driver->total_payroll ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-purple-600">
                            ?{{ number_format($driver->total_allowance ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-green-600">
                            ?{{ number_format(($driver->total_payroll ?? 0) + ($driver->total_allowance ?? 0), 2) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('reports.driver-earnings', ['driver_id' => $driver->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No driver earnings data for the selected period</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Selected Driver Details -->
    @if($selectedDriver && $driverTrips)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b bg-blue-50">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-user text-blue-600"></i> Trip Details: {{ $selectedDriver->name }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">{{ $driverTrips->count() }} trips completed</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trip ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Route</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Payroll</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Allowance</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($driverTrips as $trip)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('trips.show', $trip) }}" class="text-blue-600 hover:text-blue-800 font-mono">
                                #{{ $trip->id }}
                            </a>
                        </td>
                        <td class="px-6 py-4">{{ $trip->deliveryRequest->client->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $trip->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="text-xs text-gray-600 truncate max-w-xs">
                                {{ $trip->deliveryRequest->pickup_location }} ? {{ $trip->deliveryRequest->delivery_location }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-blue-600">
                            ?{{ number_format($trip->driver_payroll ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-purple-600">
                            ?{{ number_format($trip->driver_allowance ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-green-600">
                            ?{{ number_format(($trip->driver_payroll ?? 0) + ($trip->driver_allowance ?? 0), 2) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="4" class="px-6 py-4 text-right">TOTAL:</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-blue-600">
                            ?{{ number_format($driverTrips->sum('driver_payroll'), 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-purple-600">
                            ?{{ number_format($driverTrips->sum('driver_allowance'), 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-green-600 text-lg">
                            ?{{ number_format($driverTrips->sum('driver_payroll') + $driverTrips->sum('driver_allowance'), 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
