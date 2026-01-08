<div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plate Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Vehicle Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Trailer Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($vehicles as $vehicle)
            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location.href='{{ route('vehicles.show', $vehicle) }}'">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $vehicle->plate_number }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <i class="fas fa-truck text-purple-500"></i> {{ $vehicle->vehicle_type }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <i class="fas fa-trailer text-blue-500"></i> {{ $vehicle->trailer_type }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $vehicle->status === 'in-use' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $vehicle->status === 'maintenance' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst(str_replace('-', ' ', $vehicle->status)) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" onclick="event.stopPropagation()">
                    <div class="flex space-x-2">
                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors" title="Edit Vehicle">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'head_dispatch')
                        <a href="{{ route('vehicles.requestDelete', $vehicle) }}" onclick="event.stopPropagation();" class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors" title="Request Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
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
