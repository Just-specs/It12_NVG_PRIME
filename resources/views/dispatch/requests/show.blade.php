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
                        <p class="text-gray-600 text-sm mt-1">Created {{ $request->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $request->status === 'verified' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $request->status === 'assigned' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $request->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst($request->status) }}
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
                            <p class="font-semibold">{{ $request->client->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Contact Method</p>
                            <p class="font-semibold">
                                <i class="fas fa-{{ $request->contact_method === 'mobile' ? 'phone' : ($request->contact_method === 'email' ? 'envelope' : 'comments') }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $request->contact_method)) }}
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
                                {{ $request->atw_reference }}
                                @if($request->atw_verified)
                                <span class="ml-2 text-green-600 text-sm"><i class="fas fa-check-circle"></i> Verified</span>
                                @else
                                <span class="ml-2 text-yellow-600 text-sm"><i class="fas fa-exclamation-circle"></i> Pending Verification</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">Container:</div>
                            <div class="flex-1 font-semibold">{{ $request->container_size }} - {{ ucfirst($request->container_type) }}</div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">Pickup:</div>
                            <div class="flex-1">
                                <i class="fas fa-map-marker-alt text-green-500"></i>
                                {{ $request->pickup_location }}
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">Delivery:</div>
                            <div class="flex-1">
                                <i class="fas fa-flag-checkered text-red-500"></i>
                                {{ $request->delivery_location }}
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">Schedule:</div>
                            <div class="flex-1 font-semibold">
                                <i class="far fa-calendar-alt"></i>
                                {{ $request->preferred_schedule->format('F d, Y') }} at {{ $request->preferred_schedule->format('h:i A') }}
                            </div>
                        </div>
                        @if($request->notes)
                        <div class="flex items-start">
                            <div class="w-32 text-sm text-gray-600">Notes:</div>
                            <div class="flex-1">{{ $request->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Trip Information (if assigned) -->
                @if($request->trip)
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-truck text-blue-600"></i> Assigned Trip
                    </h3>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Driver</p>
                                <p class="font-semibold">{{ $request->trip->driver->name }}</p>
                                <p class="text-xs text-gray-500">{{ $request->trip->driver->mobile }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Vehicle</p>
                                <p class="font-semibold">{{ $request->trip->vehicle->plate_number }}</p>
                                <p class="text-xs text-gray-500">{{ $request->trip->vehicle->trailer_type }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('trips.show', $request->trip) }}" class="text-blue-600 hover:text-blue-800 text-sm">
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

                @if($request->status === 'pending' && !$request->atw_verified)
                <form method="POST" action="{{ route('requests.verify', $request) }}" class="mb-3" id="verify-atw-form">
                    @csrf
                    <button type="button" id="open-verify-modal" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-check-circle"></i> Verify ATW
                    </button>
                </form>

                <a href="{{ route('requests.create') }}" class="mb-3 block w-full px-4 py-2 bg-[#2563EB] text-white rounded-lg text-center hover:bg-[#1D4ED8] transition-colors">
                    <i class="fas fa-plus-circle"></i> New Request
                </a>

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
                @endif

                @if($request->status === 'verified' && !$request->trip)
                <a href="{{ route('trips.create', ['delivery_request' => $request->id]) }}" class="block w-full px-4 py-2 bg-[#2563EB] text-white rounded-lg hover:bg-[#1D4ED8] text-center mb-3">
                    <i class="fas fa-user-plus"></i> Assign Driver
                </a>
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
                                <p class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        @if($request->atw_verified)
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">ATW Verified</p>
                                <p class="text-xs text-gray-500">{{ $request->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($request->trip)
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-check text-purple-600 text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">Driver Assigned</p>
                                <p class="text-xs text-gray-500">{{ $request->trip->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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