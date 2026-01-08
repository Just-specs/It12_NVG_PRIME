<div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Mobile</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">License Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Co-Drivers</th>
                
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase border-l-2 border-[#1E40AF]">Actions</th>
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
                <td class="px-6 py-4">
                    @php
                        $allCoDrivers = $driver->getAllCoDrivers();
                    @endphp
                    
                    @if($allCoDrivers->count() > 0)
                        <div class="flex flex-wrap gap-1">
                            @foreach($allCoDrivers as $coDriver)
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                    <i class="fas fa-user-friends mr-1"></i>{{ $coDriver->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-sm text-gray-400 italic">No co-drivers</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" onclick="event.stopPropagation()">
                    <div class="flex justify-center space-x-2">
                        <a href="{{ route('drivers.edit', $driver) }}" onclick="event.stopPropagation();" class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors" title="Edit Driver">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'head_dispatch')
                        <a href="{{ route('drivers.requestDelete', $driver) }}" onclick="event.stopPropagation();" class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors" title="Request Delete">
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
                    <p>No drivers found</p>
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
        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
            <i class="fas fa-chevron-left mr-1"></i> Previous
        </span>
        @else
        <a href="{{ $drivers->previousPageUrl() }}" data-pagination="drivers"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors">
            <i class="fas fa-chevron-left mr-1"></i> Previous
        </a>
        @endif

        @if($drivers->hasMorePages())
        <a href="{{ $drivers->nextPageUrl() }}" data-pagination="drivers"
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


