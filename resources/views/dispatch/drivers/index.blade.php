@extends('layouts.app')

@section('title', 'Drivers')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-tie text-blue-600"></i> Drivers Management
        </h1>
        <button type="button" id="open-create-driver-modal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus"></i> Add Driver
        </button>
    </div>

    <!-- Status Filter Tabs -->
    @php
    $tabs = [
        'all' => 'All Drivers',
        'available' => 'Available',
        'on-trip' => 'On Trip',
        'off-duty' => 'Off Duty',
    ];
    @endphp
    <div class="mb-6">
        <nav id="driver-status-tabs" data-current-status="{{ $activeStatus ?? 'all' }}"
            class="flex flex-wrap gap-3">
            @foreach($tabs as $statusValue => $label)
            @php
            $isActive = ($activeStatus ?? 'all') === $statusValue;
            $count = $counts[$statusValue] ?? 0;
            @endphp
            <button type="button"
                class="status-tab group flex items-center justify-between gap-3 rounded-full border-2 px-6 py-2 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-[#1E40AF]/30 {{ $isActive ? 'bg-[#1E40AF] text-white border-[#1E40AF] shadow-lg' : 'bg-white text-[#1E40AF] border-[#1E40AF]/40 hover:border-[#1E40AF] hover:shadow-md' }}"
                data-status="{{ $statusValue }}"
                data-url="{{ $statusValue === 'all' ? route('drivers.index') : route('drivers.index', ['status' => $statusValue]) }}">
                <span>{{ $label }}</span>
                <span class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full px-2 py-0.5 text-xs font-bold transition {{ $isActive ? 'bg-white text-[#1E40AF]' : 'bg-[#1E40AF]/10 text-[#1E40AF] group-hover:bg-[#1E40AF]/20' }}"
                    data-status-count>{{ $count }}</span>
            </button>
            @endforeach
        </nav>
    </div>

    <!-- Search Section -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Search Drivers</h3>
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

    <!-- Drivers Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div id="drivers-table-container" data-url="{{ route('drivers.index') }}">
            @include('dispatch.drivers.partials.table', ['drivers' => $drivers])
        </div>
    </div>
</div>


<!-- Add Driver Modal -->
<div id="create-driver-modal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4" style="overflow-y: auto;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full my-8">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-plus text-blue-600"></i> Add New Driver
            </h2>
            <button type="button" id="close-create-driver-modal" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
            <form id="modal-create-driver-form" method="POST" action="{{ route('drivers.store') }}">
                @csrf

                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Driver Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter driver name">
                    </div>

                    <!-- Mobile -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mobile Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="mobile" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+63 XXX XXX XXXX">
                    </div>

                    <!-- License Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            License Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="license_number" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter license number">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="available">Available</option>
                            <option value="on-trip">On Trip</option>
                            <option value="off-duty">Off Duty</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                    <button type="button" id="cancel-create-driver" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-lg">
                        <i class="fas fa-save mr-2"></i>Create Driver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Driver Details Modal -->
<div id="driver-details-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Driver Details</h2>
                <p id="modal-driver-id" class="text-sm text-gray-500">Driver #—</p>
            </div>
            <button type="button" class="text-gray-500 hover:text-gray-700" id="modal-close">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-6">
            <div class="flex items-center space-x-4">
                <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user text-blue-600 text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h3 id="modal-driver-name" class="text-2xl font-bold text-gray-800">—</h3>
                    <span id="modal-status"
                        class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 capitalize">Status</span>
                </div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">License Number</p>
                        <p id="modal-license" class="text-base font-semibold text-gray-800">—</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Mobile</p>
                        <p id="modal-mobile" class="text-base font-semibold text-gray-800">
                            <i class="fas fa-phone mr-1"></i><span id="modal-mobile-number">—</span>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Trips</p>
                        <p id="modal-trips-count" class="text-2xl font-bold text-blue-600">—</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Joined Date</p>
                        <p id="modal-created" class="text-base font-semibold text-gray-800">—</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-4 flex gap-3">
                <a id="modal-view-full" href="#"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition text-center">
                    <i class="fas fa-external-link-alt"></i> View Full Profile
                </a>
                <form id="modal-status-form" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" id="modal-status-btn"
                        class="w-full px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-semibold hover:bg-gray-700 transition">
                        <i class="fas fa-user-check"></i> Change Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const driversContainer = document.getElementById('drivers-table-container');
        const tabsContainer = document.getElementById('driver-status-tabs');
        const tabButtons = tabsContainer ? Array.from(tabsContainer.querySelectorAll('.status-tab')) : [];
        const modal = document.getElementById('driver-details-modal');
        const closeButton = document.getElementById('modal-close');
        const modalDriverId = document.getElementById('modal-driver-id');
        const modalDriverName = document.getElementById('modal-driver-name');
        const modalLicense = document.getElementById('modal-license');
        const modalMobileNumber = document.getElementById('modal-mobile-number');
        const modalStatus = document.getElementById('modal-status');
        const modalTripsCount = document.getElementById('modal-trips-count');
        const modalCreated = document.getElementById('modal-created');
        const modalViewFull = document.getElementById('modal-view-full');
        const modalStatusForm = document.getElementById('modal-status-form');
        const modalStatusBtn = document.getElementById('modal-status-btn');

        const TAB_ACTIVE_CLASSES = ['bg-[#1E40AF]', 'text-white', 'border-[#1E40AF]', 'shadow-lg'];
        const TAB_INACTIVE_CLASSES = ['bg-white', 'text-[#1E40AF]', 'border-[#1E40AF]/40', 'hover:border-[#1E40AF]', 'hover:shadow-md'];
        const BADGE_ACTIVE_CLASSES = ['bg-white', 'text-[#1E40AF]'];
        const BADGE_INACTIVE_CLASSES = ['bg-[#1E40AF]/10', 'text-[#1E40AF]', 'group-hover:bg-[#1E40AF]/20'];

        const statusClasses = {
            available: ['bg-green-100', 'text-green-800'],
            'on-trip': ['bg-blue-100', 'text-blue-800'],
            'off-duty': ['bg-gray-100', 'text-gray-800']
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

        const handleDriversPagination = async (url, options = {}) => {
            if (!driversContainer) return;
            driversContainer.classList.add('opacity-50', 'pointer-events-none');

            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                driversContainer.innerHTML = data.html;
                bindDriverButtonHandlers(driversContainer);

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
                driversContainer.classList.remove('opacity-50', 'pointer-events-none');
            }
        };

        const bindDriverButtonHandlers = (root = document) => {
            root.querySelectorAll('.view-driver-btn').forEach(button => {
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

            modalDriverId.textContent = `Driver #${button.dataset.driverId}`;
            modalDriverName.textContent = button.dataset.driverName;
            modalLicense.textContent = button.dataset.licenseNumber;
            modalMobileNumber.textContent = button.dataset.mobile;
            modalTripsCount.textContent = button.dataset.tripsCount;
            modalCreated.textContent = button.dataset.createdAt;
            updateStatusBadge(modalStatus, button.dataset.status);
            modalViewFull.href = button.dataset.viewUrl;

            const status = button.dataset.status;
            if (status !== 'on-trip') {
                modalStatusForm.setAttribute('action', button.dataset.statusUrl);
                const newStatus = status === 'available' ? 'off-duty' : 'available';
                const statusInput = modalStatusForm.querySelector('input[name="status"]') || 
                    document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = newStatus;
                if (!modalStatusForm.querySelector('input[name="status"]')) {
                    modalStatusForm.appendChild(statusInput);
                }
                modalStatusBtn.innerHTML = `<i class="fas fa-${status === 'available' ? 'user-slash' : 'user-check'}"></i> Set ${newStatus === 'available' ? 'Available' : 'Off Duty'}`;
                modalStatusForm.classList.remove('hidden');
            } else {
                modalStatusForm.classList.add('hidden');
            }

            toggleModal(true);
        };

        if (driversContainer) {
            driversContainer.addEventListener('click', (event) => {
                const paginationLink = event.target.closest('a[data-pagination="drivers"]');
                if (!paginationLink) return;
                event.preventDefault();
                handleDriversPagination(paginationLink.href);
            });
            bindDriverButtonHandlers(driversContainer);
        }

        const handleTabClick = async (event) => {
            const { status, url } = event.currentTarget.dataset;
            if (!url) return;
            if (tabsContainer && tabsContainer.dataset.currentStatus === status) return;
            event.preventDefault();
            updateActiveTab(status);
            await handleDriversPagination(url, { updateTabFromResponse: true });
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
            
        // Create Driver Modal
        const openCreateDriverBtn = document.getElementById('open-create-driver-modal');
        const createDriverModal = document.getElementById('create-driver-modal');
        const closeCreateDriverBtn = document.getElementById('close-create-driver-modal');
        const cancelCreateDriverBtn = document.getElementById('cancel-create-driver');
        const createDriverForm = document.getElementById('modal-create-driver-form');

        if (openCreateDriverBtn && createDriverModal) {
            const showCreateModal = () => {
                createDriverModal.classList.remove('hidden');
                createDriverModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            };

            const hideCreateModal = () => {
                createDriverModal.classList.add('hidden');
                createDriverModal.classList.remove('flex');
                document.body.style.overflow = '';
                if (createDriverForm) createDriverForm.reset();
            };

            openCreateDriverBtn.addEventListener('click', showCreateModal);
            closeCreateDriverBtn?.addEventListener('click', hideCreateModal);
            cancelCreateDriverBtn?.addEventListener('click', hideCreateModal);
            // Handle form submission
            if (createDriverForm) {
                createDriverForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    
                    const submitBtn = createDriverForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
                    
                    const formData = new FormData(createDriverForm);
                    
                    fetch(createDriverForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        
                        if (data.success) {
                            // Driver created successfully
                            window.location.reload();
                        } else if (data.confirm_required) {
                            if (confirm(data.message + '\n\nDo you want to create this driver anyway?')) {
                                formData.append('confirm_duplicate', '1');
                                fetch(createDriverForm.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Driver created successfully
                                        window.location.reload();
                                    }
                                });
                            } else {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                            }
                        } else {
                            alert('Error: ' + (data.message || 'Failed to create driver'));
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error creating driver. Please try again.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            }

            createDriverModal.addEventListener('click', (e) => {
                if (e.target === createDriverModal) hideCreateModal();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !createDriverModal.classList.contains('hidden')) {
                    hideCreateModal();
                }
            });
        }
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
        
        // Fetch drivers with search
        function fetchDrivers(search) {
            const currentStatus = tabsContainer ? tabsContainer.dataset.currentStatus : 'all';
            let url = driversContainer.dataset.url || '{{ route("drivers.index") }}';
            const params = new URLSearchParams();
            
            if (currentStatus && currentStatus !== 'all') {
                params.append('status', currentStatus);
            }
            if (search) {
                params.append('search', search);
            }
            
            const fullUrl = params.toString() ? `${url}?${params.toString()}` : url;
            handleDriversPagination(fullUrl, { updateTabFromResponse: true });
            
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
                    fetchDrivers(searchValue);
                }
                toggleClearButton(searchValue);
            }, 300));
        }
        
        // Handle clear search
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                fetchDrivers('');
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
                fetchDrivers(searchParam);
            }
        });
    });
</script>
@endpush

@endsection





