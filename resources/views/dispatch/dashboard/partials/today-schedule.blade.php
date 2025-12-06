<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Driver</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Vehicle</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Route</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($todaySchedule as $trip)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <i class="far fa-clock text-gray-400"></i> {{ $trip->scheduled_time->format('h:i A') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $trip->driver->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $trip->vehicle->plate_number }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $trip->deliveryRequest->client->name }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <div class="max-w-xs">
                        <i class="fas fa-map-marker-alt text-green-500"></i>
                        {{ Str::limit($trip->deliveryRequest->pickup_location, 20) }}
                        <br>
                        <i class="fas fa-flag-checkered text-red-500"></i>
                        {{ Str::limit($trip->deliveryRequest->delivery_location, 20) }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $trip->status === 'scheduled' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $trip->status === 'in-transit' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $trip->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                        {{ ucfirst($trip->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <a href="{{ route('trips.show', $trip) }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-eye"></i> View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    No trips scheduled for today
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($todaySchedule instanceof \Illuminate\Pagination\Paginator && $todaySchedule->hasPages())
<div class="p-6 border-t border-gray-200">
    <div class="flex items-center justify-center gap-3">
        @if($todaySchedule->onFirstPage())
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed text-sm">Previous</span>
        @else
        <a href="{{ $todaySchedule->previousPageUrl() }}" data-pagination="today-schedule"
            class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Previous
        </a>
        @endif

        @if($todaySchedule->hasMorePages())
        <a href="{{ $todaySchedule->nextPageUrl() }}" data-pagination="today-schedule"
            class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Next
        </a>
        @else
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed text-sm">Next</span>
        @endif
    </div>
</div>
@endif