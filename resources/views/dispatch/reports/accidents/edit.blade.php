@extends('layouts.app')

@section('title', 'Edit Accident Report #' . $accident->id)

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('reports.accidents.show', $accident) }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-edit text-green-600"></i> Edit Accident Report #{{ $accident->id }}
        </h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('reports.accidents.update', $accident) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Trip Information (Read-only) -->
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">Trip Information (Read-only)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Trip</p>
                        <p class="font-semibold text-gray-800">Trip #{{ $accident->trip_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Driver</p>
                        <p class="font-semibold text-gray-800">{{ $accident->driver->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Vehicle</p>
                        <p class="font-semibold text-gray-800">{{ $accident->vehicle->plate_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Accident Details -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Accident Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Accident Date & Time *</label>
                        <input type="datetime-local" name="accident_date" 
                               value="{{ old('accident_date', $accident->accident_date->format('Y-m-d\TH:i')) }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        @error('accident_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Severity *</label>
                        <select name="severity" required 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            <option value="minor" {{ old('severity', $accident->severity) === 'minor' ? 'selected' : '' }}>Minor</option>
                            <option value="moderate" {{ old('severity', $accident->severity) === 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="severe" {{ old('severity', $accident->severity) === 'severe' ? 'selected' : '' }}>Severe</option>
                            <option value="fatal" {{ old('severity', $accident->severity) === 'fatal' ? 'selected' : '' }}>Fatal</option>
                        </select>
                        @error('severity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                        <input type="text" name="location" value="{{ old('location', $accident->location) }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" rows="4" required
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('description', $accident->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Additional Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Injuries (if any)</label>
                        <textarea name="injuries" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('injuries', $accident->injuries) }}</textarea>
                        @error('injuries')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Damage</label>
                        <textarea name="vehicle_damage" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('vehicle_damage', $accident->vehicle_damage) }}</textarea>
                        @error('vehicle_damage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Other Party Information</label>
                        <textarea name="other_party_info" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('other_party_info', $accident->other_party_info) }}</textarea>
                        @error('other_party_info')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Witness Information</label>
                        <textarea name="witness_info" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('witness_info', $accident->witness_info) }}</textarea>
                        @error('witness_info')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Police Report -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Police Report</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="police_report_filed" value="1" 
                                   {{ old('police_report_filed', $accident->police_report_filed) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Police Report Filed</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Police Report Number</label>
                        <input type="text" name="police_report_number" value="{{ old('police_report_number', $accident->police_report_number) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        @error('police_report_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action & Cost -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Action & Costs</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Action Taken</label>
                        <textarea name="action_taken" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('action_taken', $accident->action_taken) }}</textarea>
                        @error('action_taken')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Damage Cost (?)</label>
                        <input type="number" name="estimated_damage_cost" 
                               value="{{ old('estimated_damage_cost', $accident->estimated_damage_cost) }}"
                               step="0.01" min="0"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        @error('estimated_damage_cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status & Resolution -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Status & Resolution</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            <option value="pending" {{ old('status', $accident->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_investigation" {{ old('status', $accident->status) === 'under_investigation' ? 'selected' : '' }}>Under Investigation</option>
                            <option value="resolved" {{ old('status', $accident->status) === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ old('status', $accident->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Resolution Notes</label>
                        <textarea name="resolution_notes" rows="3"
                                  placeholder="Add resolution notes when marking as resolved or closed..."
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('resolution_notes', $accident->resolution_notes) }}</textarea>
                        @error('resolution_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center pt-6 border-t">
                <a href="{{ route('reports.accidents.show', $accident) }}" 
                   class="text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i> Update Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
