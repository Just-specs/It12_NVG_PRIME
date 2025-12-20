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
                <input type="hidden" name="delivery_request_id" id="modal-request-id" value="" data-debug="true">

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
    console.log('Opening assign modal for request:', requestId);
    console.log('Request ID type:', typeof requestId);
    console.log('Request ID is null/undefined:', requestId == null);
    
    if (!requestId || requestId === 'null' || requestId === 'undefined') {
        console.error('CRITICAL: Invalid request ID passed to modal:', requestId);
        alert('Error: Invalid request ID. Cannot assign driver.');
        return;
    }
    
    const modal = document.getElementById('assign-driver-modal-table');
    const requestIdInput = document.getElementById('modal-request-id');
        console.log('Submit handler - Found element:', requestIdInput);
        console.log('Submit handler - Element exists:', !!requestIdInput);
        console.log('Submit handler - Element.value BEFORE reading:', document.getElementById('modal-request-id')?.value);
        
    const scheduledTimeInput = document.getElementById('scheduled-time-table');
    const summaryContainer = document.getElementById('request-summary-table');
    
    // Set the delivery request ID

    
    if (requestIdInput) {

    
        requestIdInput.value = requestId;
        requestIdInput.setAttribute('value', requestId); // Set attribute so form.reset() preserves it
        
        window.currentAssignRequestId = requestId; // Store globally
        console.log('Stored in window.currentAssignRequestId:', window.currentAssignRequestId);

    
        console.log('Set delivery_request_id to:', requestId);

    
        console.log('Hidden field value after setting:', requestIdInput.value);

    
        console.log('Hidden field ID:', requestIdInput.id);

    
        console.log('Hidden field name:', requestIdInput.name);
    } else {
        console.error('modal-request-id input not found!');
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Load request data
    try {
        summaryContainer.innerHTML = '<p class="text-gray-500">Loading request details...</p>';
        
        const response = await fetch(`/requests/${requestId}`);
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Extract request data from the HTML
        const atwReference = doc.querySelector('[data-atw-reference]')?.textContent.trim() || 'N/A';
        const clientName = doc.querySelector('[data-client-name]')?.textContent.trim() || 'N/A';
        const containerSize = doc.querySelector('[data-container-size]')?.textContent.trim() || 'N/A';
        const pickup = doc.querySelector('[data-pickup]')?.textContent.trim() || 'N/A';
        const delivery = doc.querySelector('[data-delivery]')?.textContent.trim() || 'N/A';
        const preferredSchedule = doc.querySelector('[data-preferred-schedule]')?.getAttribute('data-preferred-schedule');
        
        // Populate summary
        summaryContainer.innerHTML = `
            <div class="pb-2 border-b">
                <p class="text-gray-500 mb-1">ATW Reference</p>
                <p class="font-mono font-semibold text-purple-600 bg-white px-2 py-1 rounded">
                    ${atwReference}
                </p>
            </div>
            <div>
                <p class="text-gray-500 mb-1">Client</p>
                <p class="font-semibold text-gray-800">${clientName}</p>
            </div>
            <div>
                <p class="text-gray-500 mb-1">Container</p>
                <p class="font-semibold text-gray-800">${containerSize}</p>
            </div>
            <div>
                <p class="text-gray-500 mb-1">Route</p>
                <div class="space-y-2">
                    <div class="flex items-start p-2 bg-green-50 rounded text-xs">
                        <i class="fas fa-map-marker-alt text-green-500 mr-1 mt-0.5"></i>
                        <p class="font-medium">${pickup}</p>
                    </div>
                    <div class="flex justify-center">
                        <i class="fas fa-arrow-down text-gray-300 text-xs"></i>
                    </div>
                    <div class="flex items-start p-2 bg-red-50 rounded text-xs">
                        <i class="fas fa-flag-checkered text-red-500 mr-1 mt-0.5"></i>
                        <p class="font-medium">${delivery}</p>
                    </div>
                </div>
            </div>
        `;
        
        // Set scheduled time to preferred schedule or current time + 1 hour
        if (preferredSchedule) {
            scheduledTimeInput.value = preferredSchedule;
        } else {
            const now = new Date();
            now.setHours(now.getHours() + 1);
            scheduledTimeInput.value = now.toISOString().slice(0, 16);
        }
        
        // Load drivers and vehicles
        await loadDriversAndVehiclesTable();
        
    } catch (error) {
        console.error('Error loading request:', error);
        summaryContainer.innerHTML = '<p class="text-red-500">Error loading request details</p>';
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
    
    
    console.log('Modal elements:', { modal, closeBtn, cancelBtn, form });
    

    function hideModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        
        // Reset form but keep delivery_request_id for debugging
        const requestId = document.getElementById('modal-request-id')?.value;
        form.reset();
        // Restore the delivery_request_id after reset
        if (requestId && document.getElementById('modal-request-id')) {
            document.getElementById('modal-request-id').value = requestId;
            console.log('Restored delivery_request_id after form reset:', requestId);
        }
        console.log('Modal closed. Previous delivery_request_id was:', requestId);
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
        console.log('=== FORM SUBMIT HANDLER CALLED ===');
        
        
        const driverSelected = document.querySelector('input[name="driver_id"]:checked');
        const vehicleSelected = document.querySelector('input[name="vehicle_id"]:checked');
        const requestIdInput = document.getElementById('modal-request-id');
        console.log('Submit handler - Found element:', requestIdInput);
        console.log('Submit handler - Element exists:', !!requestIdInput);
        console.log('Submit handler - Element.value BEFORE reading:', document.getElementById('modal-request-id')?.value);
        
        
        // Log all form data before submission

        
        console.log('===== FORM SUBMISSION DEBUG =====');

        
        console.log('requestIdInput element:', requestIdInput);

        
        console.log('requestIdInput.value:', requestIdInput?.value);

        
        console.log('All form data:', new FormData(form));

        
        for (let pair of new FormData(form).entries()) {

        
            console.log(pair[0] + ': ' + pair[1]);

        
        }

        
        console.log('Form submission attempt:', {

        
            delivery_request_id: requestIdInput?.value,
            driver_id: driverSelected?.value,
            vehicle_id: vehicleSelected?.value,
            scheduled_time: document.getElementById('scheduled-time-table')?.value
        });

        // CRITICAL FIX: Restore the request ID from global storage if it's missing
        if ((!requestIdInput || !requestIdInput.value || requestIdInput.value === "" || requestIdInput.value === "null") && window.currentAssignRequestId) {
            console.warn('WARNING: delivery_request_id was empty, restoring from window.currentAssignRequestId:', window.currentAssignRequestId);
            requestIdInput.value = window.currentAssignRequestId;
        }

        // Check if delivery_request_id is set
        if (!requestIdInput || !requestIdInput.value || requestIdInput.value === "" || requestIdInput.value === "null") {
            e.preventDefault();
            console.error('CRITICAL: delivery_request_id is missing or invalid!');
            console.error('requestIdInput:', requestIdInput);
            console.error('requestIdInput.value:', requestIdInput?.value);
            alert('ERROR: Cannot assign trip - Request ID is missing. Please close the modal and try again.');
            return false;
        }

        if (!driverSelected || !vehicleSelected) {
            e.preventDefault();
            alert('Please select both a driver and a vehicle.');
            return false;
        }

        
        
        // FINAL CHECK - Show alert with the delivery_request_id value
        
        
        const submitBtn = document.getElementById('submit-assign-table');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';
    });
});
</script>


















