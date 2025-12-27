@extends('layouts.app')

@section('title', 'Assign Driver & Vehicle')

@section('content')
<div class="container mx-auto px-4 max-w-6xl">
    <!-- Error Messages -->
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle text-red-600 text-xl mt-1 mr-3"></i>
            <div>
                <p class="font-semibold text-red-800">Error</p>
                <p class="text-red-700 text-sm mt-1">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle text-red-600 text-xl mt-1 mr-3"></i>
            <div>
                <p class="font-semibold text-red-800">Validation Errors</p>
                <ul class="text-red-700 text-sm mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('requests.show', $deliveryRequest) }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i> Back to Request
        </a>

        <div class="text-sm text-gray-600">
            <i class="fas fa-clock"></i> Manual Assignment
        </div>
    </div>

    <!-- Alert for Resource Availability -->
    @if($drivers->isEmpty() || $vehicles->isEmpty())
    <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-1 mr-3"></i>
            <div>
                <p class="font-semibold text-yellow-800">Limited Resources Available</p>
                @if($drivers->isEmpty())
                <p class="text-yellow-700 text-sm mt-1">â€¢ No available drivers found. Please set drivers to "available" status.</p>
                @endif
                @if($vehicles->isEmpty())
                <p class="text-yellow-700 text-sm mt-1">â€¢ No available vehicles found. Please set vehicles to "available" status.</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Assignment Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-user-plus text-blue-600"></i> Assign Driver & Vehicle
                    </h1>
                    <p class="text-gray-600 text-sm mt-1">
                        Select an available driver and vehicle to complete the trip assignment
                    </p>
                </div>

                <form method="POST" action="{{ route('trips.store') }}" id="assignmentForm">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf-token">
                    <input type="hidden" name="delivery_request_id" value="{{ $deliveryRequest->id }}" id="delivery_request_id_field">
                    <input type="hidden" name="delivery_request_id_backup" value="{{ $deliveryRequest->id }}">

                    <!-- Driver Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-user"></i> Select Driver <span class="text-red-500">*</span>
                        </label>

                        @if($drivers->isEmpty())
                        <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <i class="fas fa-user-slash text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 mb-2">No available drivers</p>
                            <a href="{{ route('drivers.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-external-link-alt"></i> Manage Drivers
                            </a>
                        </div>
                        @else
                        <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                            @foreach($drivers as $driver)
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                                <input type="radio"
                                    name="driver_id"
                                    value="{{ $driver->id }}"
                                    required
                                    class="mr-4 w-5 h-5 text-blue-600">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-gray-800 group-hover:text-blue-600">
                                            {{ $driver->name }}
                                        </p>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-circle text-xs"></i> Available
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-phone"></i> {{ $driver->mobile }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-id-card"></i> License: {{ $driver->license_number }}
                                    </p>
                                    @if($driver->trips_count > 0)
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        {{ $driver->trips_count }} completed trips
                                    </p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @endif

                        @error('driver_id')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-truck"></i> Select Vehicle <span class="text-red-500">*</span>
                        </label>

                        @if($vehicles->isEmpty())
                        <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <i class="fas fa-truck-slash text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 mb-2">No available vehicles</p>
                            <a href="{{ route('vehicles.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-external-link-alt"></i> Manage Vehicles
                            </a>
                        </div>
                        @else
                        <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                            @foreach($vehicles as $vehicle)
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-all group">
                                <input type="radio"
                                    name="vehicle_id"
                                    value="{{ $vehicle->id }}"
                                    required
                                    class="mr-4 w-5 h-5 text-purple-600">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-gray-800 group-hover:text-purple-600">
                                            {{ $vehicle->plate_number }}
                                        </p>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-circle text-xs"></i> Available
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-truck-moving"></i> {{ $vehicle->vehicle_type }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-trailer"></i> Trailer: {{ $vehicle->trailer_type }}
                                    </p>
                                    @if($vehicle->trips_count > 0)
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        {{ $vehicle->trips_count }} completed trips
                                    </p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @endif

                        @error('vehicle_id')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Schedule Section -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="far fa-calendar-alt"></i> Scheduled Time <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local"
                            name="scheduled_time"
                            required
                            value="{{ $deliveryRequest->preferred_schedule->format('Y-m-d\TH:i') }}"
                            min="{{ now()->format('Y-m-d\TH:i') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-600 mt-2">
                            <i class="fas fa-info-circle"></i>
                            Preferred schedule from request: {{ $deliveryRequest->preferred_schedule->format('F d, Y h:i A') }}
                        </p>
                        @error('scheduled_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Route Instructions -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-route"></i> Route Instructions
                        </label>
                        <textarea name="route_instructions"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter specific route instructions, toll gates, traffic notes, or special directions for the driver..."></textarea>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-lightbulb"></i>
                            Tip: Include toll road preferences, traffic-avoiding routes, or specific entry/exit instructions
                    </div>

                    <!-- Financial Information Section -->
                    <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                        <h3 class="font-semibold text-gray-800 mb-4">
                            <i class="fas fa-dollar-sign text-green-600"></i> Financial Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Waybill Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waybill Number
                                </label>
                                <input type="text" name="waybill_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tracking number">
                            </div>

                            <!-- Trip Rate -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Trip Rate (₱)
                                </label>
                                <input type="number" name="trip_rate" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                            </div>

                            <!-- Additional Charge 20ft -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Additional Charge 20ft (₱)
                                </label>
                                <input type="number" name="additional_charge_20ft" step="0.01" min="0" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                            </div>

                            <!-- Additional Charge 50 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Additional Charge 50 (₱)
                                </label>
                                <input type="number" name="additional_charge_50" step="0.01" min="0" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                            </div>

                            <!-- Driver Payroll -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Driver Payroll (₱)
                                </label>
                                <input type="number" name="driver_payroll" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                            </div>

                            <!-- Driver Allowance -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Driver Allowance (₱)
                                </label>
                                <input type="number" name="driver_allowance" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                            </div>

                            <!-- EIR Date/Time -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    EIR Date & Time
                                </label>
                                <input type="datetime-local" name="eir_datetime" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Served By -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Served By (Branch)
                                </label>
                                <select name="served_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Branch</option>
                                    <option value="LOR">LOR</option>
                                    <option value="JUNA">JUNA</option>
                                    <option value="EPOY">EPOY</option>
                                </select>
                            </div>
                            <!-- Official Receipt Number -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Official Receipt Number
                                </label>
                                <input type="text" name="official_receipt_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="OR Number">
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-600 mt-3">
                            <i class="fas fa-info-circle"></i> Financial information can be added now or updated later after trip completion.
                        </p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 pt-4 border-t">
                        <a href="{{ route('requests.show', $deliveryRequest) }}"
                            class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed"
                            {{ ($drivers->isEmpty() || $vehicles->isEmpty()) ? 'disabled' : '' }}>
                            <i class="fas fa-check"></i> Assign Trip
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resource Status Info -->
            <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h4 class="font-semibold text-gray-700 mb-2">
                    <i class="fas fa-info-circle text-blue-500"></i> Resource Availability
                </h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Available Drivers:</p>
                        <p class="font-semibold text-lg {{ $drivers->count() > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $drivers->count() }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">Available Vehicles:</p>
                        <p class="font-semibold text-lg {{ $vehicles->count() > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $vehicles->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Summary Sidebar -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>
                    Request Summary
                </h3>

                <div class="space-y-4 text-sm">
                    <div class="pb-3 border-b">
                        <p class="text-xs text-gray-500 mb-1">ATW Reference</p>
                        <p class="font-mono font-semibold text-purple-600 bg-purple-50 px-3 py-2 rounded">
                            {{ $deliveryRequest->atw_reference }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Client</p>
                        <p class="font-semibold text-gray-800">{{ $deliveryRequest->client->name }}</p>
                        @if($deliveryRequest->client->company)
                        <p class="text-xs text-gray-600">{{ $deliveryRequest->client->company }}</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Container</p>
                        <div class="bg-gray-50 p-3 rounded">
                            <p class="font-semibold text-gray-800">{{ $deliveryRequest->container_size }}</p>
                            <p class="text-xs text-gray-600">{{ $deliveryRequest->container_type }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-2">Route</p>
                        <div class="space-y-2">
                            <div class="flex items-start p-2 bg-green-50 rounded border-l-2 border-green-500">
                                <i class="fas fa-map-marker-alt text-green-500 mt-1 mr-2 text-xs"></i>
                                <div>
                                    <p class="text-xs text-gray-600">Pickup</p>
                                    <p class="font-medium text-xs">{{ $deliveryRequest->pickup_location }}</p>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <i class="fas fa-arrow-down text-gray-300"></i>
                            </div>
                            <div class="flex items-start p-2 bg-red-50 rounded border-l-2 border-red-500">
                                <i class="fas fa-flag-checkered text-red-500 mt-1 mr-2 text-xs"></i>
                                <div>
                                    <p class="text-xs text-gray-600">Delivery</p>
                                    <p class="font-medium text-xs">{{ $deliveryRequest->delivery_location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($deliveryRequest->notes)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Additional Notes</p>
                        <div class="p-2 bg-yellow-50 rounded text-xs border-l-2 border-yellow-500">
                            {{ Str::limit($deliveryRequest->notes, 100) }}
                        </div>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Preferred Schedule</p>
                        <p class="font-semibold text-gray-800">
                            {{ $deliveryRequest->preferred_schedule->format('F d, Y') }}
                        </p>
                        <p class="text-xs text-gray-600">
                            {{ $deliveryRequest->preferred_schedule->format('h:i A') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Status</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            {{ $deliveryRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $deliveryRequest->status === 'verified' ? 'bg-blue-100 text-blue-800' : '' }}">
                            {{ ucfirst($deliveryRequest->status) }}
                        </span>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="mt-6 pt-4 border-t">
                    <a href="{{ route('requests.show', $deliveryRequest) }}"
                        class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-center rounded hover:bg-gray-200 text-sm">
                        <i class="fas fa-eye"></i> View Full Request
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Enhanced debugging for Railway
    console.log('Delivery Request ID:', '{{ $deliveryRequest->id }}');
    console.log('Form action:', '{{ route("trips.store") }}');

    // Ensure delivery_request_id is set on page load
    document.addEventListener('DOMContentLoaded', function() {
        const deliveryRequestIdField = document.getElementById('delivery_request_id_field');
        if (deliveryRequestIdField) {
            console.log('Delivery Request ID Field Value:', deliveryRequestIdField.value);
            if (!deliveryRequestIdField.value || deliveryRequestIdField.value === '') {
                console.error('WARNING: delivery_request_id field is empty!');
                deliveryRequestIdField.value = '{{ $deliveryRequest->id }}';
            }
        }

        // Refresh CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            const csrfInput = document.getElementById('csrf-token');
            if (csrfInput) {
                csrfInput.value = csrfToken;
            }
        }
    });

    // Form validation feedback
    document.getElementById('assignmentForm')?.addEventListener('submit', function(e) {
        const driverSelected = document.querySelector('input[name="driver_id"]:checked');
        const vehicleSelected = document.querySelector('input[name="vehicle_id"]:checked');
        const scheduledTime = document.querySelector('input[name="scheduled_time"]');
        const deliveryRequestIdField = document.getElementById('delivery_request_id_field');
        
        // Log all form data before submission
        console.log('=== Form Submission Debug ===');
        console.log('delivery_request_id:', deliveryRequestIdField?.value);
        console.log('driver_id:', driverSelected?.value);
        console.log('vehicle_id:', vehicleSelected?.value);
        console.log('scheduled_time:', scheduledTime?.value);
        console.log('============================');

        // CRITICAL: Check if delivery_request_id is present
        if (!deliveryRequestIdField || !deliveryRequestIdField.value || deliveryRequestIdField.value === '') {
            e.preventDefault();
            console.error('CRITICAL ERROR: delivery_request_id is missing or empty!');
            console.error('Field exists:', !!deliveryRequestIdField);
            console.error('Field value:', deliveryRequestIdField?.value);
            alert('Error: Delivery request information is missing. Please go back and try again, or contact support.');
            return false;
        }

        // Validate selections
        if (!driverSelected || !vehicleSelected) {
            e.preventDefault();
            alert('Please select both a driver and a vehicle to continue.');
            return false;
        }

        if (!scheduledTime || !scheduledTime.value) {
            e.preventDefault();
            alert('Please select a scheduled time.');
            return false;
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning Trip...';
        }

        // Log final confirmation
        console.log('Form validation passed. Submitting...');
        
        // Allow form submission
        return true;
    });

    // Radio button selection visual feedback
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove previous selections styling
            const name = this.name;
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.closest('label').classList.remove('border-blue-500', 'border-purple-500', 'bg-blue-50', 'bg-purple-50');
            });

            // Add styling to selected
            if (name === 'driver_id') {
                this.closest('label').classList.add('border-blue-500', 'bg-blue-50');
            } else if (name === 'vehicle_id') {
                this.closest('label').classList.add('border-purple-500', 'bg-purple-50');
            }
        });
    });

    // Auto-scroll to error messages if present
    window.addEventListener('load', function() {
        const errorDiv = document.querySelector('.bg-red-50');
        if (errorDiv) {
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
</script>
@endsection




