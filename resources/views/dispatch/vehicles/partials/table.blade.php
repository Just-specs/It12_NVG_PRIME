<div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plate Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Vehicle Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Trailer Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Total Trips</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($vehicles as $vehicle)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-truck text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $vehicle->plate_number }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $vehicle->vehicle_type }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-600">{{ $vehicle->trailer_type }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-blue-600">{{ $vehicle->trips_count }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $vehicle->status === 'in-use' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $vehicle->status === 'maintenance' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst(str_replace('-', ' ', $vehicle->status)) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex space-x-2">
                        <button type="button"
                            class="text-blue-600 hover:text-blue-800 view-vehicle-btn"
                            title="View"
                            data-vehicle-id="{{ $vehicle->id }}"
                            data-plate-number="{{ $vehicle->plate_number }}"
                            data-vehicle-type="{{ $vehicle->vehicle_type }}"
                            data-trailer-type="{{ $vehicle->trailer_type }}"
                            data-status="{{ $vehicle->status }}"
                            data-trips-count="{{ $vehicle->trips_count }}"
                            data-created-at="{{ $vehicle->created_at->format('M d, Y') }}"
                            data-view-url="{{ route('vehicles.show', $vehicle) }}"
                            data-maintenance-url="{{ $vehicle->status === 'available' ? route('vehicles.set-maintenance', $vehicle) : '' }}"
                            data-available-url="{{ $vehicle->status === 'maintenance' ? route('vehicles.set-available', $vehicle) : '' }}">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if($vehicle->status === 'available')
                        <form method="POST" action="{{ route('vehicles.set-maintenance', $vehicle) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-orange-600 hover:text-orange-800" title="Set Maintenance">
                                <i class="fas fa-tools"></i>
                            </button>
                        </form>
                        @elseif($vehicle->status === 'maintenance')
                        <form method="POST" action="{{ route('vehicles.set-available', $vehicle) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800" title="Set Available">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    No vehicles found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($vehicles->hasPages())
<div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <span class="text-sm text-gray-600">
        Showing {{ $vehicles->firstItem() }} to {{ $vehicles->lastItem() }} of {{ $vehicles->total() }} vehicles
    </span>
    <div class="flex gap-3">
        @if($vehicles->onFirstPage())
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Previous</span>
        @else
        <a href="{{ $vehicles->previousPageUrl() }}" data-pagination="vehicles"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Previous
        </a>
        @endif

        @if($vehicles->hasMorePages())
        <a href="{{ $vehicles->nextPageUrl() }}" data-pagination="vehicles"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Next
        </a>
        @else
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
        @endif
    </div>
</div>
@endif
