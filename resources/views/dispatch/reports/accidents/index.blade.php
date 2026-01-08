@extends('layouts.app')

@section('title', 'Accident Reports')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-exclamation-triangle text-red-600"></i> Accident Reports
        </h1>
        <a href="{{ route('reports.accidents.create') }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i> New Accident Report
        </a>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('reports.accidents.index') }}" class="flex flex-wrap items-end gap-4">
            <input type="hidden" name="status" value="{{ $status }}">
            
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt text-red-600"></i> Start Date
                </label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt text-red-600"></i> End Date
                </label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md transition-colors">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                
                @if($startDate || $endDate)
                <a href="{{ route('reports.accidents.index', ['status' => $status]) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
                @endif
            </div>
        </form>

        @if($startDate || $endDate)
        <div class="mt-3 text-sm text-gray-600">
            <i class="fas fa-info-circle text-blue-500"></i> 
            Showing reports 
            @if($startDate && $endDate)
                from <strong>{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}</strong> 
                to <strong>{{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</strong>
            @elseif($startDate)
                from <strong>{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}</strong> onwards
            @else
                up to <strong>{{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</strong>
            @endif
        </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-500">
            <p class="text-gray-600 text-sm">Total Reports</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Under Investigation</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['under_investigation'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Resolved</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['resolved'] }}</p>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="bg-white rounded-lg shadow mb-4">
        <div class="flex border-b">
            <a href="{{ route('reports.accidents.index') }}" 
               class="px-6 py-3 {{ $status === 'all' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-800' }}">
                All Reports
            </a>
            <a href="{{ route('reports.accidents.index', ['status' => 'pending']) }}" 
               class="px-6 py-3 {{ $status === 'pending' ? 'border-b-2 border-yellow-600 text-yellow-600 font-semibold' : 'text-gray-600 hover:text-gray-800' }}">
                Pending
            </a>
            <a href="{{ route('reports.accidents.index', ['status' => 'under_investigation']) }}" 
               class="px-6 py-3 {{ $status === 'under_investigation' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-800' }}">
                Under Investigation
            </a>
            <a href="{{ route('reports.accidents.index', ['status' => 'resolved']) }}" 
               class="px-6 py-3 {{ $status === 'resolved' ? 'border-b-2 border-green-600 text-green-600 font-semibold' : 'text-gray-600 hover:text-gray-800' }}">
                Resolved
            </a>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-red-500">Trip</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-red-500">Driver</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-red-500">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-red-500">Severity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-red-500">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase border-l-2 border-red-500">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reports as $report)
                <tr class="hover:bg-gray-50 cursor-pointer transition-colors" onclick="window.location.href='{{ route('reports.accidents.show', $report) }}'">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $report->accident_date->format('M d, Y') }}
                        <div class="text-xs text-gray-500">{{ $report->accident_date->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="font-medium text-blue-600">Trip #{{ $report->trip_id }}</div>
                        <div class="text-xs text-gray-500">{{ $report->vehicle->plate_number ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $report->driver->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ Str::limit($report->location, 30) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $report->severity === 'minor' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $report->severity === 'moderate' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $report->severity === 'severe' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $report->severity === 'fatal' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($report->severity) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $report->status === 'under_investigation' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $report->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $report->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center" onclick="event.stopPropagation()">
                        <a href="{{ route('reports.accidents.export-pdf', $report) }}" 
                           target="_blank"
                           class="inline-block text-purple-600 hover:text-purple-800 mr-3" 
                           title="Export PDF">
                            <i class="fas fa-file-pdf text-lg"></i>
                        </a>
                        @if(in_array(auth()->user()->role, ['admin', 'head_dispatch']))
                        <a href="{{ route('reports.accidents.edit', $report) }}" 
                           class="inline-block text-green-600 hover:text-green-800" 
                           title="Edit">
                            <i class="fas fa-edit text-lg"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                        <p>No accident reports found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reports->hasPages())
    <div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <span class="text-sm text-gray-600">
            Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} reports
        </span>
        <div class="flex gap-3">
            @if($reports->onFirstPage())
            <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left mr-1"></i> Previous
            </span>
            @else
            <a href="{{ $reports->previousPageUrl() }}" 
                class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition-colors">
                <i class="fas fa-chevron-left mr-1"></i> Previous
            </a>
            @endif

            @if($reports->hasMorePages())
            <a href="{{ $reports->nextPageUrl() }}" 
                class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition-colors">
                Next <i class="fas fa-chevron-right ml-1"></i>
            </a>
            @else
            <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                Next <i class="fas fa-chevron-right ml-1"></i>
            </span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
