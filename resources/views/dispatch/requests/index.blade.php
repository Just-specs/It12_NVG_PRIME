@extends('layouts.app')

@section('title', 'Delivery Requests')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-clipboard-list text-blue-600"></i> Delivery Requests
        </h1>
        <button type="button" id="open-create-request-modal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus"></i> New Request
        </button>
    </div>

    <!-- Status Filter Tabs -->
    @php
    $tabs = [
        'all' => 'All Requests',
        'pending' => 'Pending',
        'verified' => 'Verified',
        'assigned' => 'Assigned',
    ];
    
    // Add 'archived' tab for admin users only
    if (auth()->user()->role === 'admin') {
        $tabs['archived'] = 'Archived';
    }
    @endphp
    <div class="mb-6">
        <nav id="request-status-tabs" data-current-status="{{ $activeStatus ?? 'all' }}"
            class="flex flex-wrap gap-3">
            @foreach($tabs as $statusValue => $label)
            @php
            $isActive = ($activeStatus ?? 'all') === $statusValue;
            $count = $counts[$statusValue] ?? 0;
            @endphp
            <button type="button"
                class="status-tab group flex items-center justify-between gap-3 rounded-full border-2 px-6 py-2 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-[#1E40AF]/30 {{ $isActive ? 'bg-[#1E40AF] text-white border-[#1E40AF] shadow-lg' : 'bg-white text-[#1E40AF] border-[#1E40AF]/40 hover:border-[#1E40AF] hover:shadow-md' }}"
                data-status="{{ $statusValue }}"
                data-url="{{ $statusValue === 'all' ? route('requests.index') : route('requests.index', ['status' => $statusValue]) }}">
                <span>{{ $label }}</span>
                <span class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full px-2 py-0.5 text-xs font-bold transition {{ $isActive ? 'bg-white text-[#1E40AF]' : 'bg-[#1E40AF]/10 text-[#1E40AF] group-hover:bg-[#1E40AF]/20' }}"
                    data-status-count>{{ $count }}</span>
            </button>
            @endforeach
        </nav>
    </div>

    <!-- Search Section -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Search Requests</h3>
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

    <!-- Requests Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div id="requests-table-container" data-url="{{ route('requests.index') }}">
            @include('dispatch.requests.partials.table', ['requests' => $requests])
        </div>
    </div>
</div>

<!-- Request Details Modal -->
<div id="request-details-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Request Details</h2>
                <p id="modal-created" class="text-sm text-gray-500">Created â€”</p>
            </div>
            <button type="button" class="text-gray-500 hover:text-gray-700" id="modal-close">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
            <form id="modal-verify-form" method="POST" class="hidden">
                @csrf
            </form>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-user text-blue-600"></i> Client Information
                            </h3>
                            <span id="modal-status"
                                class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 capitalize">Status</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Client Name</p>
                                <p id="modal-client-name" class="text-base font-semibold text-gray-800">â€”</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Contact Method</p>
                                <p class="text-base font-semibold text-gray-800">
                                    <i id="modal-contact-icon" class="fas fa-envelope mr-2"></i>
                                    <span id="modal-contact-method">â€”</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-box text-blue-600"></i> Delivery Details
                        </h3>
                        <div class="space-y-3 text-sm text-gray-700">
                            <div class="flex flex-wrap gap-2">
                                <span class="w-40 text-gray-500">ATW Reference:</span>
                                <span id="modal-atw" class="font-semibold">â€”</span>
                                <span id="modal-atw-status" class="text-xs font-semibold px-2 py-1 rounded-full">â€”</span>
                            </div>
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
                                <span class="w-40 text-gray-500">Schedule:</span>
                                <span id="modal-schedule" class="font-semibold">â€”</span>
                            </div>
                            <div class="flex flex-wrap gap-2" id="modal-notes-row" hidden>
                                <span class="w-40 text-gray-500">Notes:</span>
                                <span id="modal-notes" class="font-semibold text-gray-800">â€”</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Timeline</h4>
                        <div class="flex items-start space-x-3">
                            <div class="text-blue-600">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Request Created</p>
                                <p id="modal-timeline" class="text-xs text-gray-500">â€”</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Actions</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Viewing request #<span id="modal-request-id">â€”</span></p>
                                <p class="text-xs text-gray-500">ATW Status: <span id="modal-atw-status-text" class="font-semibold">â€”</span></p>
                            </div>
                            <button id="modal-verify-atw"
                                type="button"
                                class="hidden w-full px-3 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 transition">
                                Verify ATW
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verify Confirmation Modal -->
<div id="verify-confirm-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl p-8 space-y-6">
        <div class="space-y-2 text-center">
            <h3 class="text-2xl font-semibold text-[#1E40AF]">Verify ATW</h3>
            <p class="text-sm text-gray-600">Are you sure you want to verify?</p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <button id="verify-confirm-no" type="button"
                class="px-6 py-3 rounded-full text-base font-semibold text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition">
                Cancel
            </button>
            <button id="verify-confirm-yes" type="button"
                class="px-6 py-3 rounded-full text-base font-semibold text-white bg-[#1E40AF] hover:bg-[#1A36A0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E40AF] border border-[#1E40AF] transition">
                Confirm
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const requestsContainer = document.getElementById('requests-table-container');
        const tabsContainer = document.getElementById('request-status-tabs');
        const tabButtons = tabsContainer ? Array.from(tabsContainer.querySelectorAll('.status-tab')) : [];
        const TAB_ACTIVE_CLASSES = ['bg-[#1E40AF]', 'text-white', 'border-transparent', 'shadow-xl'];
        const TAB_INACTIVE_CLASSES = ['bg-white', 'text-[#1E40AF]', 'border-[#1E40AF]/40', 'hover:border-[#1E40AF]', 'hover:shadow-md'];
        const BADGE_ACTIVE_CLASSES = ['bg-white', 'text-[#1E40AF]'];
        const BADGE_INACTIVE_CLASSES = ['bg-[#1E40AF]/10', 'text-[#1E40AF]', 'group-hover:bg-[#1E40AF]/20'];

        const applyClasses = (element, add = [], remove = []) => {
            if (!element) {
                return;
            }
            remove.forEach(cls => element.classList.remove(cls));
            add.forEach(cls => element.classList.add(cls));
        };

        const setRequestsLoading = (isLoading) => {
            if (!requestsContainer) {
                return;
            }

            requestsContainer.classList.toggle('opacity-50', isLoading);
            requestsContainer.classList.toggle('pointer-events-none', isLoading);
        };

        const bindRequestButtonHandlers = (root = document) => {
            root.querySelectorAll('.view-request-btn').forEach(button => {
                button.addEventListener('click', handleViewClick);
            });

            root.querySelectorAll('.verify-request-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    const form = event.currentTarget.closest('form');
                    showVerifyConfirm({
                        form,
                        trigger: event.currentTarget,
                        context: 'table'
                    });
                });
            });
        };

        const updateActiveTab = (status) => {
            if (!tabsContainer) {
                return;
            }

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

        const handleRequestsPagination = async (url, {
            updateTabFromResponse = false
        } = {}) => {
            if (!requestsContainer) {
                return;
            }

            try {
                setRequestsLoading(true);
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load requests page');
                }

                const data = await response.json();

                if (data.html) {
                    requestsContainer.innerHTML = data.html;
                    bindRequestButtonHandlers(requestsContainer);
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
                setRequestsLoading(false);
            }
        };

        const modal = document.getElementById('request-details-modal');
        const closeButton = document.getElementById('modal-close');
        const modalCreated = document.getElementById('modal-created');
        const modalClientName = document.getElementById('modal-client-name');
        const modalContactMethod = document.getElementById('modal-contact-method');
        const modalContactIcon = document.getElementById('modal-contact-icon');
        const modalAtw = document.getElementById('modal-atw');
        const modalAtwStatus = document.getElementById('modal-atw-status');
        const modalAtwStatusText = document.getElementById('modal-atw-status-text');
        const modalContainer = document.getElementById('modal-container');
        const modalPickup = document.getElementById('modal-pickup');
        const modalDelivery = document.getElementById('modal-delivery');
        const modalSchedule = document.getElementById('modal-schedule');
        const modalNotes = document.getElementById('modal-notes');
        const modalNotesRow = document.getElementById('modal-notes-row');
        const modalStatus = document.getElementById('modal-status');
        const modalRequestId = document.getElementById('modal-request-id');
        const modalTimeline = document.getElementById('modal-timeline');
        const modalVerifyAtw = document.getElementById('modal-verify-atw');
        const modalCloseButton = document.getElementById('modal-close-button');
        const modalVerifyForm = document.getElementById('modal-verify-form');
        const verifyConfirmModal = document.getElementById('verify-confirm-modal');
        const verifyConfirmYes = document.getElementById('verify-confirm-yes');
        const verifyConfirmNo = document.getElementById('verify-confirm-no');
        let currentVerifyUrl = null;
        let pendingVerifyForm = null;
        let lastVerifyTrigger = null;
        let pendingVerifyContext = null;

        if (!modal || !closeButton) {
            return;
        }

        const statusClasses = {
            pending: ['bg-yellow-100', 'text-yellow-800'],
            verified: ['bg-green-100', 'text-green-800'],
            assigned: ['bg-blue-100', 'text-blue-800'],
            completed: ['bg-gray-100', 'text-gray-800']
        };

        const contactIcons = {
            mobile: 'fa-phone',
            email: 'fa-envelope',
            group_chat: 'fa-comments'
        };

        const showVerifyConfirm = ({
            form = null,
            trigger = null,
            context = null
        } = {}) => {
            if (!verifyConfirmModal) {
                if (form) {
                    form.submit();
                } else if (modalVerifyForm && currentVerifyUrl) {
                    modalVerifyForm.submit();
                }
                return;
            }

            pendingVerifyForm = form;
            lastVerifyTrigger = trigger;
            pendingVerifyContext = context;

            verifyConfirmModal.classList.remove('hidden');
            verifyConfirmModal.classList.add('flex');

            if (verifyConfirmYes) {
                verifyConfirmYes.focus({
                    preventScroll: true
                });
            }
        };

        const hideVerifyConfirm = ({
            refocus = true
        } = {}) => {
            if (verifyConfirmModal) {
                verifyConfirmModal.classList.add('hidden');
                verifyConfirmModal.classList.remove('flex');
            }

            if (pendingVerifyContext === 'modal' && modalVerifyAtw && currentVerifyUrl) {
                modalVerifyAtw.classList.remove('hidden');
            }

            if (refocus && lastVerifyTrigger) {
                lastVerifyTrigger.focus({
                    preventScroll: true
                });
            }

            pendingVerifyForm = null;
            lastVerifyTrigger = null;
            pendingVerifyContext = null;
        };

        const toggleModal = (show) => {
            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                hideVerifyConfirm({
                    refocus: false
                });
            }
        };

        const updateStatusBadge = (statusElement, status) => {
            statusElement.className = 'px-3 py-1 rounded-full text-xs font-semibold capitalize';
            const classes = statusClasses[status] ?? ['bg-gray-100', 'text-gray-800'];
            statusElement.classList.add(...classes);
            statusElement.textContent = status.replace('_', ' ');
        };

        const updateAtwStatusBadge = (badgeElement, isVerified) => {
            badgeElement.className = 'text-xs font-semibold px-2 py-1 rounded-full';
            if (isVerified) {
                badgeElement.classList.add('bg-green-100', 'text-green-700');
                badgeElement.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Verified';
            } else {
                badgeElement.classList.add('bg-yellow-100', 'text-yellow-700');
                badgeElement.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> Pending Verification';
            }
        };

        const handleViewClick = (event) => {
            const button = event.currentTarget;

            modalCreated.textContent = `Created ${button.dataset.created}`;
            modalClientName.textContent = button.dataset.clientName;
            const contactMethod = button.dataset.contactMethod;
            modalContactMethod.textContent = button.dataset.contactMethodLabel;
            modalContactIcon.className = `fas ${contactIcons[contactMethod] || 'fa-envelope'} mr-2`;
            modalAtw.textContent = button.dataset.atwReference;
            const isVerified = button.dataset.atwVerified === '1';
            updateAtwStatusBadge(modalAtwStatus, isVerified);
            modalAtwStatusText.textContent = isVerified ? 'Verified' : 'Pending Verification';
            modalContainer.textContent = `${button.dataset.containerSize} - ${button.dataset.containerType}`;
            modalPickup.textContent = button.dataset.pickup;
            modalDelivery.textContent = button.dataset.delivery;
            modalSchedule.textContent = `${button.dataset.scheduleDate} at ${button.dataset.scheduleTime}`;

            if (button.dataset.notes) {
                modalNotesRow.hidden = false;
                modalNotes.textContent = button.dataset.notes;
            } else {
                modalNotesRow.hidden = true;
                modalNotes.textContent = '';
            }

            updateStatusBadge(modalStatus, button.dataset.status);
            modalRequestId.textContent = button.dataset.requestId;
            modalTimeline.textContent = button.dataset.created;

            currentVerifyUrl = button.dataset.verifyUrl || '';
            if (modalVerifyAtw && modalVerifyForm) {
                if (currentVerifyUrl) {
                    modalVerifyAtw.classList.remove('hidden');
                    hideVerifyConfirm({
                        refocus: false
                    });
                    modalVerifyForm.setAttribute('action', currentVerifyUrl);
                } else {
                    modalVerifyAtw.classList.add('hidden');
                    hideVerifyConfirm({
                        refocus: false
                    });
                    modalVerifyForm.removeAttribute('action');
                }
            }

            toggleModal(true);
        };

        if (requestsContainer) {
            requestsContainer.addEventListener('click', (event) => {
                const paginationLink = event.target.closest('a[data-pagination="requests"]');
                if (!paginationLink) {
                    return;
                }

                event.preventDefault();
                handleRequestsPagination(paginationLink.href);
            });

            bindRequestButtonHandlers(requestsContainer);
        }

        const handleTabClick = async (event) => {
            const {
                status,
                url
            } = event.currentTarget.dataset;

            if (!url) {
                return;
            }

            if (tabsContainer && tabsContainer.dataset.currentStatus === status) {
                return;
            }

            event.preventDefault();
            updateActiveTab(status);
            await handleRequestsPagination(url, {
                updateTabFromResponse: true
            });
        };

        if (tabsContainer) {
            tabButtons.forEach(tab => {
                tab.addEventListener('click', handleTabClick);
            });
        }

        closeButton.addEventListener('click', () => toggleModal(false));
        if (modalCloseButton) {
            modalCloseButton.addEventListener('click', () => toggleModal(false));
        }
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                toggleModal(false);
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') {
                return;
            }

            if (verifyConfirmModal && verifyConfirmModal.classList.contains('flex')) {
                hideVerifyConfirm();
                if (modalVerifyAtw && currentVerifyUrl) {
                    modalVerifyAtw.focus({
                        preventScroll: true
                    });
                }
                return;
            }

            if (!modal.classList.contains('hidden')) {
                toggleModal(false);
            }
        });

        if (modalVerifyAtw && modalVerifyForm) {
            modalVerifyAtw.addEventListener('click', () => {
                if (currentVerifyUrl) {
                    showVerifyConfirm({
                        form: modalVerifyForm,
                        trigger: modalVerifyAtw,
                        context: 'modal'
                    });
                }
            });
        }

        if (verifyConfirmYes) {
            verifyConfirmYes.addEventListener('click', () => {
                if (pendingVerifyForm) {
                    const formToSubmit = pendingVerifyForm;
                    hideVerifyConfirm({
                        refocus: false
                    });
                    formToSubmit.submit();
                    return;
                }

                if (modalVerifyForm && currentVerifyUrl) {
                    hideVerifyConfirm({
                        refocus: false
                    });
                    modalVerifyForm.submit();
                }
            });
        }

        if (verifyConfirmNo) {
            verifyConfirmNo.addEventListener('click', () => {
                hideVerifyConfirm();
            });
        }

        if (verifyConfirmModal) {
            verifyConfirmModal.addEventListener('click', (event) => {
                if (event.target === verifyConfirmModal) {
                    hideVerifyConfirm();
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

        // Fetch requests with search
        function fetchRequests(search) {
            const currentStatus = tabsContainer ? tabsContainer.dataset.currentStatus : 'all';
            let url = requestsContainer.dataset.url || '{{ route("requests.index") }}';
            const params = new URLSearchParams();

            if (currentStatus && currentStatus !== 'all') {
                params.append('status', currentStatus);
            }
            if (search) {
                params.append('search', search);
            }

            const fullUrl = params.toString() ? `${url}?${params.toString()}` : url;
            handleRequestsPagination(fullUrl, {
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
                    fetchRequests(searchValue);
                }
                toggleClearButton(searchValue);
            }, 300));
        }

        // Handle clear search
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                fetchRequests('');
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
                fetchRequests(searchParam);
            }
        });

    });
</script>
<!-- Create Request Modal -->
<div id="create-request-modal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4" style="overflow-y: auto;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full my-8">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-plus-circle text-blue-600"></i> New Delivery Request
            </h2>
            <button type="button" id="close-create-modal" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
            <form id="modal-create-request-form" method="POST" action="{{ route('requests.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Client -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Client <span class="text-red-500">*</span>
                        </label>
                        <select name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Client</option>
                            @foreach(\App\Models\Client::all() as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contact Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Method <span class="text-red-500">*</span>
                        </label>
                        <select name="contact_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="mobile">Mobile Call</option>
                            <option value="group_chat">Group Chat</option>
                            <option value="email">Email</option>
                        </select>
                    </div>

                    <!-- ATW Reference -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ATW Reference <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="atw_reference" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter ATW reference number">
                    </div>

                    <!-- Pickup Location -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pickup Location <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="pickup_location" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter pickup location">
                    </div>

                    <!-- Delivery Location -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Delivery Location <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="delivery_location" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter delivery location">
                    </div>

                    <!-- Container Size -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Container Size <span class="text-red-500">*</span>
                        </label>
                        <select name="container_size" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="20ft">20ft</option>
                            <option value="40ft">40ft</option>
                            <option value="40ft HC">40ft HC</option>
                        </select>
                    </div>

                    <!-- Container Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Container Type <span class="text-red-500">*</span>
                        </label>
                        <select name="container_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="standard">Standard</option>
                            <option value="refrigerated">Refrigerated</option>
                            <option value="open_top">Open Top</option>
                            <option value="flat_rack">Flat Rack</option>
                        </select>
                    </div>

                    <!-- Preferred Schedule -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Schedule <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="preferred_schedule" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Phase 1: Shipping Documentation Section -->
                    <div class="md:col-span-2 pt-4 border-t">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-ship text-blue-600"></i> Shipping Documentation
                        </h3>
                    </div>

                    <!-- Shipping Line -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Shipping Line
                        </label>
                        <input type="text" name="shipping_line" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., WANHAI, CMA, MSC">
                    </div>

                    <!-- Shipper Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Shipper Name
                        </label>
                        <input type="text" name="shipper_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter shipper company name">
                    </div>

                    <!-- Booking Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Booking Number
                        </label>
                        <input type="text" name="booking_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter booking reference">
                    </div>

                    <!-- Container Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Container Number
                        </label>
                        <input type="text" name="container_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter container ID">
                    </div>

                    <!-- EIR Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            EIR Number
                        </label>
                        <input type="text" name="eir_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter EIR number">
                    </div>

                    <!-- Seal Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Seal Number
                        </label>
                        <input type="text" name="seal_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter seal number">
                    </div>
                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Additional Notes
                        </label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Any special instructions or notes"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                    <button type="button" id="cancel-create-request" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-lg">
                        <i class="fas fa-save mr-2"></i>Create Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openModalBtn = document.getElementById('open-create-request-modal');
        const modal = document.getElementById('create-request-modal');
        const closeModalBtn = document.getElementById('close-create-modal');
        const cancelBtn = document.getElementById('cancel-create-request');
        const form = document.getElementById('modal-create-request-form');

        if (!openModalBtn || !modal) return;

        const showModal = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        };

        const hideModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
            form.reset();
        };

        openModalBtn.addEventListener('click', showModal);
        closeModalBtn?.addEventListener('click', hideModal);
        cancelBtn?.addEventListener('click', hideModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) hideModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });
    });
</script>

@endpush

@include('dispatch.requests.partials.assign-driver-modal-table')

@endsection

