@extends('layouts.app')

@section('title', 'Add New Vehicle')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white bg-[#2563EB] rounded-full hover:bg-blue-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-truck text-blue-600"></i> Add New Vehicle
        </h1>

        <form id="create-vehicle-form" method="POST" action="{{ route('vehicles.store') }}">
            @csrf
            <input type="hidden" name="confirm_duplicate" id="confirm_duplicate" value="0">

            <div class="space-y-4">
                <!-- Plate Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Plate Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="plate_number" id="plate_number" required value="{{ old('plate_number') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter plate number (e.g., ABC-1234)">
                    @error('plate_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vehicle Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Vehicle Type <span class="text-red-500">*</span>
                    </label>
                    <select name="vehicle_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select vehicle type</option>
                        <option value="Prime Mover" {{ old('vehicle_type') == 'Prime Mover' ? 'selected' : '' }}>Prime Mover</option>
                        <option value="Truck" {{ old('vehicle_type') == 'Truck' ? 'selected' : '' }}>Truck</option>
                        <option value="Trailer Truck" {{ old('vehicle_type') == 'Trailer Truck' ? 'selected' : '' }}>Trailer Truck</option>
                        <option value="Van" {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}>Van</option>
                        <option value="Pickup" {{ old('vehicle_type') == 'Pickup' ? 'selected' : '' }}>Pickup</option>
                    </select>
                    @error('vehicle_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trailer Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Trailer Type <span class="text-red-500">*</span>
                    </label>
                    <select name="trailer_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select trailer type</option>
                        <option value="Flatbed" {{ old('trailer_type') == 'Flatbed' ? 'selected' : '' }}>Flatbed</option>
                        <option value="Container" {{ old('trailer_type') == 'Container' ? 'selected' : '' }}>Container</option>
                        <option value="Lowbed" {{ old('trailer_type') == 'Lowbed' ? 'selected' : '' }}>Lowbed</option>
                        <option value="Refrigerated" {{ old('trailer_type') == 'Refrigerated' ? 'selected' : '' }}>Refrigerated</option>
                        <option value="Tanker" {{ old('trailer_type') == 'Tanker' ? 'selected' : '' }}>Tanker</option>
                        <option value="N/A" {{ old('trailer_type') == 'N/A' ? 'selected' : '' }}>N/A</option>
                    </select>
                    @error('trailer_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="in-use" {{ old('status') == 'in-use' ? 'selected' : '' }}>In Use</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-6">
                <button type="submit" id="submit-btn" class="px-6 py-2 bg-[#2563EB] text-white rounded-lg transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-[#2563EB] focus:ring-offset-2">
                    <i class="fas fa-save"></i> Save Vehicle
                </button>
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
                    <p class="mt-2 text-sm text-gray-600">The following vehicles have similar plate numbers. Are you sure you want to add another vehicle?</p>
                    
                    <div id="similar-vehicles-list" class="mt-4 space-y-2 max-h-48 overflow-y-auto">
                        <!-- Similar vehicles will be inserted here -->
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" id="cancel-btn" class="px-4 py-2 rounded-lg bg-gray-500 text-white transition-colors hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancel
                        </button>
                        <button type="button" id="proceed-btn" class="px-4 py-2 rounded-lg bg-[#2563EB] text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            Yes, Add Anyway
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('create-vehicle-form');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
                    'X-CSRF-TOKEN': csrfToken
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
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Vehicle';
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

