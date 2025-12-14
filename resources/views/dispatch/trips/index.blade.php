@extends('layouts.app')

@section('title', 'Trips')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-route text-blue-600"></i> Trips Management
        </h1>
    </div>

    <!-- Status Filter Tabs -->
    @php
    $tabs = [
        'all' => 'All Trips',
        'scheduled' => 'Scheduled',
        'in-transit' => 'In Transit',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];
    
    // Add 'archived' tab for admin users only
    if (auth()->user()->role === 'admin') {
        $tabs['archived'] = 'Archived';
    }
    @endphp
    <div class="mb-6">
        <nav id="trip-status-tabs" data-current-status="{{ $activeStatus ?? 'all' }}"
            class="flex flex-wrap gap-3">
            @foreach($tabs as $statusValue => $label)
            @php
            $isActive = ($activeStatus ?? 'all') === $statusValue;
            $count = $counts[$statusValue] ?? 0;
            @endphp
            <button type="button"
                class="status-tab group flex items-center justify-between gap-3 rounded-full border-2 px-6 py-2 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-[#1E40AF]/30 {{ $isActive ? 'bg-[#1E40AF] text-white border-[#1E40AF] shadow-lg' : 'bg-white text-[#1E40AF] border-[#1E40AF]/40 hover:border-[#1E40AF] hover:shadow-md' }}"
                data-status="{{ $statusValue }}"
                data-url="{{ $statusValue === 'all' ? route('trips.index') : route('trips.index', ['status' => $statusValue]) }}">
                <span>{{ $label }}</span>
                <span class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full px-2 py-0.5 text-xs font-bold transition {{ $isActive ? 'bg-white text-[#1E40AF]' : 'bg-[#1E40AF]/10 text-[#1E40AF] group-hover:bg-[#1E40AF]/20' }}"
                    data-status-count>{{ $count }}</span>
            </button>
            @endforeach
        </nav>
    </div>
    <!-- Search Section -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Search Trips</h3>
        <div class="flex items-center gap-2">
            <div class="relative" style="width: 300px;">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text"
                    name="search"
                    id="search-input"
                    class="w-full pl-10 pr-4 py-2 border border-blue-500 rounded-md bg-white placeholder-gray-400 focus:outline-none focus:ring-blue-400 focus:border-blue-600 transition duration-150 sm:text-sm"
                    placeholder="Search trips..."
                    value="{{ request('search') }}"
                    autocomplete="off">
            </div>
            <button type="button"
                id="clear-search"
                class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none border border-gray-300 rounded-md {{ !request('search') ? 'hidden' : '' }}">
                Clear
            </button>
        </div>
    </div>


    <!-- Trips Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div id="trips-table-container" data-url="{{ route('trips.index') }}">
            @include('dispatch.trips.partials.table', ['trips' => $trips])
        </div>
    </div>
</div>

<!-- Trip Details Modal -->
<div id="trip-details-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Trip Details</h2>
                <p id="modal-trip-number" class="text-sm text-gray-500">Trip #â€”</p>
            </div>
            <button type="button" class="text-gray-500 hover:text-gray-700" id="modal-close">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
            <form id="modal-start-form" method="POST" class="hidden">
                @csrf
            </form>
            <form id="modal-complete-form" method="POST" class="hidden">
                @csrf
            </form>
            @if(auth()->user()->role === 'admin')
            <form id="modal-cancel-form" method="POST" class="hidden">
                @csrf
            </form>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-info-circle text-blue-600"></i> Trip Information
                            </h3>
                            <span id="modal-status"
                                class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 capitalize">Status</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Client</p>
                                <p id="modal-client-name" class="text-base font-semibold text-gray-800">â€”</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">ATW Reference</p>
                                <p id="modal-atw" class="text-base font-mono font-semibold text-purple-600">â€”</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-users text-blue-600"></i> Assigned Resources
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-gray-500 mb-1">Driver</p>
                                <p id="modal-driver-name" class="font-semibold text-gray-800">â€”</p>
                                <p id="modal-driver-mobile" class="text-xs text-gray-600 mt-1">â€”</p>
                            </div>
                            <div class="p-3 bg-purple-50 rounded-lg">
                                <p class="text-xs text-gray-500 mb-1">Vehicle</p>
                                <p id="modal-vehicle-plate" class="font-semibold text-gray-800">â€”</p>
                                <p id="modal-vehicle-type" class="text-xs text-gray-600 mt-1">â€”</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-box text-blue-600"></i> Delivery Details
                        </h3>
                        <div class="space-y-3 text-sm text-gray-700">
                            <div class="flex flex-wrap gap-2">
                                <span class="w-40 text-gray-500">Container:</span>
                                <span id="modal-container" class="font-semibold">â€”</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="w-40 text-gray-500">Pickup:</span>
                                <span class="font-semibold text-gray-800">
                                    <i class="fas fa-map-marker-alt text-green-500 mr-1"></i>
                                    <span id="modal-pickup">â€”</span>
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="w-40 text-gray-500">Delivery:</span>
                                <span class="font-semibold text-gray-800">
                                    <i class="fas fa-flag-checkered text-red-500 mr-1"></i>
                                    <span id="modal-delivery">â€”</span>
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="w-40 text-gray-500">Scheduled Time:</span>
                                <span id="modal-scheduled" class="font-semibold">â€”</span>
                            </div>
                            <div class="flex flex-wrap gap-2" id="modal-route-row" hidden>
                                <span class="w-40 text-gray-500">Route Instructions:</span>
                                <span id="modal-route" class="font-semibold text-gray-800">â€”</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Timeline</h4>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="text-blue-600">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Trip Created</p>
                                    <p id="modal-created-time" class="text-xs text-gray-500">â€”</p>
                                </div>
                            </div>
                            <div id="modal-start-timeline" class="flex items-start space-x-3 hidden">
                                <div class="text-green-600">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Trip Started</p>
                                    <p id="modal-start-time" class="text-xs text-gray-500">â€”</p>
                                </div>
                            </div>
                            <div id="modal-complete-timeline" class="flex items-start space-x-3 hidden">
                                <div class="text-purple-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Trip Completed</p>
                                    <p id="modal-complete-time" class="text-xs text-gray-500">â€”</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Actions</h4>
                        <div class="space-y-3">
                            @if(auth()->user()->role === 'admin')
                            <button type="button" id="modal-cancel-trip" 
                                class="hidden w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-times-circle mr-2"></i>Cancel Trip
                            </button>
                            @endif
                            <button id="modal-start-trip"
                                type="button"
                                class="hidden w-full px-3 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 transition">
                                <i class="fas fa-play"></i> Start Trip
                            </button>
                            <button id="modal-complete-trip"
                                type="button"
                                class="hidden w-full px-3 py-2 bg-purple-600 text-white rounded-md text-sm font-semibold hover:bg-purple-700 transition">
                                <i class="fas fa-check-circle"></i> Complete Trip
                            </button>
                            <a id="modal-view-full" href="#"
                                class="block w-full px-3 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition text-center">
                                <i class="fas fa-external-link-alt"></i> View Full Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Trip Confirmation Modal -->
<div id="complete-confirm-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 space-y-6">
        <div class="space-y-2 text-center">
            <h3 class="text-2xl font-semibold text-[#1E40AF]">Complete Trip</h3>
            <p class="text-sm text-gray-600">Are you sure you want to complete this trip?</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Completion Notes (optional)
            </label>
            <textarea id="complete-notes"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="Add any notes about the delivery completion..."></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <button id="complete-confirm-no" type="button"
                class="px-6 py-3 rounded-full text-base font-semibold text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition">
                Cancel
            </button>
            <button id="complete-confirm-yes" type="button"
                class="px-6 py-3 rounded-full text-base font-semibold text-white bg-[#1E40AF] hover:bg-[#1A36A0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E40AF] border border-[#1E40AF] transition">
                Confirm
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tripsContainer = document.getElementById('trips-table-container');
        const tabsContainer = document.getElementById('trip-status-tabs');
        const tabButtons = tabsContainer ? Array.from(tabsContainer.querySelectorAll('.status-tab')) : [];
        const TAB_ACTIVE_CLASSES = ['bg-[#1E40AF]', 'text-white', 'border-transparent', 'shadow-xl'];
        const TAB_INACTIVE_CLASSES = ['bg-white', 'text-[#1E40AF]', 'border-[#1E40AF]/40', 'hover:border-[#1E40AF]', 'hover:shadow-md'];
        const BADGE_ACTIVE_CLASSES = ['bg-white', 'text-[#1E40AF]'];
        const BADGE_INACTIVE_CLASSES = ['bg-[#1E40AF]/10', 'text-[#1E40AF]', 'group-hover:bg-[#1E40AF]/20'];

        const applyClasses = (element, add = [], remove = []) => {
            if (!element) return;
            remove.forEach(cls => element.classList.remove(cls));
            add.forEach(cls => element.classList.add(cls));
        };

        const setTripsLoading = (isLoading) => {
            if (!tripsContainer) return;
            tripsContainer.classList.toggle('opacity-50', isLoading);
            tripsContainer.classList.toggle('pointer-events-none', isLoading);
        };

        const bindTripButtonHandlers = (root = document) => {
            root.querySelectorAll('.view-trip-btn').forEach(button => {
                button.addEventListener('click', handleViewClick);
            });

            root.querySelectorAll('.start-trip-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    if (confirm('Start this trip?')) {
                        event.currentTarget.closest('form').submit();
                    }
                });
            });

            root.querySelectorAll('.complete-trip-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    console.log('Complete trip button clicked!');
                    const form = event.currentTarget.closest('form');
                    console.log('Form found:', form);
                    console.log('Form action:', form ? form.action : 'No form');
                    showCompleteConfirm(form);
                });
            });
        };

        const updateActiveTab = (status) => {
            if (!tabsContainer) return;

            tabButtons.forEach(tab => {
                const isActive = tab.dataset.status === status;
                applyClasses(
                    tab,
                    isActive ? TAB_ACTIVE_CLASSES : TAB_INACTIVE_CLASSES,
                    isActive ? TAB_INACTIVE_CLASSES : TAB_ACTIVE_CLASSES
                );

                const badge = tab.querySelector('[data-status-count]');
                applyClasses(
                    badge,
                    isActive ? BADGE_ACTIVE_CLASSES : BADGE_INACTIVE_CLASSES,
                    isActive ? BADGE_INACTIVE_CLASSES : BADGE_ACTIVE_CLASSES
                );
            });

            tabsContainer.dataset.currentStatus = status;
        };

        const updateCounts = (counts = {}) => {
            tabButtons.forEach(tab => {
                const status = tab.dataset.status;
                const badge = tab.querySelector('[data-status-count]');
                if (badge && Object.prototype.hasOwnProperty.call(counts, status)) {
                    badge.textContent = counts[status];
                }
            });
        };

        const handleTripsPagination = async (url, {
            updateTabFromResponse = false
        } = {}) => {
            if (!tripsContainer) return;

            try {
                setTripsLoading(true);
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Failed to load trips page');

                const data = await response.json();

                if (data.html) {
                    tripsContainer.innerHTML = data.html;
                    bindTripButtonHandlers(tripsContainer);
                }

                if (updateTabFromResponse && typeof data.status === 'string') {
                    updateActiveTab(data.status);
                }

                if (data.counts) {
                    updateCounts(data.counts);
                }
            } catch (error) {
                console.error(error);
            } finally {
                setTripsLoading(false);
            }
        };

        const modal = document.getElementById('trip-details-modal');
        const closeButton = document.getElementById('modal-close');
        const modalTripNumber = document.getElementById('modal-trip-number');
        const modalClientName = document.getElementById('modal-client-name');
        const modalAtw = document.getElementById('modal-atw');
        const modalDriverName = document.getElementById('modal-driver-name');
        const modalDriverMobile = document.getElementById('modal-driver-mobile');
        const modalVehiclePlate = document.getElementById('modal-vehicle-plate');
        const modalVehicleType = document.getElementById('modal-vehicle-type');
        const modalContainer = document.getElementById('modal-container');
        const modalPickup = document.getElementById('modal-pickup');
        const modalDelivery = document.getElementById('modal-delivery');
        const modalScheduled = document.getElementById('modal-scheduled');
        const modalRoute = document.getElementById('modal-route');
        const modalRouteRow = document.getElementById('modal-route-row');
        const modalStatus = document.getElementById('modal-status');
        const modalCreatedTime = document.getElementById('modal-created-time');
        const modalStartTimeline = document.getElementById('modal-start-timeline');
        const modalStartTime = document.getElementById('modal-start-time');
        const modalCompleteTimeline = document.getElementById('modal-complete-timeline');
        const modalCompleteTime = document.getElementById('modal-complete-time');
        const modalStartTrip = document.getElementById('modal-start-trip');
        const modalCompleteTrip = document.getElementById('modal-complete-trip');
        const modalCancelTrip = document.getElementById('modal-cancel-trip');
        const modalCancelForm = document.getElementById('modal-cancel-form');
        const modalViewFull = document.getElementById('modal-view-full');
        const modalStartForm = document.getElementById('modal-start-form');
        const modalCompleteForm = document.getElementById('modal-complete-form');
        const completeConfirmModal = document.getElementById('complete-confirm-modal');
        const completeConfirmYes = document.getElementById('complete-confirm-yes');
        const completeConfirmNo = document.getElementById('complete-confirm-no');
        const completeNotes = document.getElementById('complete-notes');
        let pendingCompleteForm = null;

        if (!modal || !closeButton) return;

        const statusClasses = {
            scheduled: ['bg-gray-100', 'text-gray-800'],
            'in-transit': ['bg-blue-100', 'text-blue-800'],
            completed: ['bg-green-100', 'text-green-800'],
            cancelled: ['bg-red-100', 'text-red-800']
        };

        const showCompleteConfirm = (form) => {
            if (!form) {
                console.error('Complete trip form not found');
                return;
            }

            if (!completeConfirmModal) {
                // If modal doesn't exist, submit directly
                form.submit();
                return;
            }

            pendingCompleteForm = form;
            if (completeNotes) {
                completeNotes.value = '';
            }
            completeConfirmModal.classList.remove('hidden');
            completeConfirmModal.classList.add('flex');
            if (completeConfirmYes) {
                completeConfirmYes.focus({
                    preventScroll: true
                });
            }
        };

        const hideCompleteConfirm = () => {
            if (completeConfirmModal) {
                completeConfirmModal.classList.add('hidden');
                completeConfirmModal.classList.remove('flex');
            }
            pendingCompleteForm = null;
        };

        const toggleModal = (show) => {
            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                hideCompleteConfirm();
            }
        };

        const updateStatusBadge = (statusElement, status) => {
            statusElement.className = 'px-3 py-1 rounded-full text-xs font-semibold capitalize';
            const classes = statusClasses[status] ?? ['bg-gray-100', 'text-gray-800'];
            statusElement.classList.add(...classes);
            statusElement.textContent = status.replace('-', ' ');
        };

        const handleViewClick = (event) => {
            const button = event.currentTarget;

            modalTripNumber.textContent = `Trip #${button.dataset.tripId}`;
            modalClientName.textContent = button.dataset.clientName;
            modalAtw.textContent = button.dataset.atwReference;
            modalDriverName.textContent = button.dataset.driverName;
            modalDriverMobile.textContent = button.dataset.driverMobile;
            modalVehiclePlate.textContent = button.dataset.vehiclePlate;
            modalVehicleType.textContent = button.dataset.vehicleType;
            modalContainer.textContent = `${button.dataset.containerSize} - ${button.dataset.containerType}`;
            modalPickup.textContent = button.dataset.pickup;
            modalDelivery.textContent = button.dataset.delivery;
            modalScheduled.textContent = `${button.dataset.scheduledDate} at ${button.dataset.scheduledTime}`;

            if (button.dataset.routeInstructions) {
                modalRouteRow.hidden = false;
                modalRoute.textContent = button.dataset.routeInstructions;
            } else {
                modalRouteRow.hidden = true;
                modalRoute.textContent = '';
            }

            updateStatusBadge(modalStatus, button.dataset.status);
            modalCreatedTime.textContent = button.dataset.createdAt;

            if (button.dataset.startTime) {
                modalStartTimeline.classList.remove('hidden');
                modalStartTime.textContent = button.dataset.startTime;
            } else {
                modalStartTimeline.classList.add('hidden');
            }

            if (button.dataset.completeTime) {
                modalCompleteTimeline.classList.remove('hidden');
                modalCompleteTime.textContent = button.dataset.completeTime;
            } else {
                modalCompleteTimeline.classList.add('hidden');
            }

            // Hide both buttons by default
            modalStartTrip.classList.add('hidden');
            modalCompleteTrip.classList.add('hidden');

            // Show Start Trip button only for scheduled trips
            if (button.dataset.status === 'scheduled' && button.dataset.startUrl) {
                modalStartTrip.classList.remove('hidden');
                modalStartForm.setAttribute('action', button.dataset.startUrl);
            }

            // Show Complete Trip button only for in-transit trips
            if (button.dataset.status === 'in-transit' && button.dataset.completeUrl) {
                modalCompleteTrip.classList.remove('hidden');
                modalCompleteForm.setAttribute('action', button.dataset.completeUrl);
            }

            // Show Cancel Trip button for admin only (scheduled or in-transit)
            if (modalCancelTrip && modalCancelForm && button.dataset.cancelUrl && 
                (button.dataset.status === 'scheduled' || button.dataset.status === 'in-transit')) {
                modalCancelTrip.classList.remove('hidden');
                modalCancelForm.setAttribute('action', button.dataset.cancelUrl);
            } else if (modalCancelTrip) {
                modalCancelTrip.classList.add('hidden');
            }

            // For completed trips, both buttons remain hidden (no action needed)

            modalViewFull.href = button.dataset.viewUrl;

            toggleModal(true);
        };

        // Handle pagination clicks in table container
        if (tripsContainer) {
            tripsContainer.addEventListener('click', (event) => {
                const paginationLink = event.target.closest('a[data-pagination="trips"]');
                if (!paginationLink) return;

                event.preventDefault();
                handleTripsPagination(paginationLink.href);
            });

            bindTripButtonHandlers(tripsContainer);
        }


        const handleTabClick = async (event) => {
            const {
                status,
                url
            } = event.currentTarget.dataset;
            if (!url) return;
            if (tabsContainer && tabsContainer.dataset.currentStatus === status) return;

            event.preventDefault();
            updateActiveTab(status);
            await handleTripsPagination(url, {
                updateTabFromResponse: true
            });
        };

        if (tabsContainer) {
            tabButtons.forEach(tab => {
                tab.addEventListener('click', handleTabClick);
            });
        }

        closeButton.addEventListener('click', () => toggleModal(false));
        modal.addEventListener('click', (event) => {
            if (event.target === modal) toggleModal(false);
        });

        if (modalStartTrip && modalStartForm) {
            modalStartTrip.addEventListener('click', () => {
                if (confirm('Start this trip?')) {
                    modalStartForm.submit();
                }
            });
        }

        if (modalCompleteTrip && modalCompleteForm) {
            modalCompleteTrip.addEventListener('click', () => {
                showCompleteConfirm(modalCompleteForm);
            });
        }

        if (completeConfirmYes) {
            completeConfirmYes.addEventListener('click', (e) => {
                e.preventDefault();
                if (!pendingCompleteForm) {
                    console.error('No pending form to submit');
                    hideCompleteConfirm();
                    return;
                }

                const notesValue = completeNotes ? completeNotes.value.trim() : '';

                // Remove existing update_message input if any
                const existingInput = pendingCompleteForm.querySelector('input[name="update_message"]');
                if (existingInput) {
                    existingInput.remove();
                }

                if (notesValue) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'update_message';
                    input.value = notesValue;
                    pendingCompleteForm.appendChild(input);
                }

                // Save form reference before hiding (because hideCompleteConfirm sets it to null)
                const formToSubmit = pendingCompleteForm;
                
                hideCompleteConfirm();

                // Submit the form - use setTimeout to ensure modal is closed first
                setTimeout(() => {
                    if (formToSubmit) {
                        // Verify form has action and method
                        if (!formToSubmit.action) {
                            console.error('Form has no action attribute');
                            return;
                        }
                        console.log('Submitting form with action:', formToSubmit.action);
                        formToSubmit.submit();
                    }
                }, 100);
            });
        }

        if (completeConfirmNo) {
            completeConfirmNo.addEventListener('click', hideCompleteConfirm);
        }

        if (completeConfirmModal) {
            completeConfirmModal.addEventListener('click', (event) => {
                if (event.target === completeConfirmModal) hideCompleteConfirm();
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;

            if (completeConfirmModal && completeConfirmModal.classList.contains('flex')) {
                hideCompleteConfirm();
                return;
            }

            if (!modal.classList.contains('hidden')) {
                toggleModal(false);
            }
        });
        // Search functionality
        const searchInput = document.getElementById('search-input');
        const clearSearchBtn = document.getElementById('clear-search');

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Toggle clear button visibility
        function toggleClearButton(value) {
            if (clearSearchBtn) {
                clearSearchBtn.classList.toggle('hidden', !value);
            }
        }

        // Fetch trips with search
        function fetchTrips(search) {
            const currentStatus = tabsContainer ? tabsContainer.dataset.currentStatus : 'all';
            let url = tripsContainer.dataset.url || '{{ route("trips.index") }}';
            const params = new URLSearchParams();

            if (currentStatus && currentStatus !== 'all') {
                params.append('status', currentStatus);
            }
            if (search) {
                params.append('search', search);
            }

            const fullUrl = params.toString() ? `${url}?${params.toString()}` : url;
            handleTripsPagination(fullUrl, {
                updateTabFromResponse: true
            });

            // Update URL without page reload
            const newUrl = new URL(window.location.href);
            if (search) {
                newUrl.searchParams.set('search', search);
            } else {
                newUrl.searchParams.delete('search');
            }
            if (currentStatus && currentStatus !== 'all') {
                newUrl.searchParams.set('status', currentStatus);
            }
            window.history.pushState({}, '', newUrl);
        }

        // Handle search input with debounce
        if (searchInput) {
            searchInput.addEventListener('input', debounce(function(e) {
                const searchValue = e.target.value.trim();
                if (searchValue.length === 0 || searchValue.length >= 2) {
                    fetchTrips(searchValue);
                }
                toggleClearButton(searchValue);
            }, 300));
        }

        // Handle clear search
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                fetchTrips('');
                toggleClearButton('');
                searchInput.focus();
            });
        }

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search') || '';
            if (searchInput) {
                searchInput.value = searchParam;
                toggleClearButton(searchParam);
                fetchTrips(searchParam);
            }
        });
    });
</script>
@endpush

@endsection




