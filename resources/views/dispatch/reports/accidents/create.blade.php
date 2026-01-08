@extends('layouts.app')

@section('title', 'New Accident Report')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('reports.accidents.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-exclamation-triangle text-red-600"></i> New Accident Report
        </h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('reports.accidents.store') }}" method="POST">
            @csrf

            <!-- Trip Selection -->
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500">
                <h3 class="text-lg font-semibold text-red-800 mb-3">Trip Information</h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Trip *</label>
                        <select name="trip_id" id="trip_id" required 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            <option value="">-- Select Trip --</option>
                            @foreach($trips as $t)
                            <option value="{{ $t->id }}" {{ (old('trip_id') == $t->id || ($trip && $trip->id == $t->id)) ? 'selected' : '' }}
                                    data-driver="{{ $t->driver->name ?? 'N/A' }}"
                                    data-vehicle="{{ $t->vehicle->plate_number ?? 'N/A' }}"
                                    data-client="{{ $t->deliveryRequest->client->name ?? 'N/A' }}">
                                Trip #{{ $t->id }} - {{ $t->waybill_number }} - {{ $t->deliveryRequest->client->name ?? 'N/A' }} ({{ $t->scheduled_time->format('M d, Y') }})
                            </option>
                            @endforeach
                        </select>
                        @error('trip_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($trip)
                    <div class="grid grid-cols-3 gap-4 mt-2">
                        <div class="bg-white p-3 rounded border">
                            <p class="text-xs text-gray-600">Driver</p>
                            <p class="font-semibold">{{ $trip->driver->name ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <p class="text-xs text-gray-600">Vehicle</p>
                            <p class="font-semibold">{{ $trip->vehicle->plate_number ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <p class="text-xs text-gray-600">Client</p>
                            <p class="font-semibold">{{ $trip->deliveryRequest->client->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Accident Details -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Accident Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Accident Date & Time *</label>
                        <input type="datetime-local" name="accident_date" value="{{ old('accident_date') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        @error('accident_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Severity *</label>
                        <select name="severity" required 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            <option value="minor" {{ old('severity') === 'minor' ? 'selected' : '' }}>Minor</option>
                            <option value="moderate" {{ old('severity') === 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="severe" {{ old('severity') === 'severe' ? 'selected' : '' }}>Severe</option>
                            <option value="fatal" {{ old('severity') === 'fatal' ? 'selected' : '' }}>Fatal</option>
                        </select>
                        @error('severity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                        <input type="text" name="location" value="{{ old('location') }}" required
                               placeholder="Enter accident location"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" rows="4" required
                                  placeholder="Describe what happened..."
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('description') }}</textarea>
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
                                  placeholder="Describe any injuries sustained..."
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('injuries') }}</textarea>
                        @error('injuries')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Damage</label>
                        <textarea name="vehicle_damage" rows="3"
                                  placeholder="Describe damage to vehicle..."
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('vehicle_damage') }}</textarea>
                        @error('vehicle_damage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Other Party Information</label>
                        <textarea name="other_party_info" rows="3"
                                  placeholder="Information about other parties involved..."
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('other_party_info') }}</textarea>
                        @error('other_party_info')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Witness Information</label>
                        <textarea name="witness_info" rows="3"
                                  placeholder="Names and contact info of witnesses..."
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('witness_info') }}</textarea>
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
                                   {{ old('police_report_filed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Police Report Filed</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Police Report Number</label>
                        <input type="text" name="police_report_number" value="{{ old('police_report_number') }}"
                               placeholder="Enter police report number"
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
                                  placeholder="Describe immediate actions taken..."
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('action_taken') }}</textarea>
                        @error('action_taken')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Damage Cost (?)</label>
                        <input type="number" name="estimated_damage_cost" value="{{ old('estimated_damage_cost') }}"
                               step="0.01" min="0" placeholder="0.00"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        @error('estimated_damage_cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center pt-6 border-t">
                <a href="{{ route('reports.accidents.index') }}" 
                   class="text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i> Submit Accident Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
