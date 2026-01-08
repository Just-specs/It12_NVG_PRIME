@extends('layouts.app')

@section('title', 'Request Vehicle Deletion')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                <i class="fas fa-exclamation-triangle text-yellow-600"></i> Request Deletion Approval
            </h1>
            <p class="text-gray-600">Submit a deletion request for admin approval</p>
        </div>

        <!-- Vehicle Information -->
        <div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-6 rounded">
            <h3 class="font-semibold text-purple-900 mb-2">Vehicle to be Deleted:</h3>
            <div class="text-purple-800">
                <p><strong>Plate Number:</strong> {{ $vehicle->plate_number }}</p>
                <p><strong>Model:</strong> {{ $vehicle->model }}</p>
                <p><strong>Type:</strong> {{ ucfirst($vehicle->vehicle_type) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($vehicle->status) }}</p>
            </div>
        </div>

        <!-- Warning Message -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Important:</strong> This request will be sent to the admin for approval. 
                        Please provide a clear reason for the deletion.
                    </p>
                </div>
            </div>
        </div>

        <!-- Request Form -->
        <form method="POST" action="{{ route('vehicles.submitDeleteRequest', $vehicle) }}">
            @csrf
            
            <div class="mb-6">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Deletion Reason <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="reason" 
                    id="reason" 
                    rows="5" 
                    required
                    minlength="10"
                    maxlength="500"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('reason') border-red-500 @enderror"
                    placeholder="Explain why this vehicle needs to be deleted (minimum 10 characters)...">{{ old('reason') }}</textarea>
                
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <p class="mt-1 text-sm text-gray-500">
                    <span id="char-count">0</span>/500 characters
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('vehicles.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-paper-plane"></i> Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('reason').addEventListener('input', function() {
    document.getElementById('char-count').textContent = this.value.length;
});
</script>
@endsection
