@extends('layouts.app')

@section('title', 'Deletion Requests')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-trash-alt text-red-600"></i> Deletion Requests
        </h1>
        <div class="flex items-center gap-4">
            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-semibold">
                <i class="fas fa-clock"></i> {{ $stats['pending'] }} Pending
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-600 font-medium">Pending</p>
                    <p class="text-2xl font-bold text-yellow-700">{{ $stats['pending'] }}</p>
                </div>
                <i class="fas fa-clock text-3xl text-yellow-500"></i>
            </div>
        </div>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600 font-medium">Approved</p>
                    <p class="text-2xl font-bold text-green-700">{{ $stats['approved'] }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-600 font-medium">Rejected</p>
                    <p class="text-2xl font-bold text-red-700">{{ $stats['rejected'] }}</p>
                </div>
                <i class="fas fa-times-circle text-3xl text-red-500"></i>
            </div>
        </div>
    </div>

    <!-- Deletion Requests Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Requested By
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Resource Type
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Resource Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Reason
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $request)
                <tr class="hover:bg-gray-50 cursor-pointer" data-request-id="{{ $request->id }}" data-url="{{ route('deletion-requests.show', $request) }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <i class="fas fa-user text-gray-400 mr-2"></i>
                            <span class="text-sm font-medium text-gray-900">{{ $request->requestedBy->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @if($request->resource_type === 'driver') bg-blue-100 text-blue-800
                            @elseif($request->resource_type === 'vehicle') bg-purple-100 text-purple-800
                            @elseif($request->resource_type === 'client') bg-green-100 text-green-800
                            @else bg-orange-100 text-orange-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $request->resource_type)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $request->resource_name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                        {{ $request->reason }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($request->status === 'pending')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        @elseif($request->status === 'approved')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check"></i> Approved
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times"></i> Rejected
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $request->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex gap-2" onclick="event.stopPropagation()">
                            @if($request->status === 'pending')
                                <button type="button" 
                                        onclick="showApproveModal({{ $request->id }}, '{{ str_replace("'", "\\'", $request->resource_name) }}')"
                                        class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-1"></i> Approve
                                </button>
                                <button type="button" 
                                        onclick="showRejectModal({{ $request->id }}, '{{ str_replace("'", "\\'", $request->resource_name) }}')"
                                        class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-1"></i> Reject
                                </button>
                            @else
                                <span class="text-sm text-gray-500 italic">{{ ucfirst($request->status) }}</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>No deletion requests found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
    <div class="mt-6">
        {{ $requests->links() }}
    </div>
    @endif
</div>

<!-- Approve Modal -->
<div id="approve-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-check-circle text-green-600"></i> Approve Deletion Request
        </h3>
        <p class="mb-4 text-gray-700">
            Are you sure you want to approve the deletion of <strong id="approve-resource-name"></strong>?
        </p>
        <form id="approve-form" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Review Notes (Optional)</label>
                <textarea name="review_notes" rows="3" 
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Add any notes about this approval..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeApproveModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-check"></i> Approve & Delete
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-times-circle text-red-600"></i> Reject Deletion Request
        </h3>
        <p class="mb-4 text-gray-700">
            Please provide a reason for rejecting the deletion of <strong id="reject-resource-name"></strong>:
        </p>
        <form id="reject-form" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                <textarea name="review_notes" rows="3" required
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Explain why this deletion is being rejected..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-times"></i> Reject Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showApproveModal(requestId, resourceName) {
    const modal = document.getElementById('approve-modal');
    const form = document.getElementById('approve-form');
    const nameElement = document.getElementById('approve-resource-name');
    
    nameElement.textContent = resourceName;
    form.action = '/deletion-requests/' + requestId + '/approve';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    console.log('Opening approve modal for request ID:', requestId);
    console.log('Form action:', form.action);
}

function closeApproveModal() {
    const modal = document.getElementById('approve-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function showRejectModal(requestId, resourceName) {
    const modal = document.getElementById('reject-modal');
    const form = document.getElementById('reject-form');
    const nameElement = document.getElementById('reject-resource-name');
    
    nameElement.textContent = resourceName;
    form.action = '/deletion-requests/' + requestId + '/reject';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    console.log('Opening reject modal for request ID:', requestId);
    console.log('Form action:', form.action);
}

function closeRejectModal() {
    const modal = document.getElementById('reject-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    const approveModal = document.getElementById('approve-modal');
    const rejectModal = document.getElementById('reject-modal');
    
    if (e.target === approveModal) {
        closeApproveModal();
    }
    if (e.target === rejectModal) {
        closeRejectModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApproveModal();
        closeRejectModal();
    }
});
// Handle double-click on table rows for viewing details
document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('tbody tr[data-request-id]');
    
    tableRows.forEach(row => {
        row.addEventListener('dblclick', function(e) {
            // Don't navigate if clicking on buttons
            if (e.target.closest('button')) {
                return;
            }
            
            const url = this.dataset.url;
            if (url) {
                window.location.href = url;
            }
        });
    });
});

</script>
@endsection
