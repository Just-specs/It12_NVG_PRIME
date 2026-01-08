@extends('layouts.app')

@section('title', 'Accident Report #' . $accident->id)

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('reports.accidents.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-exclamation-triangle text-red-600"></i> Accident Report #{{ $accident->id }}
            </h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.accidents.export-pdf', $accident) }}" target="_blank"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
            @if(in_array(auth()->user()->role, ['admin', 'head_dispatch']))
            <a href="{{ route('reports.accidents.edit', $accident) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Status and Severity Header -->
        <div class="flex justify-between items-center mb-6 pb-4 border-b">
            <div>
                <p class="text-sm text-gray-600 mb-1">Status</p>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $accident->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $accident->status === 'under_investigation' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $accident->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $accident->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $accident->status)) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Severity</p>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $accident->severity === 'minor' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $accident->severity === 'moderate' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $accident->severity === 'severe' ? 'bg-orange-100 text-orange-800' : '' }}
                    {{ $accident->severity === 'fatal' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($accident->severity) }}
                </span>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-info-circle text-blue-600"></i> Basic Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Accident Date & Time</p>
                    <p class="font-semibold text-gray-800">{{ $accident->accident_date->format('M d, Y h:i A') }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Location</p>
                    <p class="font-semibold text-gray-800">{{ $accident->location }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Reported By</p>
                    <p class="font-semibold text-gray-800">{{ $accident->reportedBy->name ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Report Date</p>
                    <p class="font-semibold text-gray-800">{{ $accident->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Trip Information -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-route text-blue-600"></i> Trip Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <p class="text-sm text-gray-600">Trip</p>
                    <a href="{{ route('trips.show', $accident->trip_id) }}" class="font-semibold text-blue-600 hover:underline">
                        Trip #{{ $accident->trip_id }}
                    </a>
                    <p class="text-sm text-gray-500">{{ $accident->trip->waybill_number ?? 'N/A' }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <p class="text-sm text-gray-600">Driver</p>
                    <p class="font-semibold text-gray-800">{{ $accident->driver->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $accident->driver->license_number ?? '' }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <p class="text-sm text-gray-600">Vehicle</p>
                    <p class="font-semibold text-gray-800">{{ $accident->vehicle->plate_number ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $accident->vehicle->model ?? '' }}</p>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-file-alt text-blue-600"></i> Description
            </h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-800 whitespace-pre-wrap">{{ $accident->description }}</p>
            </div>
        </div>

        <!-- Additional Details -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-list text-blue-600"></i> Additional Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($accident->injuries)
                <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
                    <p class="text-sm font-semibold text-red-800 mb-2">
                        <i class="fas fa-user-injured"></i> Injuries
                    </p>
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $accident->injuries }}</p>
                </div>
                @endif

                @if($accident->vehicle_damage)
                <div class="bg-orange-50 p-4 rounded-lg border-l-4 border-orange-500">
                    <p class="text-sm font-semibold text-orange-800 mb-2">
                        <i class="fas fa-car-crash"></i> Vehicle Damage
                    </p>
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $accident->vehicle_damage }}</p>
                </div>
                @endif

                @if($accident->other_party_info)
                <div class="bg-purple-50 p-4 rounded-lg border-l-4 border-purple-500">
                    <p class="text-sm font-semibold text-purple-800 mb-2">
                        <i class="fas fa-users"></i> Other Party Information
                    </p>
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $accident->other_party_info }}</p>
                </div>
                @endif

                @if($accident->witness_info)
                <div class="bg-indigo-50 p-4 rounded-lg border-l-4 border-indigo-500">
                    <p class="text-sm font-semibold text-indigo-800 mb-2">
                        <i class="fas fa-eye"></i> Witness Information
                    </p>
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $accident->witness_info }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Police Report -->
        @if($accident->police_report_filed || $accident->police_report_number)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-shield-alt text-blue-600"></i> Police Report
            </h3>
            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Report Filed</p>
                        <p class="font-semibold {{ $accident->police_report_filed ? 'text-green-600' : 'text-red-600' }}">
                            {{ $accident->police_report_filed ? 'Yes' : 'No' }}
                        </p>
                    </div>
                    @if($accident->police_report_number)
                    <div>
                        <p class="text-sm text-gray-600">Report Number</p>
                        <p class="font-semibold text-gray-800">{{ $accident->police_report_number }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Action Taken & Cost -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-tasks text-blue-600"></i> Action & Costs
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($accident->action_taken)
                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                    <p class="text-sm font-semibold text-green-800 mb-2">
                        <i class="fas fa-check-circle"></i> Action Taken
                    </p>
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $accident->action_taken }}</p>
                </div>
                @endif

                @if($accident->estimated_damage_cost)
                <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                    <p class="text-sm font-semibold text-yellow-800 mb-2">
                        <i class="fas fa-dollar-sign"></i> Estimated Damage Cost
                    </p>
                    <p class="text-2xl font-bold text-gray-800">?{{ number_format($accident->estimated_damage_cost, 2) }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Resolution (if resolved) -->
        @if($accident->status === 'resolved' || $accident->status === 'closed')
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-check-circle text-green-600"></i> Resolution
            </h3>
            <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                @if($accident->resolved_at)
                <p class="text-sm text-gray-600 mb-2">Resolved At: {{ $accident->resolved_at->format('M d, Y h:i A') }}</p>
                @endif
                @if($accident->resolution_notes)
                <p class="text-gray-800 whitespace-pre-wrap">{{ $accident->resolution_notes }}</p>
                @else
                <p class="text-gray-500 italic">No resolution notes provided</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
