<!-- Assign Driver Modal -->
<div id="assign-driver-modal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4" style="overflow-y: auto;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full my-8">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-plus text-blue-600"></i> Assign Driver & Vehicle
            </h2>
            <button type="button" id="close-assign-driver-modal" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
            <form id="modal-assign-driver-form" method="POST" action="{{ route('trips.store') }}">
                @csrf
                <input type="hidden" name="delivery_request_id" value="{{ $deliveryRequest->id }}">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Driver & Vehicle Selection -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Driver Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-user"></i> Select Driver <span class="text-red-500">*</span>
                            </label>

                            <div id="driver-list-container">
                                <div class="text-center py-8">
                                    <i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i>
                                    <p class="text-gray-500 mt-2">Loading drivers...</p>
                                </div>
                            </div>

                            <div id="driver-error" class="hidden text-red-500 text-xs mt-2"></div>
                        </div>

                        <!-- Vehicle Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-truck"></i> Select Vehicle <span class="text-red-500">*</span>
                            </label>

                            <div id="vehicle-list-container">
                                <div class="text-center py-8">
                                    <i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i>
                                    <p class="text-gray-500 mt-2">Loading vehicles...</p>
                                </div>
                            </div>

                            <div id="vehicle-error" class="hidden text-red-500 text-xs mt-2"></div>
                        </div>

                        <!-- Schedule Section -->
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="far fa-calendar-alt"></i> Scheduled Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local"
                                name="scheduled_time"
                                id="scheduled_time"
                                required
                                value="{{ $deliveryRequest->preferred_schedule->format('Y-m-d\TH:i') }}"
                                min="{{ now()->format('Y-m-d\TH:i') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-xs text-gray-600 mt-2">
                                <i class="fas fa-info-circle"></i>
                                Preferred: {{ $deliveryRequest->preferred_schedule->format('F d, Y h:i A') }}
                            </p>
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

                            <div class="space-y-3 text-xs">
                                <div class="pb-2 border-b">
                                    <p class="text-gray-500 mb-1">ATW Reference</p>
                                    <p class="font-mono font-semibold text-purple-600 bg-white px-2 py-1 rounded">
                                        {{ $deliveryRequest->atw_reference }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-gray-500 mb-1">Client</p>
                                    <p class="font-semibold text-gray-800">{{ $deliveryRequest->client->name }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500 mb-1">Container</p>
                                    <p class="font-semibold text-gray-800">{{ $deliveryRequest->container_size }}</p>
                                    <p class="text-gray-600">{{ $deliveryRequest->container_type }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500 mb-1">Route</p>
                                    <div class="space-y-2">
                                        <div class="flex items-start p-2 bg-green-50 rounded text-xs">
                                            <i class="fas fa-map-marker-alt text-green-500 mr-1 mt-0.5"></i>
                                            <p class="font-medium">{{ Str::limit($deliveryRequest->pickup_location, 40) }}</p>
                                        </div>
                                        <div class="flex justify-center">
                                            <i class="fas fa-arrow-down text-gray-300 text-xs"></i>
                                        </div>
                                        <div class="flex items-start p-2 bg-red-50 rounded text-xs">
                                            <i class="fas fa-flag-checkered text-red-500 mr-1 mt-0.5"></i>
                                            <p class="font-medium">{{ Str::limit($deliveryRequest->delivery_location, 40) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 mt-6 pt-4 border-t">
                    <button type="button" id="cancel-assign-driver" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" id="submit-assign-driver" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <i class="fas fa-check"></i> Assign Trip & Notify Driver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let assignDriverModal = null;
    let driversData = [];
    let vehiclesData = [];

    async function loadDriversAndVehicles() {
        try {
            // Load available drivers
            const driversResponse = await fetch('/api/available-drivers');
            driversData = await driversResponse.json();
            renderDrivers();

            // Load available vehicles
            const vehiclesResponse = await fetch('/api/available-vehicles');
            vehiclesData = await vehiclesResponse.json();
            renderVehicles();
        } catch (error) {
            console.error('Error loading resources:', error);
            document.getElementById('driver-list-container').innerHTML = `
                <div class="text-center py-4 bg-red-50 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    <p class="text-red-600 mt-2">Error loading drivers</p>
                </div>
            `;
            document.getElementById('vehicle-list-container').innerHTML = `
                <div class="text-center py-4 bg-red-50 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    <p class="text-red-600 mt-2">Error loading vehicles</p>
                </div>
            `;
        }
    }

    function renderDrivers() {
        const container = document.getElementById('driver-list-container');
        
        if (driversData.length === 0) {
            container.innerHTML = `
                <div class="text-center py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <i class="fas fa-user-slash text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500 mb-2">No available drivers</p>
                    <a href="/drivers" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-external-link-alt"></i> Manage Drivers
                    </a>
                </div>
            `;
            document.getElementById('submit-assign-driver').disabled = true;
            return;
        }

        container.innerHTML = `
            <div class="space-y-2 max-h-80 overflow-y-auto pr-2">
                ${driversData.map(driver => `
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                        <input type="radio" name="driver_id" value="${driver.id}" required class="mr-3 w-4 h-4 text-blue-600">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-gray-800 group-hover:text-blue-600 truncate">${driver.name}</p>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 flex-shrink-0 ml-2">
                                    <i class="fas fa-circle text-xs"></i> Available
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">
                                <i class="fas fa-phone"></i> ${driver.mobile}
                            </p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-id-card"></i> ${driver.license_number}
                            </p>
                            ${driver.trips_count > 0 ? `
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-check-circle text-green-500"></i> ${driver.trips_count} completed trips
                                </p>
                            ` : ''}
                        </div>
                    </label>
                `).join('')}
            </div>
        `;

        // Add event listeners for radio selection
        container.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                container.querySelectorAll('label').forEach(label => {
                    label.classList.remove('border-blue-500', 'bg-blue-50');
                });
                this.closest('label').classList.add('border-blue-500', 'bg-blue-50');
            });
        });
    }

    function renderVehicles() {
        const container = document.getElementById('vehicle-list-container');
        
        if (vehiclesData.length === 0) {
            container.innerHTML = `
                <div class="text-center py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <i class="fas fa-truck-slash text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500 mb-2">No available vehicles</p>
                    <a href="/vehicles" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-external-link-alt"></i> Manage Vehicles
                    </a>
                </div>
            `;
            document.getElementById('submit-assign-driver').disabled = true;
            return;
        }

        container.innerHTML = `
            <div class="space-y-2 max-h-80 overflow-y-auto pr-2">
                ${vehiclesData.map(vehicle => `
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-all group">
                        <input type="radio" name="vehicle_id" value="${vehicle.id}" required class="mr-3 w-4 h-4 text-purple-600">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-gray-800 group-hover:text-purple-600 truncate">${vehicle.plate_number}</p>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 flex-shrink-0 ml-2">
                                    <i class="fas fa-circle text-xs"></i> Available
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">
                                <i class="fas fa-truck-moving"></i> ${vehicle.vehicle_type}
                            </p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-trailer"></i> ${vehicle.trailer_type}
                            </p>
                            ${vehicle.trips_count > 0 ? `
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-check-circle text-green-500"></i> ${vehicle.trips_count} completed trips
                                </p>
                            ` : ''}
                        </div>
                    </label>
                `).join('')}
            </div>
        `;

        // Add event listeners for radio selection
        container.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                container.querySelectorAll('label').forEach(label => {
                    label.classList.remove('border-purple-500', 'bg-purple-50');
                });
                this.closest('label').classList.add('border-purple-500', 'bg-purple-50');
            });
        });
    }

    function initAssignDriverModal() {
        assignDriverModal = document.getElementById('assign-driver-modal');
        const openButton = document.getElementById('open-assign-driver-modal');
        const closeButton = document.getElementById('close-assign-driver-modal');
        const cancelButton = document.getElementById('cancel-assign-driver');
        const form = document.getElementById('modal-assign-driver-form');

        if (!assignDriverModal || !openButton) return;

        function showModal() {
            assignDriverModal.classList.remove('hidden');
            assignDriverModal.classList.add('flex');
            loadDriversAndVehicles();
        }

        function hideModal() {
            assignDriverModal.classList.add('hidden');
            assignDriverModal.classList.remove('flex');
        }

        openButton.addEventListener('click', showModal);
        closeButton?.addEventListener('click', hideModal);
        cancelButton?.addEventListener('click', hideModal);

        // Close on backdrop click
        assignDriverModal.addEventListener('click', (e) => {
            if (e.target === assignDriverModal) hideModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !assignDriverModal.classList.contains('hidden')) {
                hideModal();
            }
        });

        // Form validation
        form?.addEventListener('submit', function(e) {
            const driverSelected = document.querySelector('input[name="driver_id"]:checked');
            const vehicleSelected = document.querySelector('input[name="vehicle_id"]:checked');

            if (!driverSelected || !vehicleSelected) {
                e.preventDefault();
                alert('Please select both a driver and a vehicle.');
                return false;
            }

            // Show loading state
            const submitBtn = document.getElementById('submit-assign-driver');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAssignDriverModal);
    } else {
        initAssignDriverModal();
    }
</script>

