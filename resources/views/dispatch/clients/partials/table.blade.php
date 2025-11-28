<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Contact</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Company</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Total Requests</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Completed</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($clients as $client)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $client->name }}</p>
                            <p class="text-xs text-gray-500">Added {{ $client->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($client->email)
                    <p class="text-gray-900"><i class="fas fa-envelope"></i> {{ $client->email }}</p>
                    @endif
                    @if($client->mobile)
                    <p class="text-gray-600"><i class="fas fa-phone"></i> {{ $client->mobile }}</p>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $client->company ?? '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="text-xl font-bold text-blue-600">{{ $client->delivery_requests_count }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="text-xl font-bold text-green-600">{{ $client->completed_requests ?? 0 }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex space-x-2">
                        <a href="{{ route('clients.show', $client) }}" class="text-blue-600 hover:text-blue-800" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('clients.edit', $client) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('clients.requests', $client) }}" class="text-purple-600 hover:text-purple-800" title="Requests">
                            <i class="fas fa-clipboard-list"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    No clients found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($clients->hasPages())
<div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <span class="text-sm text-gray-600">
        Showing {{ $clients->firstItem() }} to {{ $clients->lastItem() }} of {{ $clients->total() }} clients
    </span>
    <div class="flex gap-3">
        @if($clients->onFirstPage())
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Previous</span>
        @else
        <a href="{{ $clients->previousPageUrl() }}" data-pagination="clients"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Previous
        </a>
        @endif

        @if($clients->hasMorePages())
        <a href="{{ $clients->nextPageUrl() }}" data-pagination="clients"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Next
        </a>
        @else
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
        @endif
    </div>
</div>
@endif