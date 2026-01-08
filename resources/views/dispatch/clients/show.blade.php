@extends('layouts.app')

@section('title', 'Client Details')

@section('content')
<div class="container mx-auto px-4 max-w-6xl">
    <div class="mb-6">
        <a href="{{ route('clients.index') }}" class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white bg-[#2563EB] rounded-full hover:bg-blue-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Info -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-blue-600 text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $client->name }}</h2>
                    @if($client->company)
                    <p class="text-gray-600 mt-1">{{ $client->company }}</p>
                    @endif
                </div>

                <div class="space-y-3 text-sm border-t pt-4">
                    @if($client->email)
                    <div>
                        <p class="text-gray-600">Email</p>
                        <p class="font-semibold">{{ $client->email }}</p>
                    </div>
                    @endif
                    @if($client->mobile)
                    <div>
                        <p class="text-gray-600">Mobile</p>
                        <p class="font-semibold">{{ $client->mobile }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-gray-600">Client Since</p>
                        <p class="font-semibold">{{ $client->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 mt-6 border-t pt-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_requests'] }}</p>
                        <p class="text-xs text-gray-600">Total Requests</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $stats['completed_requests'] }}</p>
                        <p class="text-xs text-gray-600">Completed</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_requests'] }}</p>
                        <p class="text-xs text-gray-600">Pending</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['active_trips'] }}</p>
                        <p class="text-xs text-gray-600">Active Trips</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 space-y-2 border-t pt-4">
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'head_dispatch' || auth()->user()->role === 'dispatcher')
                    <a href="{{ route('clients.edit', $client) }}" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                        <i class="fas fa-edit"></i> Edit Client
                    </a>
                    @endif
                    
                    <a href="{{ route('clients.requests', $client) }}" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                        <i class="fas fa-clipboard-list"></i> View All Requests
                    </a>
                    
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'head_dispatch')
        <a href="{{ route('clients.requestDelete', $client) }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-trash mr-2"></i>
            Request Delete
        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Requests -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-history text-blue-600"></i> Recent Requests
                </h3>

                <div id="client-recent-requests-container" class="space-y-4">
                    @include('dispatch.clients.partials.recent-requests', ['recentRequests' => $recentRequests])
                </div>

                @if($recentRequests->count() > 0)
                <div class="mt-6 text-center">
                    <a href="{{ route('clients.requests', $client) }}"
                        class="inline-flex items-center gap-2 px-6 py-2 text-sm font-semibold text-white bg-[#2563EB] rounded-full shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        View All Requests <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this client? This action cannot be undone.')) {
            document.getElementById('delete-client-form').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('#client-recent-requests-container');
        if (!container) {
            return;
        }

        const setLoadingState = (isLoading) => {
            container.classList.toggle('opacity-50', isLoading);
            container.classList.toggle('pointer-events-none', isLoading);
        };

        const loadRecentRequests = async (url) => {
            try {
                setLoadingState(true);
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                if (data.html) {
                    container.innerHTML = data.html;
                }
            } catch (error) {
                console.error('Failed to load client recent requests:', error);
            } finally {
                setLoadingState(false);
            }
        };

        container.addEventListener('click', (event) => {
            const paginationLink = event.target.closest('a[data-pagination=\"client-recent-requests\"]');
            if (!paginationLink) {
                return;
            }

            event.preventDefault();
            loadRecentRequests(paginationLink.href);
        });
    });
</script>
@endpush
