<div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Company</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Mobile</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
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
                    <div class="flex space-x-2">
                        <a href="{{ route('clients.edit', $client) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-medium">
                            Edit
                        </a>
                        @if(auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('clients.destroy', $client) }}" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-medium delete-btn">
                                Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    No clients found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($clients->hasPages())
<div class="px-6 py-4 bg-white border-t border-gray-200">
    {{ $clients->links() }}
</div>
@endif