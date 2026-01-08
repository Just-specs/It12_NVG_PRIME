@extends('layouts.app')

@section('title', 'Co-Drivers Management')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-users text-blue-600"></i> Co-Drivers Management
                </h1>
                <p class="text-gray-600 mt-1">Manage driver co-driver relationships</p>
            </div>
            <button onclick="openAssignModal()" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i> Assign Co-Driver
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6 bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('co-drivers.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ $search ?? '' }}"
                       placeholder="Search by name, license, or mobile..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-search mr-2"></i> Search
            </button>
            @if($search)
                <a href="{{ route('co-drivers.index') }}" 
                   class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Drivers Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Driver
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        License Number
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mobile
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Co-Drivers
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($drivers as $driver)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $driver->name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($driver->status) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <i class="fas fa-id-card text-gray-400 mr-2"></i>{{ $driver->license_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <i class="fas fa-phone text-gray-400 mr-2"></i>{{ $driver->mobile }}
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $allCoDrivers = $driver->getAllCoDrivers();
                        @endphp
                        
                        @if($allCoDrivers->count() > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach($allCoDrivers as $coDriver)
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-md">
                                        <i class="fas fa-user-friends mr-1"></i>{{ $coDriver->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm text-gray-400 italic">No co-drivers</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="openAssignModalForDriver({{ $driver->id }}, '{{ addslashes($driver->name) }}')" 
                                class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-plus mr-1"></i> Add
                        </button>
                        <a href="{{ route('drivers.coDrivers', $driver) }}" 
                           class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-cog mr-1"></i> Manage
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-users text-4xl mb-2"></i>
                        <p>No drivers found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($drivers->hasPages())
    <div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-b-lg shadow-md">
        <span class="text-sm text-gray-600">
            Showing {{ $drivers->firstItem() }} to {{ $drivers->lastItem() }} of {{ $drivers->total() }} drivers
        </span>
        <div class="flex gap-3">
            @if($drivers->onFirstPage())
            <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left mr-1"></i> Previous
            </span>
            @else
            <a href="{{ $drivers->previousPageUrl() }}" 
                class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors">
                <i class="fas fa-chevron-left mr-1"></i> Previous
            </a>
            @endif

            @if($drivers->hasMorePages())
            <a href="{{ $drivers->nextPageUrl() }}" 
                class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors">
                Next <i class="fas fa-chevron-right ml-1"></i>
            </a>
            @else
            <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                Next <i class="fas fa-chevron-right ml-1"></i>
            </span>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Assign Co-Driver Modal -->
<div id="assign-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-user-plus text-blue-600"></i> Assign Co-Driver
        </h3>
        
        <form id="assign-form" method="POST" action="{{ route('co-drivers.assign') }}">
            @csrf
            
            <div class="mb-4">
                <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Main Driver <span class="text-red-500">*</span>
                </label>
                <select name="driver_id" id="driver_id" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Select Main Driver --</option>
                    @foreach($availableDrivers as $d)
                        <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->license_number }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label for="co_driver_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Co-Driver <span class="text-red-500">*</span>
                </label>
                <select name="co_driver_id" id="co_driver_id" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Select Co-Driver --</option>
                    @foreach($availableDrivers as $d)
                        <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->license_number }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-3 mb-4">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-1"></i>
                    This will create a bi-directional co-driver relationship.
                </p>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeAssignModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-check mr-2"></i> Assign
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAssignModal() {
    document.getElementById('assign-modal').classList.remove('hidden');
    document.getElementById('driver_id').value = '';
    document.getElementById('co_driver_id').value = '';
}

function openAssignModalForDriver(driverId, driverName) {
    document.getElementById('assign-modal').classList.remove('hidden');
    document.getElementById('driver_id').value = driverId;
    document.getElementById('co_driver_id').value = '';
}

function closeAssignModal() {
    document.getElementById('assign-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('assign-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAssignModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAssignModal();
    }
});

// Filter out selected driver from co-driver dropdown
document.getElementById('driver_id').addEventListener('change', function() {
    const selectedDriverId = this.value;
    const coDriverSelect = document.getElementById('co_driver_id');
    const options = coDriverSelect.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.value === selectedDriverId) {
            option.style.display = 'none';
        } else {
            option.style.display = 'block';
        }
    });
    
    // Reset co-driver selection if it matches main driver
    if (coDriverSelect.value === selectedDriverId) {
        coDriverSelect.value = '';
    }
});
</script>
@endsection
