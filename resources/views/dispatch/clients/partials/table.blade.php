<div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Company</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Mobile</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Email</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($clients as $client)
            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location.href='{{ route('clients.show', $client) }}'">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $client->company ?? '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <i class="fas fa-phone text-blue-500"></i> {{ $client->mobile ?? '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <i class="fas fa-envelope text-purple-500"></i> {{ $client->email ?? '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" onclick="event.stopPropagation()">
                    <div class="flex justify-center items-center space-x-3">
                        <!-- Edit Icon -->
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'head_dispatch' || auth()->user()->role === 'dispatcher')
                        <a href="{{ route('clients.edit', $client) }}" 
                           class="text-blue-600 hover:text-blue-800 transition-colors" 
                           title="Edit Client">
                            <i class="fas fa-edit text-lg"></i>
                        </a>
                        @endif
                        
                        <!-- Delete Icon -->
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'head_dispatch')
                        <a href="{{ route('clients.requestDelete', $client) }}" onclick="event.stopPropagation();" class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors" title="Request Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-users text-4xl mb-2 text-gray-300"></i>
                    <p>No clients found</p>
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
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
            <i class="fas fa-chevron-left mr-1"></i> Previous
        </span>
        @else
        <a href="{{ $clients->previousPageUrl() }}" data-pagination="clients"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors">
            <i class="fas fa-chevron-left mr-1"></i> Previous
        </a>
        @endif

        @if($clients->hasMorePages())
        <a href="{{ $clients->nextPageUrl() }}" data-pagination="clients"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors">
            Next <i class="fas fa-chevron-right ml-1"></i>
        </a>
        @else
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
            Next <i class="fas fa-chevron-right ml-1"></i>
        </span>
        @endif
    </div>
</div>
@endif


