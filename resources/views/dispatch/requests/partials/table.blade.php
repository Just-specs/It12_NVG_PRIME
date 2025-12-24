<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">ATW Reference</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Container</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Shipping Line</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Pickup Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Delivery Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Schedule</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($requests as $request)
            <tr class="hover:bg-gray-50" data-request-id="{{ $request->id }}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $request->client->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $request->atw_reference }}</div>
                    @if($request->atw_verified)
                    <span class="text-xs text-green-600"><i class="fas fa-check-circle"></i> Verified</span>
                    @else
                    <span class="text-xs text-yellow-600"><i class="fas fa-exclamation-circle"></i> Pending</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <div>{{ $request->container_size }} {{ $request->container_type }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    @if($request->shipping_line)
                    <div class="text-blue-600"><i class="fas fa-ship"></i> {{ $request->shipping_line }}</div>
                    @else
                    <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <i class="fas fa-map-marker-alt text-green-500"></i>
                    {{ Str::limit($request->pickup_location, 30) }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <i class="fas fa-flag-checkered text-red-500"></i>
                    {{ Str::limit($request->delivery_location, 30) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $request->preferred_schedule->format('M d, h:i A') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $request->status === 'verified' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $request->status === 'assigned' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $request->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex space-x-2">
                        @if($request->status === 'verified' && !$request->trip)
                        <a href="{{ route('requests.show', $request) }}" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs font-medium" title="Assign Driver">
                            <i class="fas fa-user-plus"></i> Assign
                        </a>
                        @endif
                        <a href="{{ route('requests.show', $request) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-medium" title="View Details">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                    No delivery requests found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($requests->hasPages())
<div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <span class="text-sm text-gray-600">
        Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} of {{ $requests->total() }} requests
    </span>
    <div class="flex gap-3">
        @if($requests->onFirstPage())
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Previous</span>
        @else
        <a href="{{ $requests->previousPageUrl() }}" data-pagination="requests"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Previous
        </a>
        @endif

        @if($requests->hasMorePages())
        <a href="{{ $requests->nextPageUrl() }}" data-pagination="requests"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Next
        </a>
        @else
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
        @endif
    </div>
</div>
@endif
