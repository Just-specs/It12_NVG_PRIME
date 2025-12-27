@extends('layouts.app')

@section('title', 'Financial Summary Report')

@section('content')
<div class="container mx-auto px-4 max-w-7xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-chart-line text-green-600"></i> Financial Summary Report
        </h1>
        <p class="text-gray-600 mt-2">Revenue, expenses, and profit analysis</p>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('reports.financial') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-600">?{{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Payroll</p>
                    <p class="text-2xl font-bold text-blue-600">?{{ number_format($totalPayroll, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Allowance</p>
                    <p class="text-2xl font-bold text-purple-600">?{{ number_format($totalAllowance, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-hand-holding-usd text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Net Profit</p>
                    <p class="text-2xl font-bold text-{{ $totalProfit >= 0 ? 'green' : 'red' }}-600">?{{ number_format($totalProfit, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-{{ $totalProfit >= 0 ? 'green' : 'red' }}-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-{{ $totalProfit >= 0 ? 'green' : 'red' }}-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Trips Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-table text-blue-600"></i> Trip Details
            </h2>
            <p class="text-sm text-gray-600 mt-1">{{ $trips->count() }} trips found</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trip ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Payroll</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Allowance</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($trips as $trip)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('trips.show', $trip) }}" class="text-blue-600 hover:text-blue-800 font-mono">
                                #{{ $trip->id }}
                            </a>
                        </td>
                        <td class="px-6 py-4">{{ $trip->deliveryRequest->client->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $trip->driver->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $trip->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-green-600">
                            ?{{ number_format($trip->trip_rate, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-blue-600">
                            ?{{ number_format($trip->driver_payroll ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-purple-600">
                            ?{{ number_format($trip->driver_allowance ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-{{ ($trip->trip_rate - ($trip->driver_payroll ?? 0) - ($trip->driver_allowance ?? 0)) >= 0 ? 'green' : 'red' }}-600">
                            ?{{ number_format($trip->trip_rate - ($trip->driver_payroll ?? 0) - ($trip->driver_allowance ?? 0), 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No financial data available for the selected period</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
