<div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Mobile</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">License Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($drivers as $driver)
            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location.href='{{ route('drivers.show', $driver) }}'">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $driver->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <i class="fas fa-phone text-blue-500"></i> {{ $driver->mobile }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <i class="fas fa-id-card text-purple-500"></i> {{ $driver->license_number }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $driver->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $driver->status === 'on-trip' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $driver->status === 'off-duty' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst(str_replace('-', ' ', $driver->status)) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" onclick="event.stopPropagation()">
                    <div class="flex space-x-2">
                        <a href="{{ route('drivers.edit', $driver) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-medium">
                            Edit
                        </a>
                        @if(auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('drivers.destroy', $driver) }}" class="inline delete-form">
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
                    No drivers found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($drivers->hasPages())
<div class="px-6 py-4 bg-white border-t border-gray-200">
    {{ $drivers->links() }}
</div>
@endif