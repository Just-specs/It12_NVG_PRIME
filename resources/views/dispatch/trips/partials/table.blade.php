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
                    <div class="text-sm font-medium text-gray-900">
                        @if($trip->deliveryRequest && $trip->deliveryRequest->client)
                            {{ $trip->deliveryRequest?->client?->name ?? 'N/A' }}
                        @else
                            <span class="text-red-500">Client Deleted</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-bold text-gray-900">{{ $trip->deliveryRequest?->atw_reference ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($trip->waybill_number)
                    <div class="text-sm font-medium text-gray-900">{{ $trip->waybill_number }}</div>
                    @else
                    <span class="text-xs text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                        @if($trip->driver)
                            {{ $trip->driver->name }}
                        @else
                            <span class="text-red-500">Driver Deleted</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                        @if($trip->vehicle)
                            {{ $trip->vehicle ? $trip->vehicle->plate_number : 'N/A' }}
                        @else
                            <span class="text-red-500">Vehicle Deleted</span>
                        @endif
                    </div>
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
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $trip->status === 'scheduled' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $trip->status === 'in-transit' ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg font-bold ' : '' }}
                            {{ $trip->status === 'delayed' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $trip->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $trip->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst(str_replace('-', ' ', $trip->status)) }}
                        </span>
                        @if($trip->status === 'delayed' && $trip->delay_reason)
                        <button onclick="event.stopPropagation(); showDelayReasonModal({{ $trip->id }}, '{{ addslashes($trip->delay_reason) }}', '{{ $trip->delay_detected_at ? $trip->delay_detected_at->format('M d, Y h:i A') : 'N/A' }}', '{{ $trip->delayReasonBy?->name ?? 'System' }}', {{ $trip->delay_minutes ?? 0 }})" 
                                class="text-orange-600 hover:text-orange-800 transition-colors" 
                                title="View delay reason">
                            <i class="fas fa-exclamation-circle text-lg"></i>
                        </button>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" onclick="event.stopPropagation()">
                    <div class="flex space-x-2">
                        @if($trip->status === 'scheduled' || $trip->status === 'in-transit')
                        <a href="{{ route('trips.show', $trip) }}" class="w-8 h-8 flex items-center justify-center bg-[#1E40AF] text-white rounded-full hover:bg-[#1A36A0] transition-colors" title="Edit Trip">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        
                        @if($trip->status === 'delayed')
                        <a href="{{ route('trips.delay-reason', $trip) }}" class="w-8 h-8 flex items-center justify-center bg-orange-600 text-white rounded-full hover:bg-orange-700 transition-colors" title="Provide Delay Reason">
                            <i class="fas fa-comment-alt"></i>
                        </a>
                        @endif
                        
                        @if(in_array(auth()->user()->role, ['admin', 'head_dispatch']) && in_array($trip->status, ['scheduled', 'in-transit']))
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

<!-- Delay Reason Modal -->
<div id="delay-reason-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-lg" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">
                <i class="fas fa-exclamation-circle text-orange-600"></i> Delay Information
            </h3>
            <button onclick="closeDelayReasonModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="space-y-4">
            <!-- Trip ID -->
            <div class="bg-orange-50 p-3 rounded-lg border border-orange-200">
                <p class="text-sm font-semibold text-orange-800">Trip #<span id="delay-trip-id"></span></p>
            </div>

            <!-- Delay Reason -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment-alt mr-1"></i> Reason for Delay
                </label>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p id="delay-reason-text" class="text-gray-800 whitespace-pre-wrap"></p>
                </div>
            </div>

            <!-- Delay Details Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-clock mr-1"></i> Detected At
                    </label>
                    <p id="delay-detected-time" class="text-sm text-gray-800 bg-gray-50 p-2 rounded"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-hourglass-half mr-1"></i> Delay Duration
                    </label>
                    <p id="delay-minutes" class="text-sm text-gray-800 bg-gray-50 p-2 rounded"></p>
                </div>
            </div>

            <!-- Reported By -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-user mr-1"></i> Reported By
                </label>
                <p id="delay-reported-by" class="text-sm text-gray-800 bg-gray-50 p-2 rounded"></p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button onclick="closeDelayReasonModal()" 
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-times mr-2"></i> Close
            </button>
        </div>
    </div>
</div>

<script>
function viewTrip(tripId) {
    window.location.href = `/trips/${tripId}`;
}

// Show delay reason modal
function showDelayReasonModal(tripId, reason, detectedAt, reportedBy, minutes) {
    document.getElementById('delay-trip-id').textContent = tripId;
    document.getElementById('delay-reason-text').textContent = reason;
    document.getElementById('delay-detected-time').textContent = detectedAt;
    document.getElementById('delay-reported-by').textContent = reportedBy;
    document.getElementById('delay-minutes').textContent = minutes + ' minutes';
    
    const modal = document.getElementById('delay-reason-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Close delay reason modal
function closeDelayReasonModal() {
    const modal = document.getElementById('delay-reason-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('delay-reason-modal');
    if (e.target === modal) {
        closeDelayReasonModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDelayReasonModal();
    }
});

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