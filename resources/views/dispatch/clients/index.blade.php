@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-users text-blue-600"></i> Clients Management
        </h1>
        <a href="{{ route('clients.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus"></i> Add New Client
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Clients</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_clients'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Total Clients {{ $stats['total_clients'] }}</p>
                </div>
                <i class="fas fa-users text-5xl text-blue-500"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Clients</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['active_clients'] }}</p>
                </div>
                <i class="fas fa-user-check text-5xl text-green-500"></i>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Search Clients</h3>
        <div class="flex items-center gap-2">
            <div class="relative" style="width: 300px;">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                       name="search" 
                       id="client-search" 
                       class="w-full pl-10 pr-4 py-2 border border-blue-500 rounded-md bg-white placeholder-gray-400 focus:outline-none focus:ring-blue-400 focus:border-blue-600 transition duration-150 sm:text-sm" 
                       placeholder="Search..." 
                       value="{{ request('search') }}"
                       autocomplete="off">
            </div>
            <button type="button" 
                    id="clear-search" 
                    class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none border border-gray-300 rounded-md {{ !request('search') ? 'hidden' : '' }}">
                Clear
            </button>
        </div>
    </div>
    
    <!-- Clients Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Client List</h3>
            <div class="flex space-x-2">
                <a href="{{ route('clients.export-excel') }}" class="text-sm bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                    <i class="fas fa-file-excel"></i> Export
                </a>
            </div>
        </div>
        <div id="clients-table-container" data-url="{{ route('clients.index') }}">
            @include('dispatch.clients.partials.table', ['clients' => $clients])
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchForm = document.getElementById('client-search-form');
                const searchInput = document.getElementById('client-search');
                const clearSearchBtn = document.getElementById('clear-search');
                const clientsTable = document.getElementById('clients-table-container');
                
                // Debounce function to limit API calls
                function debounce(func, wait) {
                    let timeout;
                    return function executedFunction(...args) {
                        const later = () => {
                            clearTimeout(timeout);
                            func(...args);
                        };
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                    };
                }

                // Handle search input with debounce
                searchInput.addEventListener('input', debounce(function(e) {
                    const searchValue = e.target.value.trim();
                    if (searchValue.length === 0 || searchValue.length >= 2) {
                        fetchClients(searchValue);
                    }
                    toggleClearButton(searchValue);
                }, 300));

                // Handle clear search
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    fetchClients('');
                    toggleClearButton('');
                    searchInput.focus();
                });

                // Toggle clear button visibility
                function toggleClearButton(value) {
                    clearSearchBtn.classList.toggle('hidden', !value);
                }

                // Fetch clients via AJAX
                function fetchClients(search) {
                    const url = new URL(clientsTable.dataset.url, window.location.origin);
                    if (search) {
                        url.searchParams.set('search', search);
                    } else {
                        url.searchParams.delete('search');
                    }
                    url.searchParams.set('ajax', '1');

                    fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        clientsTable.innerHTML = data.html;
                        // Update URL without page reload
                        const newUrl = new URL(window.location.href);
                        if (search) {
                            newUrl.searchParams.set('search', search);
                        } else {
                            newUrl.searchParams.delete('search');
                        }
                        window.history.pushState({}, '', newUrl);
                    })
                    .catch(error => console.error('Error:', error));
                }

                // Handle browser back/forward buttons
                window.addEventListener('popstate', function() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const searchParam = urlParams.get('search') || '';
                    searchInput.value = searchParam;
                    toggleClearButton(searchParam);
                    fetchClients(searchParam);
                });
            });
        </script>
        @endpush
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const clientsContainer = document.getElementById('clients-table-container');

        if (!clientsContainer) {
            return;
        }

        const setClientsLoading = (isLoading) => {
            clientsContainer.classList.toggle('opacity-50', isLoading);
            clientsContainer.classList.toggle('pointer-events-none', isLoading);
        };

        const loadClientsPage = async (url) => {
            try {
                setClientsLoading(true);
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch clients page');
                }

                const data = await response.json();
                if (data.html) {
                    clientsContainer.innerHTML = data.html;
                }
            } catch (error) {
                console.error(error);
            } finally {
                setClientsLoading(false);
            }
        };

        clientsContainer.addEventListener('click', (event) => {
            const paginationLink = event.target.closest('a[data-pagination="clients"]');
            if (!paginationLink) {
                return;
            }

            event.preventDefault();
            loadClientsPage(paginationLink.href);
        });
    });
</script>
@endpush
@endsection