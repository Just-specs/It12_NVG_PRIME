<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dispatch System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Roboto', sans-serif;
        }
        
        body {
            transition: margin-left 0.3s ease;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            transition: all 0.3s ease;
            width: 250px;
            overflow-y: auto;
            z-index: 40;
        }

        .sidebar.collapsed {
            margin-left: -250px;
        }

        .main-content {
            transition: all 0.3s ease;
            flex: 1;
            margin-left: 250px;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(0);
            }

            .sidebar.collapsed {
                transform: translateX(-100%);
                margin-left: 0;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }
        }

        /* Toast Animations */
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast-notification {
            animation: slideIn 0.3s ease-out;
        }

        .toast-notification.hiding {
            animation: slideOut 0.3s ease-in forwards;
        }
        
        /* Table Styles for Better Readability */
        table {
            font-family: 'Roboto', sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        
        table thead {
            background-color: #f8fafc;
        }
        
        table thead th {
            font-family: 'Roboto', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: 0.025em;
            padding: 0.75rem 1rem;
            text-align: left;
            color: #1e293b;
            border-bottom: 2px solid #e2e8f0;
        }
        
        table tbody td {
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
            font-size: 0.875rem;
            padding: 0.875rem 1rem;
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
            line-height: 1.5;
        }
        
        table tbody tr {
            transition: background-color 0.15s ease;
        }
        
        table tbody tr:hover {
            background-color: #f1f5f9;
        }
        
        /* Badge styles in tables */
        table .badge, table .status-badge {
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            display: inline-block;
            letter-spacing: 0.025em;
        }
        
        /* Button styles in tables */
        table button, table .btn {
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        /* Improve number readability */
        table td[class*='text-right'], table th[class*='text-right'] {
            font-variant-numeric: tabular-nums;
        }
        
        /* Headings - Improved Readability */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
            line-height: 1.3;
            letter-spacing: -0.01em;
        }
        
        h1 { font-size: 2rem; font-weight: 700; }
        h2 { font-size: 1.5rem; font-weight: 600; }
        h3 { font-size: 1.25rem; font-weight: 600; }
        h4 { font-size: 1.125rem; font-weight: 500; }
        
        /* Form Elements - Better Readability */
        input, textarea, select {
            font-family: 'Roboto', sans-serif;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        
        label {
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        /* Buttons - Consistent Typography */
        button, .btn {
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
            letter-spacing: 0.025em;
        }
        
        /* Cards - Better Text Hierarchy */
        .card-title {
            font-family: 'Roboto', sans-serif;
            font-weight: 600;
            font-size: 1.125rem;
        }
        
        .card-text {
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
            line-height: 1.6;
        }
        
        /* Small Text - Readable */
        small, .text-sm {
            font-size: 0.875rem;
            line-height: 1.5;
        }
        
        .text-xs {
            font-size: 0.75rem;
            line-height: 1.4;
        }
    </style>
    @stack('styles')
</head>

<body class="@yield('body-class', 'bg-gray-100')">
    <!-- Sidebar (only show when logged in) -->
    @auth
    <div id="sidebar" class="sidebar bg-gradient-to-b from-blue-800 to-blue-900 text-white shadow-2xl">
        <!-- Logo & Header -->
        <div class="p-4 border-b border-blue-700">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center hover:opacity-80 transition">
                <img src="{{ asset('img/NVG_LOGO_org.png') }}" alt="NVG Prime Movers logo" class="h-20 w-20" />
            </a>
            <div class="text-center mt-3">
                <h2 class="text-lg font-bold">NVG PRIME MOVERS</h2>

            </div>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-b border-blue-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-xs text-blue-300 truncate">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 bg-blue-600 text-white text-xs rounded-full">
                        {{ ucfirst(auth()->user()->role ?? 'dispatcher') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="mt-6 px-3 space-y-2">
            <!-- Dashboard - Available to ALL -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-between px-4 py-3 rounded-lg transition group {{ request()->routeIs('dashboard') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-home w-5 {{ request()->routeIs('dashboard') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                    <span class="ml-3 font-medium">Dashboard</span>
                </div>
            </a>

            <!-- Delivery Requests - Available to ALL -->
            <a href="{{ route('requests.index') }}"
                class="flex items-center justify-between px-4 py-3 rounded-lg transition group {{ request()->routeIs('requests.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-clipboard-list w-5 {{ request()->routeIs('requests.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                    <span class="ml-3 font-medium">Requests</span>
                </div>
                @php
                $pendingRequests = \App\Models\DeliveryRequest::where('status', 'pending')->count();
                @endphp
                @if($pendingRequests > 0)
                <span class="bg-[#2563EB] text-white text-xs font-bold px-2 py-1 rounded-full">
                    {{ $pendingRequests }}
                </span>
                @endif
            </a>

            <!-- Trips - Available to ALL -->
            <a href="{{ route('trips.index') }}"
                class="flex items-center justify-between px-4 py-3 rounded-lg transition group {{ request()->routeIs('trips.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-route w-5 {{ request()->routeIs('trips.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                    <span class="ml-3 font-medium">Trips</span>
                </div>
                @php
                $activeTrips = \App\Models\Trip::whereIn('status', ['scheduled', 'in-transit'])->count();
                @endphp
                @if($activeTrips > 0)
                <span class="bg-[#1E40AF] text-white text-xs font-bold px-2 py-1 rounded-full">
                    {{ $activeTrips }}
                </span>
                @endif
            </a>

            <!-- Notifications - Available to ALL -->
            <a href="{{ route('notifications.index') }}"
                class="flex items-center justify-between px-4 py-3 rounded-lg transition group {{ request()->routeIs('notifications.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-bell w-5 {{ request()->routeIs('notifications.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                    <span class="ml-3 font-medium">Notifications</span>
                </div>
                @php
                $unsentNotifications = \App\Models\ClientNotification::where('sent', false)->count();
                @endphp
                @if($unsentNotifications > 0)
                <span class="bg-[#2563EB] text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">
                    {{ $unsentNotifications }}
                </span>
                @endif
            </a>

            <!-- Reports - Available to ALL -->
            <a href="{{ route('reports.index') }}"
                class="flex items-center px-4 py-2 rounded-lg transition group {{ request()->routeIs('reports.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <i class="fas fa-file-alt w-5 {{ request()->routeIs('reports.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                <span class="ml-3 font-medium">Reports</span>
            </a>

            <!-- Drivers - Available to Head Dispatcher (Admin) -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'head_dispatch')
            <a href="{{ route('drivers.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('drivers.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <i class="fas fa-user-tie w-5 {{ request()->routeIs('drivers.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                <span class="ml-3 font-medium">Drivers</span>
            </a>

            <!-- Co-Drivers -->
            <a href="{{ route('co-drivers.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('co-drivers.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <i class="fas fa-users w-5 {{ request()->routeIs('co-drivers.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                <span class="ml-3 font-medium">Co-Drivers</span>
            </a>
            @endif
            <!-- Vehicles - Available to Head Dispatcher (Admin) -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'head_dispatch')
            <a href="{{ route('vehicles.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('vehicles.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <i class="fas fa-truck w-5 {{ request()->routeIs('vehicles.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                <span class="ml-3 font-medium">Vehicles</span>
            </a>
            @endif

            <!-- Clients - Available to Head Dispatcher (Admin) -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'head_dispatch')
            <a href="{{ route('clients.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('clients.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <i class="fas fa-building w-5 {{ request()->routeIs('clients.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                <span class="ml-3 font-medium">Clients</span>
            </a>
            @endif

            <!-- ADMIN ONLY SECTION -->
            @if(auth()->user()->role === 'admin')
            <div class="mt-6 pt-4 border-t border-blue-700">
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">
                    <i class="fas fa-shield-alt"></i> Admin Only
                </p>
                
                <!-- Deletion Requests -->
                <a href="{{ route('deletion-requests.index') }}"
                    class="flex items-center justify-between px-4 py-3 rounded-lg transition group {{ request()->routeIs('deletion-requests.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                    <div class="flex items-center">
                        <i class="fas fa-trash-alt w-5 {{ request()->routeIs('deletion-requests.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                        <span class="ml-3 font-medium">Deletion Requests</span>
                    </div>
                    @php
                    $pendingDeletions = \App\Models\DeletionRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingDeletions > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">
                        {{ $pendingDeletions }}
                    </span>
                    @endif
                </a>
                
                <!-- Audit Logs -->
                <a href="{{ route('admin.audit-logs.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('admin.audit-logs.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                    <i class="fas fa-history w-5 {{ request()->routeIs('admin.audit-logs.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                    <span class="ml-3 font-medium">Audit Logs</span>
                </a>

                <!-- Deleted Records -->
                <div class="mt-2">
                    <button onclick="toggleDeletedMenu()" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition group text-white hover:bg-blue-700">
                        <div class="flex items-center">
                            <i class="fas fa-trash-restore w-5 text-blue-300 group-hover:text-white"></i>
                            <span class="ml-3 font-medium">Deleted Records</span>
                        </div>
                        <i id="deleted-chevron" class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div id="deleted-submenu" class="hidden mt-1 ml-8 space-y-1">
                        <a href="{{ route('drivers.deleted') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-blue-700">
                            <i class="fas fa-user-tie w-4"></i> Deleted Drivers
                        </a>
                        <a href="{{ route('vehicles.deleted') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-blue-700">
                            <i class="fas fa-truck w-4"></i> Deleted Vehicles
                        </a>
                        <a href="{{ route('clients.deleted') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-blue-700">
                            <i class="fas fa-building w-4"></i> Deleted Clients
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Dispatchers - ADMIN ONLY (Full Admin Control) -->
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dispatchers.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('admin.dispatchers.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <i class="fas fa-users-cog w-5 {{ request()->routeIs('admin.dispatchers.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                <span class="ml-3 font-medium">Dispatchers</span>
            </a>
            @endif
        </nav>

        <!-- Divider -->
        <div class="mt-6 mx-3 border-t border-blue-700"></div>

        <!-- Settings & Logout -->
        <nav class="mt-6 px-3 space-y-2 pb-6">
            <!-- Profile -->
            <a href="{{ route('profile.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('profile.*') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <i class="fas fa-user-circle w-5 {{ request()->routeIs('profile.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                <span class="ml-3 font-medium">Profile</span>
            </a>

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'head_dispatch')
            <!-- Settings - ADMIN ONLY -->
            <a href="{{ route('utils.settings') }}"
                class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('utils.settings') ? 'bg-white text-blue-800 shadow-lg' : 'text-white hover:bg-blue-700' }}">
                <i class="fas fa-cog w-5 {{ request()->routeIs('utils.settings') ? 'text-blue-800' : 'text-blue-300 group-hover:text-white' }}"></i>
                <span class="ml-3 font-medium">Settings</span>
            </a>
            @endif

            <!-- Logout -->
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button id="logout-button" type="button"
                    class="w-full flex items-center px-4 py-3 rounded-lg transition group text-white hover:bg-red-600">
                    <i class="fas fa-sign-out-alt w-5 text-blue-300 group-hover:text-white"></i>
                    <span class="ml-3 font-medium">Logout</span>
                </button>
            </form>
        </nav>
    </div>
    @endauth

    <!-- Mobile Sidebar Toggle -->
    @auth
    <button id="mobile-sidebar-toggle"
        class="md:hidden fixed top-4 left-4 z-50 bg-blue-800 text-white p-3 rounded-lg shadow-lg">
        <i class="fas fa-bars"></i>
    </button>
    @endauth

    <!-- Main Content -->
    <div class="main-content">
        <!-- Toast Notifications Container -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2" style="max-width: 400px;">
            @if(session('success'))
            <div class="toast-notification bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between transform transition-all duration-300 ease-in-out" style="animation: slideIn 0.3s ease-out;">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-2xl mr-3"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="toast-notification bg-red-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between transform transition-all duration-300 ease-in-out" style="animation: slideIn 0.3s ease-out;">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            @endif

            @if(session('info'))
            <div class="toast-notification bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between transform transition-all duration-300 ease-in-out" style="animation: slideIn 0.3s ease-out;">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-2xl mr-3"></i>
                    <span class="font-medium">{{ session('info') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            @endif

            @if(session('warning'))
            <div class="toast-notification bg-yellow-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between transform transition-all duration-300 ease-in-out" style="animation: slideIn 0.3s ease-out;">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
                    <span class="font-medium">{{ session('warning') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <main class="py-6">
            @yield('content')
        </main>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logout-confirmation" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-sm w-full mx-4">
            <div class="px-6 py-5 text-center">
                <div class="text-3xl text-blue-600 mb-4">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Are you sure you want to log out?</h3>
                <p class="text-sm text-gray-500 mb-6">You can stay signed in to continue managing dispatch operations.</p>
                <div class="flex justify-center space-x-3">
                    <button id="cancel-logout" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </button>
                    <button id="confirm-logout" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Yes, Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Auto-dismiss toast notifications
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(toast => {
                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    toast.classList.add('hiding');
                    setTimeout(() => {
                        toast.remove();
                    }, 300); // Wait for animation to complete
                }, 5000);
            });

            // Logout confirmation
            const logoutForm = document.getElementById('logout-form');
            const logoutButton = document.getElementById('logout-button');
            const modal = document.getElementById('logout-confirmation');
            const confirmButton = document.getElementById('confirm-logout');
            const cancelButton = document.getElementById('cancel-logout');

            if (logoutForm && logoutButton && modal && confirmButton && cancelButton) {
                const showModal = () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    confirmButton.focus({
                        preventScroll: true
                    });
                };

                const hideModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    logoutButton.focus({
                        preventScroll: true
                    });
                };

                logoutButton.addEventListener('click', (event) => {
                    event.preventDefault();
                    showModal();
                });

                confirmButton.addEventListener('click', () => {
                    hideModal();
                    logoutForm.submit();
                });

                cancelButton.addEventListener('click', hideModal);

                modal.addEventListener('click', (event) => {
                    if (event.target === modal) hideModal();
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        hideModal();
                    }
                });
            }

            // Toggle deleted records submenu
            window.toggleDeletedMenu = function() {
                const submenu = document.getElementById('deleted-submenu');
                const chevron = document.getElementById('deleted-chevron');
                submenu.classList.toggle('hidden');
                chevron.classList.toggle('fa-chevron-down');
                chevron.classList.toggle('fa-chevron-up');
            };

            // Mobile sidebar toggle
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.getElementById('mobile-sidebar-toggle');

            if (sidebar && mobileToggle) {
                mobileToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('collapsed');
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', (event) => {
                    if (window.innerWidth < 768) {
                        if (!sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                            sidebar.classList.add('collapsed');
                        }
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>








