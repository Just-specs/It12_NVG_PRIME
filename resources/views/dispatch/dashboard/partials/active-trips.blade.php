@forelse($activeTrips as $trip)
<div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
    <div class="flex-1">
        <p class="font-semibold text-gray-800">
            @if($trip->driver)
                {{ $trip->driver->name }}
            @else
                <span class="text-red-500">Driver Deleted</span>
            @endif
        </p>
        <p class="text-sm text-gray-600">
            <i class="fas fa-truck"></i> 
            @if($trip->vehicle)
                {{ $trip->vehicle ? $trip->vehicle->plate_number : 'N/A' }}
            @else
                <span class="text-red-500">Vehicle Deleted</span>
            @endif
        </p>
        <p class="text-xs text-gray-500 mt-1">
            Client: 
            @if($trip->deliveryRequest && $trip->deliveryRequest->client)
                {{ $trip->deliveryRequest?->client?->name ?? 'N/A' }}
            @else
                <span class="text-red-500">Client Deleted</span>
            @endif
        </p>
    </div>
    <div class="text-right ml-4">
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
            <i class="fas fa-circle animate-pulse"></i> In Transit
        </span>
        <p class="text-xs text-gray-500 mt-1">{{ $trip->scheduled_time->format('h:i A') }}</p>
    </div>
</div>
@empty
<p class="text-gray-500 text-center py-4">No active trips</p>
@endforelse

@if($activeTrips instanceof \Illuminate\Pagination\Paginator && $activeTrips->hasPages())
<div class="mt-4 flex items-center gap-3">
    @if($activeTrips->onFirstPage())
    <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed text-sm">Previous</span>
    @else
    <a href="{{ $activeTrips->previousPageUrl() }}" data-pagination="active-trips"
        class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
        Previous
    </a>
    @endif

    @if($activeTrips->hasMorePages())
    <a href="{{ $activeTrips->nextPageUrl() }}" data-pagination="active-trips"
        class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
        Next
    </a>
    @else
    <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed text-sm">Next</span>
    @endif
</div>
@endif