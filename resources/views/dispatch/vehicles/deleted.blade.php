@extends('layouts.app')

@section('title', 'Deleted Vehicles')

@section('content')
<div class="container mx-auto px-4 max-w-7xl">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-trash-restore text-red-600"></i> Deleted Vehicles
            </h1>
            <p class="text-gray-600 mt-2">View and restore deleted vehicles</p>
        </div>
        <a href="{{ route('vehicles.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-arrow-left"></i> Back to Vehicles
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plate Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trailer Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deleted At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deleted By</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($vehicles as $vehicle)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">#{{ $vehicle->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $vehicle->plate_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $vehicle->vehicle_type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $vehicle->trailer_type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $vehicle->deleted_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($vehicle->deletedBy)
                                <span class="text-blue-600">{{ $vehicle->deletedBy->name }}</span>
                            @else
                                <span class="text-gray-400">Unknown</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <form action="{{ route('vehicles.restore', $vehicle->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                                        onclick="return confirm('Are you sure you want to restore this vehicle?')">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No deleted vehicles found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vehicles->hasPages())
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            {{ $vehicles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
