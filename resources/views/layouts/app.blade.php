<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dispatch System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            transition: margin-left 0.3s ease;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            transition: all 0.3s ease;
            width: 250px;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            margin-left: -250px;
        }

        .main-content {
            transition: all 0.3s ease;
            flex: 1;
        }

        .main-content.expanded {
            margin-left: 0;
        }
    </style>
    @stack('styles')
</head>

<body class="@yield('body-class', 'bg-gray-100')">
    <!-- Sidebar (only show when logged in) -->
    @auth
    <div id="sidebar" class="sidebar bg-blue-800 text-white shadow-lg">
        <!-- Logo -->
        <div class="p-4 border-b border-blue-700 flex justify-center">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center hover:opacity-80 transition">
                <img src="{{ asset('img/NVG_LOGO org.png') }}" alt="NVG Prime Movers logo" class="h-16 w-auto" />
            </a>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-b border-blue-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Dispatcher' }}</p>
                    <p class="text-xs text-blue-300 truncate">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="mt-6 px-3 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('dashboard') ? 'bg-white text-blue-800 shadow-sm' : 'text-white hover:bg-white hover:text-blue-800' }}">
                <i class="fas fa-chart-line w-5 {{ request()->routeIs('dashboard') ? 'text-blue-800' : 'text-blue-300 group-hover:text-blue-800' }}"></i>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>

            <!-- Delivery Requests -->
            <a href="{{ route('requests.index') }}" class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('requests.*') ? 'bg-white text-blue-800 shadow-sm' : 'text-white hover:bg-white hover:text-blue-800' }}">
                <i class="fas fa-clipboard-list w-5 {{ request()->routeIs('requests.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-blue-800' }}"></i>
                <span class="ml-3 font-medium">Requests</span>
            </a>

            <!-- Trips -->
            <a href="{{ route('trips.index') }}" class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('trips.*') ? 'bg-white text-blue-800 shadow-sm' : 'text-white hover:bg-white hover:text-blue-800' }}">
                <i class="fas fa-route w-5 {{ request()->routeIs('trips.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-blue-800' }}"></i>
                <span class="ml-3 font-medium">Trips</span>
            </a>

            <!-- Drivers -->
            <a href="{{ route('drivers.index') }}" class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('drivers.*') ? 'bg-white text-blue-800 shadow-sm' : 'text-white hover:bg-white hover:text-blue-800' }}">
                <i class="fas fa-user-tie w-5 {{ request()->routeIs('drivers.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-blue-800' }}"></i>
                <span class="ml-3 font-medium">Drivers</span>
            </a>

            <!-- Vehicles -->
            <a href="{{ route('vehicles.index') }}" class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('vehicles.*') ? 'bg-white text-blue-800 shadow-sm' : 'text-white hover:bg-white hover:text-blue-800' }}">
                <i class="fas fa-car w-5 {{ request()->routeIs('vehicles.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-blue-800' }}"></i>
                <span class="ml-3 font-medium">Vehicles</span>
            </a>

            <!-- Clients -->
            <a href="{{ route('clients.index') }}" class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('clients.*') ? 'bg-white text-blue-800 shadow-sm' : 'text-white hover:bg-white hover:text-blue-800' }}">
                <i class="fas fa-building w-5 {{ request()->routeIs('clients.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-blue-800' }}"></i>
                <span class="ml-3 font-medium">Clients</span>
            </a>

            <!-- Reports -->
            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('reports.*') ? 'bg-white text-blue-800 shadow-sm' : 'text-white hover:bg-white hover:text-blue-800' }}">
                <i class="fas fa-file-alt w-5 {{ request()->routeIs('reports.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-blue-800' }}"></i>
                <span class="ml-3 font-medium">Reports</span>
            </a>

            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}" class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('notifications.*') ? 'bg-white text-blue-800 shadow-sm' : 'text-white hover:bg-white hover:text-blue-800' }}">
                <i class="fas fa-bell w-5 {{ request()->routeIs('notifications.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-blue-800' }}"></i>
                <span class="ml-3 font-medium">Notifications</span>
            </a>
        </nav>

        <!-- Divider -->
        <div class="mt-6 mx-3 border-t border-blue-700"></div>

        <!-- Settings & Logout -->
        <nav class="mt-6 px-3 space-y-2 pb-6">
            <!-- Profile -->
            <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('profile.*') ? 'bg-white text-blue-800 shadow-sm' : 'text-white hover:bg-white hover:text-blue-800' }}">
                <i class="fas fa-user-circle w-5 {{ request()->routeIs('profile.*') ? 'text-blue-800' : 'text-blue-300 group-hover:text-blue-800' }}"></i>
                <span class="ml-3 font-medium">Profile</span>
            </a>

            <!-- Settings -->
            <a href="{{ route('utils.settings') }}" class="flex items-center px-4 py-3 rounded-lg transition group {{ request()->routeIs('utils.settings') ? 'bg-white text-blue-800 shadow-sm' : 'text-white hover:bg-white hover:text-blue-800' }}">
                <i class="fas fa-cog w-5 {{ request()->routeIs('utils.settings') ? 'text-blue-800' : 'text-blue-300 group-hover:text-blue-800' }}"></i>
                <span class="ml-3 font-medium">Settings</span>
            </a>

            <!-- Logout -->
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button id="logout-button" type="button" class="w-full flex items-center px-4 py-3 rounded-lg transition group text-white hover:bg-white hover:text-blue-800">
                    <i class="fas fa-sign-out-alt w-5 text-blue-300 group-hover:text-blue-800"></i>
                    <span class="ml-3 font-medium">Logout</span>
                </button>
            </form>
        </nav>
    </div>
    @endauth

    <!-- Flash Messages -->
    <div class="main-content">
        @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Main Content -->
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
                    <button id="confirm-logout" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Yes, log me out
                    </button>
                    <button id="cancel-logout" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        No, stay signed in
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const logoutForm = document.getElementById('logout-form');
            const logoutButton = document.getElementById('logout-button');
            const modal = document.getElementById('logout-confirmation');
            const confirmButton = document.getElementById('confirm-logout');
            const cancelButton = document.getElementById('cancel-logout');

            if (!logoutForm || !logoutButton || !modal || !confirmButton || !cancelButton) {
                return;
            }

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

            cancelButton.addEventListener('click', () => {
                hideModal();
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    hideModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    hideModal();
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>