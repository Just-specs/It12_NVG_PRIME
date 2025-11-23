@forelse($recentRequests as $request)
<div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
    <div class="flex justify-between items-start mb-3">
        <div>
            <p class="font-semibold text-gray-800">{{ $request->atw_reference }}</p>
            <p class="text-sm text-gray-600">{{ $request->container_size }} - {{ $request->container_type }}</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-semibold
            {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
            {{ $request->status === 'verified' ? 'bg-green-100 text-green-800' : '' }}
            {{ $request->status === 'assigned' ? 'bg-blue-100 text-blue-800' : '' }}
            {{ $request->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
            {{ ucfirst($request->status) }}
        </span>
    </div>

    <div class="space-y-2 text-sm">
        <div class="flex items-start">
            <i class="fas fa-map-marker-alt text-green-500 mt-1 mr-2"></i>
            <p class="text-gray-700">{{ Str::limit($request->pickup_location, 60) }}</p>
        </div>
        <div class="flex items-start">
            <i class="fas fa-flag-checkered text-red-500 mt-1 mr-2"></i>
            <p class="text-gray-700">{{ Str::limit($request->delivery_location, 60) }}</p>
        </div>
    </div>

    <div class="flex justify-between items-center mt-3 pt-3 border-t">
        <p class="text-xs text-gray-500">
            <i class="far fa-calendar-alt"></i>
            {{ optional($request->preferred_schedule)->format('M d, Y h:i A') ?? 'No schedule' }}
        </p>
        <a href="{{ route('requests.show', $request) }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-[#2563EB] rounded-full shadow-sm transition hover:bg-[#1D4ED8] focus:outline-none focus:ring-2 focus:ring-blue-300">
            View Details <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>
@empty
<p class="text-center py-8 text-gray-500">No requests yet</p>
@endforelse

@if($recentRequests instanceof \Illuminate\Pagination\Paginator && $recentRequests->hasPages())
<div class="mt-4 flex flex-col items-center gap-2">
    <div class="flex items-center gap-2">
        @if($recentRequests->onFirstPage())
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed text-sm font-medium">Previous</span>
        @else
        <a href="{{ $recentRequests->previousPageUrl() }}" data-pagination="client-recent-requests"
           class="px-4 py-2 rounded-md bg-[#2563EB] text-white text-sm font-medium shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Previous
        </a>
        @endif

        @if($recentRequests->hasMorePages())
        <a href="{{ $recentRequests->nextPageUrl() }}" data-pagination="client-recent-requests"
           class="px-4 py-2 rounded-md bg-[#2563EB] text-white text-sm font-medium shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Next
        </a>
        @else
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed text-sm font-medium">Next</span>
        @endif
    </div>

    <p class="text-xs text-gray-500">Page {{ $recentRequests->currentPage() }}</p>
</div>
@endif