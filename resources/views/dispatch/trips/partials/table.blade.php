<div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">ATW Reference</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Waybill</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Driver</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Vehicle</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Pickup</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Delivery</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Schedule</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($trips as $trip)
            <tr class="hover:bg-gray-50 cursor-pointer transition-colors" onclick="viewTrip({{ $trip->id }})" data-trip-id="{{ $trip->id }}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $trip->deliveryRequest->client->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-mono text-purple-600">{{ $trip->deliveryRequest->atw_reference }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($trip->waybill_number)
                    <div class="text-sm font-medium text-gray-900">{{ $trip->waybill_number }}</div>
                    @else
                    <span class="text-xs text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $trip->driver->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $trip->vehicle->plate_number }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <i class="fas fa-map-marker-alt text-green-500"></i>
                    {{ Str::limit($trip->deliveryRequest->pickup_location, 30) }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <i class="fas fa-flag-checkered text-red-500"></i>
                    {{ Str::limit($trip->deliveryRequest->delivery_location, 30) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $trip->scheduled_time->format('M d, h:i A') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $trip->status === 'scheduled' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $trip->status === 'in-transit' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $trip->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $trip->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst(str_replace('-', ' ', $trip->status)) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" onclick="event.stopPropagation()">
                    <div class="flex space-x-2">
                        <a href="{{ route('trips.show', $trip) }}" class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($trip->status === 'scheduled' || $trip->status === 'in-transit')
                        <a href="{{ route('trips.show', $trip) }}" class="w-8 h-8 flex items-center justify-center bg-[#1E40AF] text-white rounded-full hover:bg-[#1A36A0] transition-colors" title="Edit Trip">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        @if(auth()->user()->role === 'admin' && in_array($trip->status, ['scheduled', 'in-transit']))
                        <form method="POST" action="{{ route('trips.cancel', $trip) }}" class="inline">
                            @csrf
                            <button type="button" class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors cancel-trip-btn" title="Cancel Trip">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                    No trips found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($trips->hasPages())
<div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <span class="text-sm text-gray-600">
        Showing {{ $trips->firstItem() }} to {{ $trips->lastItem() }} of {{ $trips->total() }} trips
    </span>
    <div class="flex gap-3">
        @if($trips->onFirstPage())
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Previous</span>
        @else
        <a href="{{ $trips->previousPageUrl() }}" data-pagination="trips"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Previous
        </a>
        @endif

        @if($trips->hasMorePages())
        <a href="{{ $trips->nextPageUrl() }}" data-pagination="trips"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Next
        </a>
        @else
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
        @endif
    </div>
</div>
@endif

<script>
function viewTrip(tripId) {
    window.location.href = /trips/${tripId};
}

// Handle cancel button confirmation
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cancel-trip-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (confirm('Are you sure you want to cancel this trip?')) {
                this.closest('form').submit();
            }
        });
    });
});
</script>
