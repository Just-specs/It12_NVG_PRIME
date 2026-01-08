<!-- Assign Driver Modal (Reusable) -->
<div id="assign-driver-modal-table" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4" style="overflow-y: auto;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full my-8">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-plus text-blue-600"></i> Assign Driver & Vehicle
            </h2>
            <button type="button" id="close-assign-modal-table" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
            <form id="assign-driver-form-table" method="POST" action="{{ route('trips.store') }}">
                @csrf
                <input type="hidden" name="delivery_request_id" id="modal-request-id" value="">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Driver & Vehicle Selection -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Driver Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-user"></i> Select Driver <span class="text-red-500">*</span>
                            </label>

                            <div id="driver-list-table">
                                <div class="text-center py-8">
                                    <i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i>
                                    <p class="text-gray-500 mt-2">Loading drivers...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-truck"></i> Select Vehicle <span class="text-red-500">*</span>
                            </label>

                            <div id="vehicle-list-table">
                                <div class="text-center py-8">
                                    <i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i>
                                    <p class="text-gray-500 mt-2">Loading vehicles...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Section -->
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="far fa-calendar-alt"></i> Scheduled Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local"
                                name="scheduled_time"
                                id="scheduled-time-table"
                                required
                                min="{{ now()->format('Y-m-d\TH:i') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Route Instructions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-route"></i> Route Instructions
                            </label>
                            <textarea name="route_instructions"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter specific route instructions, toll gates, traffic notes..."></textarea>
                        </div>
                    </div>

                    <!-- Right Column: Request Summary -->
                    <div>
                        <div class="bg-gray-50 rounded-lg p-4 sticky top-0">
                            <h3 class="font-semibold text-gray-800 mb-3 flex items-center text-sm">
                                <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>
                                Request Summary
                            </h3>

                            <div id="request-summary-table" class="space-y-3 text-xs">
                                <p class="text-gray-500">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 mt-6 pt-4 border-t">
                    <button type="button" id="cancel-assign-table" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" id="submit-assign-table" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <i class="fas fa-check"></i> Assign Trip & Notify Driver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentRequestData = null;

async function openAssignModalForRequest(requestId) {
    const modal = document.getElementById('assign-driver-modal-table');
    document.getElementById('modal-request-id').value = requestId;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Load request data
    try {
        const response = await fetch(`/requests/${requestId}`);
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Extract request data from the page (alternative: create API endpoint)
        // For now, load drivers and vehicles
        await loadDriversAndVehiclesTable();
        
    } catch (error) {
        console.error('Error loading request:', error);
    }
}

async function loadDriversAndVehiclesTable() {
    try {
        const driversResponse = await fetch('/api/available-drivers');
        const driversData = await driversResponse.json();
        renderDriversTable(driversData);

        const vehiclesResponse = await fetch('/api/available-vehicles');
        const vehiclesData = await vehiclesResponse.json();
        renderVehiclesTable(vehiclesData);
    } catch (error) {
        console.error('Error loading resources:', error);
    }
}

function renderDriversTable(drivers) {
    const container = document.getElementById('driver-list-table');
    
    if (drivers.length === 0) {
        container.innerHTML = `
            <div class="text-center py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <i class="fas fa-user-slash text-3xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">No available drivers</p>
            </div>
        `;
        return;
    }

    container.innerHTML = `
        <div class="space-y-2 max-h-80 overflow-y-auto pr-2">
            ${drivers.map(driver => `
                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                    <input type="radio" name="driver_id" value="${driver.id}" required class="mr-3 w-4 h-4 text-blue-600">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-gray-800 group-hover:text-blue-600">${driver.name}</p>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                Available
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">
                            <i class="fas fa-phone"></i> ${driver.mobile}
                        </p>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-id-card"></i> ${driver.license_number}
                        </p>
                    </div>
                </label>
            `).join('')}
        </div>
    `;

    container.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            container.querySelectorAll('label').forEach(label => {
                label.classList.remove('border-blue-500', 'bg-blue-50');
            });
            this.closest('label').classList.add('border-blue-500', 'bg-blue-50');
        });
    });
}

function renderVehiclesTable(vehicles) {
    const container = document.getElementById('vehicle-list-table');
    
    if (vehicles.length === 0) {
        container.innerHTML = `
            <div class="text-center py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <i class="fas fa-truck-slash text-3xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">No available vehicles</p>
            </div>
        `;
        return;
    }

    container.innerHTML = `
        <div class="space-y-2 max-h-80 overflow-y-auto pr-2">
            ${vehicles.map(vehicle => `
                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-all group">
                    <input type="radio" name="vehicle_id" value="${vehicle.id}" required class="mr-3 w-4 h-4 text-purple-600">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-gray-800 group-hover:text-purple-600">${vehicle.plate_number}</p>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                Available
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">
                            <i class="fas fa-truck-moving"></i> ${vehicle.vehicle_type}
                        </p>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-trailer"></i> ${vehicle.trailer_type}
                        </p>
                    </div>
                </label>
            `).join('')}
        </div>
    `;

    container.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            container.querySelectorAll('label').forEach(label => {
                label.classList.remove('border-purple-500', 'bg-purple-50');
            });
            this.closest('label').classList.add('border-purple-500', 'bg-purple-50');
        });
    });
}

// Modal controls
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('assign-driver-modal-table');
    const closeBtn = document.getElementById('close-assign-modal-table');
    const cancelBtn = document.getElementById('cancel-assign-table');
    const form = document.getElementById('assign-driver-form-table');

    function hideModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        form.reset();
    }

    closeBtn?.addEventListener('click', hideModal);
    cancelBtn?.addEventListener('click', hideModal);

    modal?.addEventListener('click', (e) => {
        if (e.target === modal) hideModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            hideModal();
        }
    });

    form?.addEventListener('submit', function(e) {
        const driverSelected = document.querySelector('input[name="driver_id"]:checked');
        const vehicleSelected = document.querySelector('input[name="vehicle_id"]:checked');

        if (!driverSelected || !vehicleSelected) {
            e.preventDefault();
            alert('Please select both a driver and a vehicle.');
            return false;
        }

        const submitBtn = document.getElementById('submit-assign-table');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';
    });
});
</script>
