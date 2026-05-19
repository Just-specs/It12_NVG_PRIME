@extends('layouts.app')

@section('title', 'Receipt Reports')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-receipt text-blue-600"></i> Receipt Reports
            </h1>
            <p class="text-gray-600 mt-1">Upload, open, and print trip receipts from the reports module.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">
            <i class="fas fa-arrow-left"></i>
            Back to Reports
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('reports.receipts') }}" class="flex flex-col gap-3 md:flex-row">
            <input type="text" name="search" value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search OR number, waybill, ATW, or client">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-1"></i>Search
            </button>
            @if(request('search'))
                <a href="{{ route('reports.receipts') }}" class="px-5 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trip</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client / ATW</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver / Vehicle</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receipt</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Upload</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($trips as $trip)
                        <tr>
                            <td class="px-4 py-4 align-top">
                                <a href="{{ route('trips.show', $trip) }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                    Trip #{{ $trip->id }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $trip->scheduled_time?->format('M d, Y h:i A') }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst(str_replace('-', ' ', $trip->status)) }}</p>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <p class="font-semibold text-gray-800">{{ $trip->deliveryRequest?->client?->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $trip->deliveryRequest?->atw_reference ?? 'No ATW reference' }}</p>
                                <p class="text-xs text-gray-500">Waybill: {{ $trip->waybill_number ?: 'N/A' }}</p>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <p class="text-sm text-gray-800">{{ $trip->driver?->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $trip->vehicle?->plate_number ?? 'N/A' }}</p>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <p class="font-semibold font-mono text-gray-800">{{ $trip->official_receipt_number ?: 'No OR number' }}</p>
                                @if($trip->receipt_url)
                                    <a href="{{ $trip->receipt_url }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 mt-1">
                                        <i class="fas fa-cloud-download-alt"></i>
                                        Open file
                                    </a>
                                @else
                                    <p class="text-sm text-gray-400 mt-1">No cloud file</p>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-top">
                                <form method="POST" action="{{ route('reports.receipts.update', $trip) }}" enctype="multipart/form-data" class="space-y-2">
                                    @csrf
                                    <input type="text" name="official_receipt_number" value="{{ old('official_receipt_number', $trip->official_receipt_number) }}" class="w-48 px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="OR number">
                                    <input type="file" name="receipt" accept="image/jpeg,image/png,image/webp,application/pdf" class="block w-56 text-sm">
                                    <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                        <i class="fas fa-cloud-upload-alt mr-1"></i>Save Receipt
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-4 align-top text-center">
                                <a href="{{ route('reports.receipts.print', $trip) }}" target="_blank" class="inline-flex items-center justify-center h-9 w-9 bg-gray-700 text-white rounded-full hover:bg-gray-800" title="Print receipt">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                                No trips found for receipt reporting.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trips->hasPages())
            <div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <span class="text-sm text-gray-600">
                    Showing {{ $trips->firstItem() }} to {{ $trips->lastItem() }} of {{ $trips->total() }} receipts
                </span>
                <div class="flex gap-3">
                    @if($trips->onFirstPage())
                        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-1"></i> Previous
                        </span>
                    @else
                        <a href="{{ $trips->previousPageUrl() }}"
                            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i> Previous
                        </a>
                    @endif

                    @if($trips->hasMorePages())
                        <a href="{{ $trips->nextPageUrl() }}"
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
    </div>
</div>
@endsection
