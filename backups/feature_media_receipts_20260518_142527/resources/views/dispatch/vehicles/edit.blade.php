@extends('layouts.app')

@section('title', 'Edit Vehicle - ' . $vehicle->plate_number)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('vehicles.show', $vehicle) }}" class="text-blue-600 hover:text-blue-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to Vehicle Profile
        </a>
    </div>

    <!-- Edit Form Card -->
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-6 text-white">
            <h1 class="text-2xl font-bold flex items-center">
                <i class="fas fa-edit mr-3"></i>Edit Vehicle Information
            </h1>
            <p class="text-green-100 mt-1">Update vehicle details below</p>
        </div>

        <!-- Form -->
        <form id="edit-vehicle-form" method="POST" action="{{ route('vehicles.update', $vehicle) }}" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="confirm_duplicate" id="confirm_duplicate" value="0">

            <!-- Plate Number -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Plate Number <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="plate_number" 
                       id="plate_number"
                       value="{{ old('plate_number', $vehicle->plate_number) }}" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="ABC1234">
                @error('plate_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Vehicle Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Vehicle Type <span class="text-red-500">*</span>
                </label>
                <select name="vehicle_type" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Select Type</option>
                    <option value="Prime Mover" {{ old('vehicle_type', $vehicle->vehicle_type) == 'Prime Mover' ? 'selected' : '' }}>Prime Mover</option>
                    <option value="Container Truck" {{ old('vehicle_type', $vehicle->vehicle_type) == 'Container Truck' ? 'selected' : '' }}>Container Truck</option>
                    <option value="Flatbed Truck" {{ old('vehicle_type', $vehicle->vehicle_type) == 'Flatbed Truck' ? 'selected' : '' }}>Flatbed Truck</option>
                    <option value="Tanker Truck" {{ old('vehicle_type', $vehicle->vehicle_type) == 'Tanker Truck' ? 'selected' : '' }}>Tanker Truck</option>
                    <option value="Box Truck" {{ old('vehicle_type', $vehicle->vehicle_type) == 'Box Truck' ? 'selected' : '' }}>Box Truck</option>
                </select>
                @error('vehicle_type')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Trailer Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Trailer Type <span class="text-red-500">*</span>
                </label>
                <select name="trailer_type" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Select Trailer</option>
                    <option value="20ft Container" {{ old('trailer_type', $vehicle->trailer_type) == '20ft Container' ? 'selected' : '' }}>20ft Container</option>
                    <option value="40ft Container" {{ old('trailer_type', $vehicle->trailer_type) == '40ft Container' ? 'selected' : '' }}>40ft Container</option>
                    <option value="20ft Reefer" {{ old('trailer_type', $vehicle->trailer_type) == '20ft Reefer' ? 'selected' : '' }}>20ft Reefer</option>
                    <option value="40ft Reefer" {{ old('trailer_type', $vehicle->trailer_type) == '40ft Reefer' ? 'selected' : '' }}>40ft Reefer</option>
                    <option value="Flatbed" {{ old('trailer_type', $vehicle->trailer_type) == 'Flatbed' ? 'selected' : '' }}>Flatbed</option>
                </select>
                @error('trailer_type')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" id="submit-btn" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition flex-1">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a href="{{ route('vehicles.show', $vehicle) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition text-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Duplicate Warning Modal -->
<div id="duplicate-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="flex min-h-full items-center justify-center bg-black/50 px-4">
        <div class="w-full max-w-lg rounded-lg bg-white p-6 shadow-xl">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-gray-800">Similar Plate Numbers Found</h2>
                    <p class="mt-2 text-sm text-gray-600">The following vehicles have similar plate numbers. Are you sure you want to update to this?</p>
                    
                    <div id="similar-vehicles-list" class="mt-4 space-y-2 max-h-48 overflow-y-auto">
                        <!-- Similar vehicles will be inserted here -->
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" id="cancel-btn" class="px-4 py-2 rounded-lg bg-gray-500 text-white transition-colors hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancel
                        </button>
                        <button type="button" id="proceed-btn" class="px-4 py-2 rounded-lg bg-[#2563EB] text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            Yes, Update Anyway
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('edit-vehicle-form');
    const modal = document.getElementById('duplicate-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const proceedBtn = document.getElementById('proceed-btn');
    const confirmDuplicateInput = document.getElementById('confirm_duplicate');
    const similarVehiclesList = document.getElementById('similar-vehicles-list');
    const submitBtn = document.getElementById('submit-btn');

    const showModal = (similarVehicles) => {
        similarVehiclesList.innerHTML = '';
        similarVehicles.forEach(vehicle => {
            const div = document.createElement('div');
            div.className = 'p-3 bg-yellow-50 border border-yellow-200 rounded-lg';
            div.innerHTML = `
                <p class="font-semibold text-gray-800">${vehicle.plate_number}</p>
                ${vehicle.vehicle_type ? `<p class="text-xs text-gray-600">Type: ${vehicle.vehicle_type}</p>` : ''}
                ${vehicle.trailer_type ? `<p class="text-xs text-gray-600">Trailer: ${vehicle.trailer_type}</p>` : ''}
                <p class="text-xs text-gray-500">Status: ${vehicle.status}</p>
            `;
            similarVehiclesList.appendChild(div);
        });

        modal.classList.remove('hidden');
        proceedBtn.focus();
    };

    const hideModal = () => {
        modal.classList.add('hidden');
        confirmDuplicateInput.value = '0';
    };

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (confirmDuplicateInput.value === '1') {
            form.removeEventListener('submit', arguments.callee);
            form.submit();
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (data.requires_confirmation) {
                showModal(data.similar_vehicles);
            } else if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'An error occurred');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while checking for duplicates');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Changes';
        }
    });

    cancelBtn.addEventListener('click', hideModal);

    proceedBtn.addEventListener('click', () => {
        confirmDuplicateInput.value = '1';
        hideModal();
        form.submit();
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            hideModal();
        }
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            hideModal();
        }
    });
});
</script>
@endsection
