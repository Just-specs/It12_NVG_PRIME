@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-truck text-blue-600"></i> Vehicles Management
        </h1>
        <a href="{{ route('vehicles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus"></i> Add Vehicle
        </a>
    </div>

    <!-- Status Filter Tabs -->
    @php
    $tabs = [
        'all' => 'All Vehicles',
        'available' => 'Available',
        'in-use' => 'In Use',
        'maintenance' => 'Maintenance',
    ];
    @endphp
    <div class="mb-6">
        <nav id="vehicle-status-tabs" data-current-status="{{ $activeStatus ?? 'all' }}"
            class="flex flex-wrap gap-3">
            @foreach($tabs as $statusValue => $label)
            @php
            $isActive = ($activeStatus ?? 'all') === $statusValue;
            $count = $counts[$statusValue] ?? 0;
            @endphp
            <button type="button"
                class="status-tab group flex items-center justify-between gap-3 rounded-full border-2 px-6 py-2 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-[#1E40AF]/30 {{ $isActive ? 'bg-[#1E40AF] text-white border-[#1E40AF] shadow-lg' : 'bg-white text-[#1E40AF] border-[#1E40AF]/40 hover:border-[#1E40AF] hover:shadow-md' }}"
                data-status="{{ $statusValue }}"
                data-url="{{ $statusValue === 'all' ? route('vehicles.index') : route('vehicles.index', ['status' => $statusValue]) }}">
                <span>{{ $label }}</span>
                <span class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full px-2 py-0.5 text-xs font-bold transition {{ $isActive ? 'bg-white text-[#1E40AF]' : 'bg-[#1E40AF]/10 text-[#1E40AF] group-hover:bg-[#1E40AF]/20' }}"
                    data-status-count>{{ $count }}</span>
            </button>
            @endforeach
        </nav>
    </div>

    <!-- Search Section -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Search Vehicles</h3>
        <div class="flex items-center gap-2">
            <div class="relative" style="width: 300px;">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                       name="search" 
                       id="search-input" 
                       class="w-full pl-10 pr-4 py-2 border border-blue-500 rounded-md bg-white placeholder-gray-400 focus:outline-none focus:ring-blue-400 focus:border-blue-600 transition duration-150 sm:text-sm" 
                       placeholder="Search..." 
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

    <!-- Vehicles Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div id="vehicles-table-container" data-url="{{ route('vehicles.index') }}">
            @include('dispatch.vehicles.partials.table', ['vehicles' => $vehicles])
        </div>
    </div>
</div>

<!-- Vehicle Details Modal -->
<div id="vehicle-details-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Vehicle Details</h2>
                <p id="modal-vehicle-id" class="text-sm text-gray-500">Vehicle #—</p>
            </div>
            <button type="button" class="text-gray-500 hover:text-gray-700" id="modal-close">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-6">
            <div class="flex items-center space-x-4">
                <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-truck text-blue-600 text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h3 id="modal-plate-number" class="text-2xl font-bold text-gray-800">—</h3>
                    <span id="modal-status"
                        class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 capitalize">Status</span>
                </div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Vehicle Type</p>
                        <p id="modal-vehicle-type" class="text-base font-semibold text-gray-800">—</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Trailer Type</p>
                        <p id="modal-trailer-type" class="text-base font-semibold text-gray-800">—</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Trips</p>
                        <p id="modal-trips-count" class="text-2xl font-bold text-blue-600">—</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Added Date</p>
                        <p id="modal-created" class="text-base font-semibold text-gray-800">—</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-4 flex gap-3">
                <a id="modal-view-full" href="#"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition text-center">
                    <i class="fas fa-external-link-alt"></i> View Full Details
                </a>
                <form id="modal-maintenance-form" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" id="modal-maintenance-btn"
                        class="w-full px-4 py-2 bg-orange-600 text-white rounded-md text-sm font-semibold hover:bg-orange-700 transition">
                        <i class="fas fa-tools"></i> Set Maintenance
                    </button>
                </form>
                <form id="modal-available-form" method="POST" class="flex-1 hidden">
                    @csrf
                    <button type="submit" id="modal-available-btn"
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 transition">
                        <i class="fas fa-check"></i> Set Available
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const vehiclesContainer = document.getElementById('vehicles-table-container');
        const tabsContainer = document.getElementById('vehicle-status-tabs');
        const tabButtons = tabsContainer ? Array.from(tabsContainer.querySelectorAll('.status-tab')) : [];
        const modal = document.getElementById('vehicle-details-modal');
        const closeButton = document.getElementById('modal-close');
        const modalVehicleId = document.getElementById('modal-vehicle-id');
        const modalPlateNumber = document.getElementById('modal-plate-number');
        const modalVehicleType = document.getElementById('modal-vehicle-type');
        const modalTrailerType = document.getElementById('modal-trailer-type');
        const modalStatus = document.getElementById('modal-status');
        const modalTripsCount = document.getElementById('modal-trips-count');
        const modalCreated = document.getElementById('modal-created');
        const modalViewFull = document.getElementById('modal-view-full');
        const modalMaintenanceForm = document.getElementById('modal-maintenance-form');
        const modalAvailableForm = document.getElementById('modal-available-form');

        const TAB_ACTIVE_CLASSES = ['bg-[#1E40AF]', 'text-white', 'border-[#1E40AF]', 'shadow-lg'];
        const TAB_INACTIVE_CLASSES = ['bg-white', 'text-[#1E40AF]', 'border-[#1E40AF]/40', 'hover:border-[#1E40AF]', 'hover:shadow-md'];
        const BADGE_ACTIVE_CLASSES = ['bg-white', 'text-[#1E40AF]'];
        const BADGE_INACTIVE_CLASSES = ['bg-[#1E40AF]/10', 'text-[#1E40AF]', 'group-hover:bg-[#1E40AF]/20'];

        const statusClasses = {
            available: ['bg-green-100', 'text-green-800'],
            'in-use': ['bg-yellow-100', 'text-yellow-800'],
            maintenance: ['bg-red-100', 'text-red-800']
        };

        const applyClasses = (element, add = [], remove = []) => {
            if (!element) return;
            remove.forEach(cls => element.classList.remove(cls));
            add.forEach(cls => element.classList.add(cls));
        };

        const updateActiveTab = (status) => {
            if (!tabsContainer) return;
            tabButtons.forEach(tab => {
                const isActive = tab.dataset.status === status;
                applyClasses(tab, isActive ? TAB_ACTIVE_CLASSES : TAB_INACTIVE_CLASSES,
                    isActive ? TAB_INACTIVE_CLASSES : TAB_ACTIVE_CLASSES);
                const badge = tab.querySelector('[data-status-count]');
                applyClasses(badge, isActive ? BADGE_ACTIVE_CLASSES : BADGE_INACTIVE_CLASSES,
                    isActive ? BADGE_INACTIVE_CLASSES : BADGE_ACTIVE_CLASSES);
            });
            tabsContainer.dataset.currentStatus = status;
        };

        const handleVehiclesPagination = async (url, options = {}) => {
            if (!vehiclesContainer) return;
            vehiclesContainer.classList.add('opacity-50', 'pointer-events-none');

            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                vehiclesContainer.innerHTML = data.html;
                bindVehicleButtonHandlers(vehiclesContainer);

                if (options.updateTabFromResponse && data.counts) {
                    Object.entries(data.counts).forEach(([status, count]) => {
                        const tab = tabButtons.find(t => t.dataset.status === status);
                        if (tab) {
                            const badge = tab.querySelector('[data-status-count]');
                            if (badge) badge.textContent = count;
                        }
                    });
                }
            } catch (error) {
                console.error('Pagination error:', error);
            } finally {
                vehiclesContainer.classList.remove('opacity-50', 'pointer-events-none');
            }
        };

        const bindVehicleButtonHandlers = (root = document) => {
            root.querySelectorAll('.view-vehicle-btn').forEach(button => {
                button.addEventListener('click', handleViewClick);
            });
        };

        const toggleModal = (show) => {
            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        };

        const updateStatusBadge = (statusElement, status) => {
            statusElement.className = 'inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold capitalize';
            const classes = statusClasses[status] ?? ['bg-gray-100', 'text-gray-800'];
            statusElement.classList.add(...classes);
            statusElement.textContent = status.replace('-', ' ');
        };

        const handleViewClick = (event) => {
            const button = event.currentTarget;

            modalVehicleId.textContent = `Vehicle #${button.dataset.vehicleId}`;
            modalPlateNumber.textContent = button.dataset.plateNumber;
            modalVehicleType.textContent = button.dataset.vehicleType;
            modalTrailerType.textContent = button.dataset.trailerType;
            modalTripsCount.textContent = button.dataset.tripsCount;
            modalCreated.textContent = button.dataset.createdAt;
            updateStatusBadge(modalStatus, button.dataset.status);
            modalViewFull.href = button.dataset.viewUrl;

            const status = button.dataset.status;
            if (status === 'available') {
                modalMaintenanceForm.setAttribute('action', button.dataset.maintenanceUrl);
                modalMaintenanceForm.classList.remove('hidden');
                modalAvailableForm.classList.add('hidden');
            } else if (status === 'maintenance') {
                modalAvailableForm.setAttribute('action', button.dataset.availableUrl);
                modalAvailableForm.classList.remove('hidden');
                modalMaintenanceForm.classList.add('hidden');
            } else {
                modalMaintenanceForm.classList.add('hidden');
                modalAvailableForm.classList.add('hidden');
            }

            toggleModal(true);
        };

        if (vehiclesContainer) {
            vehiclesContainer.addEventListener('click', (event) => {
                const paginationLink = event.target.closest('a[data-pagination="vehicles"]');
                if (!paginationLink) return;
                event.preventDefault();
                handleVehiclesPagination(paginationLink.href);
            });
            bindVehicleButtonHandlers(vehiclesContainer);
        }

        const handleTabClick = async (event) => {
            const { status, url } = event.currentTarget.dataset;
            if (!url) return;
            if (tabsContainer && tabsContainer.dataset.currentStatus === status) return;
            event.preventDefault();
            updateActiveTab(status);
            await handleVehiclesPagination(url, { updateTabFromResponse: true });
        };

        if (tabsContainer) {
            tabButtons.forEach(tab => tab.addEventListener('click', handleTabClick));
        }

        if (closeButton) {
            closeButton.addEventListener('click', () => toggleModal(false));
        }

        if (modal) {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) toggleModal(false);
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
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
        
        // Fetch vehicles with search
        function fetchVehicles(search) {
            const currentStatus = tabsContainer ? tabsContainer.dataset.currentStatus : 'all';
            let url = vehiclesContainer.dataset.url || '{{ route("vehicles.index") }}';
            const params = new URLSearchParams();
            
            if (currentStatus && currentStatus !== 'all') {
                params.append('status', currentStatus);
            }
            if (search) {
                params.append('search', search);
            }
            
            const fullUrl = params.toString() ? `${url}?${params.toString()}` : url;
            handleVehiclesPagination(fullUrl, { updateTabFromResponse: true });
            
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
                    fetchVehicles(searchValue);
                }
                toggleClearButton(searchValue);
            }, 300));
        }
        
        // Handle clear search
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                fetchVehicles('');
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
                fetchVehicles(searchParam);
            }
        });
    });
</script>
@endpush

@endsection
