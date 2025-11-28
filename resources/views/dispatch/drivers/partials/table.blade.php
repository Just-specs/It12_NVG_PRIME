<div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">License Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Mobile</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Total Trips</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($drivers as $driver)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $driver->name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $driver->license_number }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-phone"></i> {{ $driver->mobile }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-blue-600">{{ $driver->trips_count }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $driver->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $driver->status === 'on-trip' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $driver->status === 'off-duty' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst(str_replace('-', ' ', $driver->status)) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex space-x-2">
                        <button type="button"
                            class="text-blue-600 hover:text-blue-800 view-driver-btn"
                            title="View"
                            data-driver-id="{{ $driver->id }}"
                            data-driver-name="{{ $driver->name }}"
                            data-license-number="{{ $driver->license_number }}"
                            data-mobile="{{ $driver->mobile }}"
                            data-status="{{ $driver->status }}"
                            data-trips-count="{{ $driver->trips_count }}"
                            data-created-at="{{ $driver->created_at->format('M d, Y') }}"
                            data-view-url="{{ route('drivers.show', $driver) }}"
                            data-status-url="{{ route('drivers.update-status', $driver) }}">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if($driver->status !== 'on-trip')
                        <form method="POST" action="{{ route('drivers.update-status', $driver) }}" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="{{ $driver->status === 'available' ? 'off-duty' : 'available' }}">
                            <button type="submit" 
                                class="text-{{ $driver->status === 'available' ? 'gray' : 'green' }}-600 hover:text-{{ $driver->status === 'available' ? 'gray' : 'green' }}-800" 
                                title="{{ $driver->status === 'available' ? 'Set Off-Duty' : 'Set Available' }}">
                                <i class="fas fa-{{ $driver->status === 'available' ? 'user-slash' : 'user-check' }}"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    No drivers found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($drivers->hasPages())
<div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <span class="text-sm text-gray-600">
        Showing {{ $drivers->firstItem() }} to {{ $drivers->lastItem() }} of {{ $drivers->total() }} drivers
    </span>
    <div class="flex gap-3">
        @if($drivers->onFirstPage())
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Previous</span>
        @else
        <a href="{{ $drivers->previousPageUrl() }}" data-pagination="drivers"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Previous
        </a>
        @endif

        @if($drivers->hasMorePages())
        <a href="{{ $drivers->nextPageUrl() }}" data-pagination="drivers"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
            Next
        </a>
        @else
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
        @endif
    </div>
</div>
@endif
