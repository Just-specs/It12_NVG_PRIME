@extends('layouts.app')

@section('title', 'Provide Delay Reason')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('trips.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-exclamation-triangle text-orange-600"></i> Provide Delay Reason - Trip #{{ $trip->id }}
        </h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <!-- Trip Information -->
        <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
            <h3 class="text-lg font-semibold text-yellow-800 mb-3">
                <i class="fas fa-truck mr-2"></i> Trip Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Waybill Number:</span>
                    <span class="text-gray-900">{{ $trip->waybill_number ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Driver:</span>
                    <span class="text-gray-900">{{ $trip->driver->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Vehicle:</span>
                    <span class="text-gray-900">{{ $trip->vehicle->plate_number ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Scheduled Time:</span>
                    <span class="text-gray-900">{{ $trip->scheduled_time->format('M d, Y h:i A') }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Current Delay:</span>
                    <span class="text-red-600 font-semibold">{{ $trip->delay_minutes ?? 'Calculating...' }} minutes</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Status:</span>
                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">
                        {{ ucfirst($trip->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Existing Delay Reason (if any) -->
        @if($trip->delay_reason)
        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">
                <i class="fas fa-info-circle mr-2"></i> Current Delay Reason
            </h3>
            <p class="text-gray-800 whitespace-pre-wrap">{{ $trip->delay_reason }}</p>
            @if($trip->delayReasonBy)
            <p class="text-sm text-gray-600 mt-2">
                Provided by: <strong>{{ $trip->delayReasonBy->name }}</strong>
            </p>
            @endif
        </div>
        @endif

        <!-- Delay Reason Form -->
        <form action="{{ route('trips.submit-delay-reason', $trip) }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment-alt mr-1"></i> Delay Reason *
                </label>
                <textarea name="delay_reason" rows="6" required
                          placeholder="Please provide a detailed explanation for the delay..."
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">{{ old('delay_reason', $trip->delay_reason) }}</textarea>
                @error('delay_reason')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Minimum 10 characters, maximum 1000 characters</p>
            </div>

            <div class="flex justify-between items-center pt-6 border-t">
                <a href="{{ route('trips.index') }}" 
                   class="text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit" 
                        class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i> {{ $trip->delay_reason ? 'Update' : 'Submit' }} Delay Reason
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
