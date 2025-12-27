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
                <input type="hidden" name="delivery_request_id" id="modal-delivery-request-id" value="" data-debug="true">

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

                        <!-- Phase 1: Financial & Documentation Section -->
                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <h3 class="text-sm font-semibold text-gray-800 mb-4">
                                <i class="fas fa-dollar-sign text-green-600"></i> Financial Information
                            </h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Client Rate -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Client Rate (?)
                                    </label>
                                    <input type="number" name="trip_rate" step="0.01" min="0" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm"
                                        placeholder="0.00">
                                </div>

                                <!-- Driver Payroll -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Driver Payroll (?)
                                    </label>
                                    <input type="number" name="driver_payroll" step="0.01" min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm"
                                        placeholder="0.00">
                                </div>

                                <!-- Driver Allowance -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Driver Allowance (?)
                                    </label>
                                    <input type="number" name="driver_allowance" step="0.01" min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm"
                                        placeholder="0.00">
                                </div>

                                <!-- OR Number -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        OR Number
                                    </label>
                                    <input type="text" name="official_receipt_number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm"
                                        placeholder="OR-000000">
                                </div>
                            </div>
                        </div>

                        <!-- Phase 1: Shipping Documentation Section -->
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h3 class="text-sm font-semibold text-gray-800 mb-4">
                                <i class="fas fa-ship text-blue-600"></i> Shipping Documentation
                            </h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Waybill Number -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Waybill Number
                                    </label>
                                    <input type="text" name="waybill_number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                                        placeholder="WB-000000">
                                </div>

                                <!-- EIR Date/Time -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        EIR Date & Time
                                    </label>
                                    <input type="datetime-local" name="eir_datetime"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                </div>

                                <!-- Served By -->
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Served By (Branch)
                                    </label>
                                    <select name="served_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                        <option value="">Select Branch</option>
                                        <option value="LOR">LOR</option>
                                        <option value="JUNA">JUNA</option>
                                        <option value="EPOY">EPOY</option>
                                    </select>
                                </div>
                            </div>
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

    // Toast notification function
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container') || createToastContainer();
        const toast = document.createElement('div');
        toast.className = `toast-notification bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between transform transition-all duration-300 ease-in-out`;
        
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-3"></i>
                <span>${message}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(toast);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        container.style.maxWidth = '400px';
        document.body.appendChild(container);
        return container;
    }
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
    const requestIdInput = document.getElementById('modal-delivery-request-id');
        console.log('Submit handler - Found element:', requestIdInput);
        console.log('Submit handler - Element exists:', !!requestIdInput);
        console.log('Submit handler - Element.value BEFORE reading:', document.getElementById('modal-delivery-request-id')?.value);
        
    const scheduledTimeInput = document.getElementById('scheduled-time-table');
    const summaryContainer = document.getElementById('request-summary-table');
    
    // Set the delivery request ID

    
    if (requestIdInput) {

    
        requestIdInput.value = requestId;
        requestIdInput.setAttribute('value', requestId);
        requestIdInput.setAttribute('name', 'delivery_request_id'); // Force-set name attribute
        console.log('AFTER SETTING - name attribute:', requestIdInput.getAttribute('name'));
        console.log('AFTER SETTING - name property:', requestIdInput.name); // Set attribute so form.reset() preserves it
        
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
        
        const response = await fetch(`/api/requests/${requestId}`);
        const result = await response.json();
        if (!result.success) { throw new Error('Failed to load request details'); } const request = result.data;
        
        // Extract request data from the HTML
        const atwReference = request.atw_reference;
        const clientName = request.client_name;
        const containerSize = `${request.container_size} - ${request.container_type}`;
        const pickup = request.pickup_location;
        const delivery = request.delivery_location;
        const preferredSchedule = request.preferred_schedule;
        
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
        const requestId = document.getElementById('modal-delivery-request-id')?.value;
        form.reset();
        // Restore the delivery_request_id after reset
        if (requestId && document.getElementById('modal-delivery-request-id')) {
            document.getElementById('modal-delivery-request-id').value = requestId;
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
        e.preventDefault(); // Stop default submission
        
        const driverSelected = document.querySelector('input[name="driver_id"]:checked');
        const vehicleSelected = document.querySelector('input[name="vehicle_id"]:checked');

        // Validate driver and vehicle
        if (!driverSelected || !vehicleSelected) {
            alert('Please select both a driver and a vehicle.');
            return false;
        }

        // Get the request ID from the global variable
        const requestId = window.currentAssignRequestId;
        
        if (!requestId) {
            alert('ERROR: Cannot assign trip - Request ID is missing. Please close the modal and try again.');
            return false;
        }

        console.log('Submitting with delivery_request_id:', requestId);

        // Create FormData and manually add all fields including delivery_request_id
        const formData = new FormData(form);
        
        // Force set delivery_request_id
        formData.set('delivery_request_id', requestId);
        
        console.log('Final FormData contents:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        const submitBtn = document.getElementById('submit-assign-table');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';

        // Submit using fetch
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers.get('content-type'));
            
            // Check if response is OK (200-299)
            if (response.ok) {
                // Try to parse as JSON, but also handle redirect responses
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Non-JSON response (likely a redirect), consider it success
                    return { success: true, redirect: '/trips' };
                }
            } else {
                // Non-OK response, try to get error message
                return response.text().then(text => {
                    console.error('Error response:', text);
                    return { success: false, message: 'Server error occurred' };
                });
            }
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success || data.success === undefined) {
                // Redirect immediately - toast will show on trips page
                window.location.href = data.redirect || '/trips';
            } else {
                // Show error toast
                showToast(data.message || 'Failed to assign trip', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Assign Trip & Notify Driver';
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            // Show warning toast
            showToast('Request completed but response handling failed. Redirecting to trips page...', 'info');
            window.location.href = '/trips';
        });
    });
});
</script>

































