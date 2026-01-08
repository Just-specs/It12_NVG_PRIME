@extends('layouts.app')

@section('title', 'Request Details')

@section('content')
<div class="container mx-auto px-4 max-w-6xl">
    <div class="mb-6">
        <a href="{{ route('requests.index') }}" class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white bg-[#2563EB] rounded-full hover:bg-blue-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Request Details</h1>
                        <p class="text-gray-600 text-sm mt-1">Created {{ $deliveryRequest->created_at ? $deliveryRequest->created_at->diffForHumans() : 'Just now' }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $deliveryRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $deliveryRequest->status === 'verified' ? 'bg-gradient-to-r from-emerald-400 to-teal-500 text-white shadow-lg font-bold' : '' }}
                        {{ $deliveryRequest->status === 'assigned' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $deliveryRequest->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst($deliveryRequest->status) }}
                    </span>
                </div>

                <!-- Client Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-user text-blue-600"></i> Client Information
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Client Name</p>
                            <p class="font-semibold">{{ $deliveryRequest->client?->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Contact Method</p>
                            <p class="font-semibold">
                                <i class="fas fa-{{ $deliveryRequest->contact_method === 'mobile' ? 'phone' : ($deliveryRequest->contact_method === 'email' ? 'envelope' : 'comments') }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $deliveryRequest->contact_method)) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Delivery Details -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-box text-blue-600"></i> Delivery Details
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">ATW Reference:</div>
                            <div class="flex-1 font-semibold">
                                {{ $deliveryRequest->atw_reference }}

                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">Container:</div>
                            <div class="flex-1 font-semibold">{{ $deliveryRequest->container_size }} - {{ ucfirst($deliveryRequest->container_type) }}</div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">Pickup:</div>
                            <div class="flex-1">
                                <i class="fas fa-map-marker-alt text-green-500"></i>
                                {{ $deliveryRequest->pickup_location }}
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">Delivery:</div>
                            <div class="flex-1">
                                <i class="fas fa-flag-checkered text-red-500"></i>
                                {{ $deliveryRequest->delivery_location }}
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">Schedule:</div>
                            <div class="flex-1 font-semibold">
                                <i class="far fa-calendar-alt"></i>
                                {{ $deliveryRequest->preferred_schedule ? $deliveryRequest->preferred_schedule->format('F d, Y') : 'N/A' }} at {{ $deliveryRequest->preferred_schedule ? $deliveryRequest->preferred_schedule->format('h:i A') : '' }}
                            </div>
                        </div>
                        @if($deliveryRequest->notes)
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">Notes:</div>
                            <div class="flex-1">{{ $deliveryRequest->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Phase 1: Shipping Documentation -->
                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-ship text-blue-600"></i> Shipping Documentation
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        @if($deliveryRequest->shipping_line)
                        <div>
                            <p class="text-sm text-gray-600">Shipping Line</p>
                            <p class="font-semibold">{{ $deliveryRequest->shipping_line }}</p>
                        </div>
                        @endif
                        @if($deliveryRequest->shipper_name)
                        <div>
                            <p class="text-sm text-gray-600">Shipper</p>
                            <p class="font-semibold">{{ $deliveryRequest->shipper_name }}</p>
                        </div>
                        @endif
                        @if($deliveryRequest->booking_number)
                        <div>
                            <p class="text-sm text-gray-600">Booking Number</p>
                            <p class="font-semibold font-mono">{{ $deliveryRequest->booking_number }}</p>
                        </div>
                        @endif
                        @if($deliveryRequest->eir_number)
                        <div>
                            <p class="text-sm text-gray-600">EIR Number</p>
                            <p class="font-semibold font-mono">{{ $deliveryRequest->eir_number }}</p>
                        </div>
                        @endif
                        @if($deliveryRequest->container_number)
                        <div>
                            <p class="text-sm text-gray-600">Container Number</p>
                            <p class="font-semibold font-mono">{{ $deliveryRequest->container_number }}</p>
                        </div>
                        @endif
                        @if($deliveryRequest->seal_number)
                        <div>
                            <p class="text-sm text-gray-600">Seal Number</p>
                            <p class="font-semibold font-mono">{{ $deliveryRequest->seal_number }}</p>
                        </div>
                        @endif
                        @if($deliveryRequest->container_status)
                        <div>
                            <p class="text-sm text-gray-600">Container Status</p>
                            <p class="font-semibold">{{ ucfirst($deliveryRequest->container_status) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- Trip Information (if assigned) -->
                @if($deliveryRequest->trip)
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-truck text-blue-600"></i> Assigned Trip
                    </h3>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Driver</p>
                                <p class="font-semibold">{{ $deliveryRequest->trip?->driver?->name ?? 'Not Assigned' }}</p>
                                <p class="text-xs text-gray-500">{{ $deliveryRequest->trip?->driver?->mobile ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Vehicle</p>
                                <p class="font-semibold">{{ $deliveryRequest->trip?->vehicle?->plate_number ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $deliveryRequest->trip?->vehicle?->trailer_type ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('trips.show', $deliveryRequest->trip) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-external-link-alt"></i> View Trip Details
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>

                @if($deliveryRequest->status === 'pending' && !$deliveryRequest->atw_verified)
                    @if(auth()->user()->canVerifyRequests())
                    {{-- Only Admin and Head Dispatch can verify --}}
                    <form method="POST" action="{{ $deliveryRequest->id ? route('requests.verify', ['request' => $deliveryRequest->id]) : '#' }}" class="mb-3" id="verify-atw-form">
                        @csrf
                        <button type="button" id="open-verify-modal" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-check-circle"></i> Verify ATW
                        </button>
                    </form>

                    <div id="verify-atw-modal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center border border-gray-100">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Verify ATW</h3>
                            <p class="text-sm text-gray-600">Are you sure you want to verify?</p>
                            <div class="mt-6 flex flex-col sm:flex-row sm:justify-center sm:space-x-4 gap-3">
                                <button type="button" id="cancel-verify-atw" class="w-full sm:w-auto px-5 py-2 rounded-full bg-red-500 text-white font-semibold hover:bg-red-600 transition-colors">Cancel</button>
                                <button type="button" id="confirm-verify-atw" class="w-full sm:w-auto px-5 py-2 rounded-full bg-[#2563EB] text-white font-semibold hover:bg-blue-700 transition-colors shadow-[0_0_0_3px_rgba(37,99,235,0.2)]">Confirm</button>
                            </div>
                        </div>
                    </div>
                    @else
                    {{-- Regular dispatchers see a message --}}
                    <div class="mb-3 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Waiting for Admin Verification</strong><br>
                            This request must be verified by an Admin before you can assign a driver.
                        </p>
                    </div>
                    @endif
                @endif

                <a href="{{ route('requests.create') }}" class="mb-3 block w-full px-4 py-2 bg-[#2563EB] text-white rounded-lg text-center hover:bg-[#1D4ED8] transition-colors">
                    <i class="fas fa-plus-circle"></i> New Request
                </a>

                @if(auth()->user()->isAdmin())
                <button type="button" id="open-edit-request-modal" class="mb-3 block w-full px-4 py-2 bg-amber-600 text-white rounded-lg text-center hover:bg-amber-700 transition-colors">
                    <i class="fas fa-edit"></i> Edit Request
                </button>
                @endif

                @if($deliveryRequest->status === 'verified' && !$deliveryRequest->trip)
                <button type="button" id="open-assign-driver-modal" class="block w-full px-4 py-2 bg-[#2563EB] text-white rounded-lg hover:bg-[#1D4ED8] text-center mb-3">
                    <i class="fas fa-user-plus"></i> Assign Driver
                </button>
                @endif

                <!-- Timeline -->
                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-semibold text-gray-800 mb-3">Timeline</h4>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-plus text-blue-600 text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">Request Created</p>
                                <p class="text-xs text-gray-500">{{ $deliveryRequest->created_at ? $deliveryRequest->created_at->format('M d, Y h:i A') : 'N/A' }}</p>
                            </div>
                        </div>

                        @if($deliveryRequest->atw_verified)
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">ATW Verified</p>
                                <p class="text-xs text-gray-500">{{ $deliveryRequest->updated_at ? $deliveryRequest->updated_at->format('M d, Y h:i A') : 'N/A' }}</p>
                            </div>
                        </div>
                        @endif

                        @if($deliveryRequest->trip)
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-check text-purple-600 text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">Driver Assigned</p>
                                <p class="text-xs text-gray-500">{{ $deliveryRequest->trip->created_at ? $deliveryRequest->trip->created_at->format('M d, Y h:i A') : 'N/A' }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Assign Driver Modal -->
@if($deliveryRequest->status === 'verified' && !$deliveryRequest->trip)
    @include('dispatch.requests.partials.assign-driver-modal')
@endif

<!-- Edit Request Modal -->
@if(auth()->user()->isAdmin())
<div id="edit-request-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/40 backdrop-blur-sm" style="backdrop-filter: blur(4px);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden border border-gray-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-edit mr-2"></i>Edit Delivery Request
                </h3>
                <button type="button" id="close-edit-modal" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                <form id="edit-request-form" method="POST" action="{{ $deliveryRequest->id ? route('requests.update', ['request' => $deliveryRequest->id]) : '#' }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Client Selection -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Client <span class="text-red-500">*</span>
                            </label>
                            <select name="client_id" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                                <option value="">Select a client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" @selected(old('client_id', $deliveryRequest->client_id) == $client->id)>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Contact Method -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Contact Method <span class="text-red-500">*</span>
                            </label>
                            <select name="contact_method" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                                <option value="mobile" @selected(old('contact_method', $deliveryRequest->contact_method) == 'mobile')>Mobile</option>
                                <option value="email" @selected(old('contact_method', $deliveryRequest->contact_method) == 'email')>Email</option>
                                <option value="group_chat" @selected(old('contact_method', $deliveryRequest->contact_method) == 'group_chat')>Group Chat</option>
                            </select>
                        </div>

                        <!-- ATW Reference -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                ATW Reference <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="atw_reference" value="{{ old('atw_reference', $deliveryRequest->atw_reference) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                placeholder="Enter ATW reference">
                        </div>

                        <!-- EIR Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                EIR Number
                            </label>
                            <input type="text" name="eir_number" value="{{ old('eir_number', $deliveryRequest->eir_number) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                placeholder="Equipment Interchange Receipt">
                        </div>

                        <!-- Booking Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Booking Number
                            </label>
                            <input type="text" name="booking_number" value="{{ old('booking_number', $deliveryRequest->booking_number) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                placeholder="Shipping booking reference">
                        </div>

                        <!-- Container Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Container Number
                            </label>
                            <input type="text" name="container_number" value="{{ old('container_number', $deliveryRequest->container_number) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                placeholder="e.g., WHSU 816908-2">
                        </div>

                        <!-- Seal Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Seal Number
                            </label>
                            <input type="text" name="seal_number" value="{{ old('seal_number', $deliveryRequest->seal_number) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                placeholder="Security seal number">
                        </div>

                        <!-- Pickup Location -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pickup Location <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pickup_location" value="{{ old('pickup_location', $deliveryRequest->pickup_location) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                placeholder="Enter pickup location">
                        </div>

                        <!-- Delivery Location -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Delivery Location <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="delivery_location" value="{{ old('delivery_location', $deliveryRequest->delivery_location) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                placeholder="Enter delivery location">
                        </div>

                        <!-- Container Size -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Container Size <span class="text-red-500">*</span>
                            </label>
                            <select name="container_size" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                                <option value="20ft" @selected(old('container_size', $deliveryRequest->container_size) == '20ft')>20ft</option>
                                <option value="40ft" @selected(old('container_size', $deliveryRequest->container_size) == '40ft')>40ft</option>
                            </select>
                        </div>

                        <!-- Container Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Container Type <span class="text-red-500">*</span>
                            </label>
                            <select name="container_type" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                                <option value="standard" @selected(old('container_type', $deliveryRequest->container_type) == 'standard')>Standard</option>
                                <option value="refrigerated" @selected(old('container_type', $deliveryRequest->container_type) == 'refrigerated')>Refrigerated</option>
                                <option value="open_top" @selected(old('container_type', $deliveryRequest->container_type) == 'open_top')>Open Top</option>
                            </select>
                        </div>

                        <!-- Shipping Line -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Shipping Line
                            </label>
                            <select name="shipping_line" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                                <option value="">-- Select Shipping Line --</option>
                                <option value="WANHAI" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'WANHAI')>WANHAI</option>
                                <option value="CMA" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'CMA')>CMA</option>
                                <option value="COSCO" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'COSCO')>COSCO</option>
                                <option value="EVERGREEN" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'EVERGREEN')>EVERGREEN</option>
                                <option value="MCC" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'MCC')>MCC</option>
                                <option value="ONE" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'ONE')>ONE</option>
                                <option value="OOCL" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'OOCL')>OOCL</option>
                                <option value="SITC" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'SITC')>SITC</option>
                                <option value="MAERSK" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'MAERSK')>MAERSK</option>
                                <option value="MSC" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'MSC')>MSC</option>
                                <option value="YANGMING" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'YANGMING')>YANGMING</option>
                                <option value="Other" @selected(old('shipping_line', $deliveryRequest->shipping_line) == 'Other')>Other</option>
                            </select>
                        </div>

                        <!-- Shipper Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Shipper Name
                            </label>
                            <input type="text" name="shipper_name" value="{{ old('shipper_name', $deliveryRequest->shipper_name) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                placeholder="Enter shipper name">
                        </div>

                        <!-- Preferred Schedule -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Preferred Schedule <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="preferred_schedule" value="{{ old('preferred_schedule', $deliveryRequest->preferred_schedule ? \Carbon\Carbon::parse($deliveryRequest->preferred_schedule)->format('Y-m-d\TH:i') : '') }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        </div>

                        <!-- Special Instructions -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Special Instructions
                            </label>
                            <textarea name="special_instructions" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                placeholder="Enter any special instructions">{{ old('special_instructions', $deliveryRequest->special_instructions) }}</textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                        <button type="button" id="cancel-edit-modal" class="px-6 py-3 rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition-colors">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 rounded-lg bg-amber-600 text-white font-semibold hover:bg-amber-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Edit Request Modal
    const initEditRequestModal = () => {
        const openButton = document.getElementById('open-edit-request-modal');
        const modal = document.getElementById('edit-request-modal');
        const closeButton = document.getElementById('close-edit-modal');
        const cancelButton = document.getElementById('cancel-edit-modal');

        if (!openButton || !modal) return;

        const showModal = () => {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        const hideModal = () => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        };

        openButton.addEventListener('click', showModal);
        if (closeButton) closeButton.addEventListener('click', hideModal);
        if (cancelButton) cancelButton.addEventListener('click', hideModal);

        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) hideModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEditRequestModal);
    } else {
        initEditRequestModal();
    }
</script>
@endif
@endsection

@push('scripts')
<script>
    const initVerifyAtwModal = () => {
        const openModalButton = document.getElementById('open-verify-modal');
        const modal = document.getElementById('verify-atw-modal');
        const cancelButton = document.getElementById('cancel-verify-atw');
        const confirmButton = document.getElementById('confirm-verify-atw');
        const form = document.getElementById('verify-atw-form');

        if (!openModalButton || !modal || !cancelButton || !confirmButton || !form) {
            return;
        }

        const showModal = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            confirmButton.focus({ preventScroll: true });
        };

        const hideModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            openModalButton.focus({ preventScroll: true });
        };

        openModalButton.addEventListener('click', showModal);
        cancelButton.addEventListener('click', hideModal);

        confirmButton.addEventListener('click', () => {
            hideModal();
            form.submit();
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                hideModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVerifyAtwModal);
    } else {
        initVerifyAtwModal();
    }
</script>
@endpush














