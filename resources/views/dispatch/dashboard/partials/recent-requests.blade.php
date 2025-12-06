@forelse($recentRequests as $request)
<div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
    <div class="flex-1">
        <p class="font-semibold text-gray-800">{{ $request->client->name }}</p>
        <p class="text-sm text-gray-600">
            <i class="fas fa-file-alt"></i> {{ $request->atw_reference }}
        </p>
        <p class="text-xs text-gray-500 mt-1">
            <i class="fas fa-map-marker-alt"></i> {{ Str::limit($request->pickup_location, 5) }}
        </p>
    </div>
    <div class="text-right ml-4">
        <span class="px-3 py-1 rounded-full text-xs font-semibold
            {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
            {{ $request->status === 'verified' ? 'bg-green-100 text-green-800' : '' }}
            {{ $request->status === 'assigned' ? 'bg-blue-100 text-blue-800' : '' }}">
            {{ ucfirst($request->status) }}
        </span>
        <p class="text-xs text-gray-500 mt-1">{{ $request->created_at->diffForHumans() }}</p>
    </div>
</div>
@empty
<p class="text-gray-500 text-center py-4">No recent requests</p>
@endforelse

@if($recentRequests instanceof \Illuminate\Pagination\Paginator && $recentRequests->hasPages())
<div class="mt-4 flex items-center gap-3">
    @if($recentRequests->onFirstPage())
    <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed text-sm">Previous</span>
    @else
    <a href="{{ $recentRequests->previousPageUrl() }}" data-pagination="recent-requests"
        class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
        Previous
    </a>
    @endif

    @if($recentRequests->hasMorePages())
    <a href="{{ $recentRequests->nextPageUrl() }}" data-pagination="recent-requests"
        class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
        Next
    </a>
    @else
    <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed text-sm">Next</span>
    @endif
</div>
@endif