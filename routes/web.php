<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryRequestController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes - Container Dispatch Management System
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// ============================================
// PUBLIC ROUTES
// ============================================

Route::get('/', function () {
    return redirect()->route('login');
});

// ============================================
// AUTHENTICATION ROUTES
// ============================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
| All routes below require authentication
*/

Route::middleware(['auth'])->group(function () {

    // ============================================
    // DASHBOARD
    // ============================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard/recent-requests', [DashboardController::class, 'recentRequests'])->name('dashboard.recent-requests');

    // AJAX Dashboard Data
    Route::get('/ajax/dashboard-stats', [DashboardController::class, 'getStats'])->name('ajax.dashboard-stats');

    // ============================================
    // DELIVERY REQUESTS
    // ============================================
    Route::prefix('requests')->name('requests.')->group(function () {
        // Standard CRUD Operations
        Route::get('/', [DeliveryRequestController::class, 'index'])->name('index');
        Route::get('/create', [DeliveryRequestController::class, 'create'])->name('create');
        Route::post('/', [DeliveryRequestController::class, 'store'])->name('store');
        Route::get('/{request}', [DeliveryRequestController::class, 'show'])->name('show');
        Route::get('/{request}/edit', [DeliveryRequestController::class, 'edit'])->name('edit');
        Route::put('/{request}', [DeliveryRequestController::class, 'update'])->name('update');
        Route::delete('/{request}', [DeliveryRequestController::class, 'destroy'])->name('destroy');

        // Custom Actions
        Route::post('/{request}/verify', [DeliveryRequestController::class, 'verify'])->name('verify');
        Route::post('/{request}/cancel', [DeliveryRequestController::class, 'cancel'])->name('cancel');

        // Filtering & Search
        Route::get('/status/{status}', [DeliveryRequestController::class, 'filterByStatus'])->name('filter-status');
        Route::get('/search/query', [DeliveryRequestController::class, 'search'])->name('search');

        // Export Functions
        Route::get('/export/excel', [DeliveryRequestController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export/pdf', [DeliveryRequestController::class, 'exportPdf'])->name('export-pdf');

        // Import Functions
        Route::get('/import', [DeliveryRequestController::class, 'importForm'])->name('import-form');
        Route::post('/import', [DeliveryRequestController::class, 'importRequests'])->name('import');
    });

    // ============================================
    // TRIPS MANAGEMENT
    // ============================================
    Route::prefix('trips')->name('trips.')->group(function () {
        // Standard CRUD Operations
        Route::get('/', [TripController::class, 'index'])->name('index');
        Route::get('/create', [TripController::class, 'create'])->name('create');
        Route::post('/', [TripController::class, 'store'])->name('store');
        Route::get('/{trip}', [TripController::class, 'show'])->name('show');
        Route::get('/{trip}/edit', [TripController::class, 'edit'])->name('edit');
        Route::put('/{trip}', [TripController::class, 'update'])->name('update');
        Route::delete('/{trip}', [TripController::class, 'destroy'])->name('destroy');

        // Trip Status Management
        Route::post('/{trip}/start', [TripController::class, 'startTrip'])->name('start');
        Route::post('/{trip}/complete', [TripController::class, 'completeTrip'])->name('complete');
        Route::post('/{trip}/cancel', [TripController::class, 'cancelTrip'])->name('cancel');
        Route::post('/{trip}/update-status', [TripController::class, 'updateStatus'])->name('update-status');

        // Trip Updates & Monitoring
        Route::post('/{trip}/add-update', [TripController::class, 'addUpdate'])->name('add-update');
        Route::get('/{trip}/updates', [TripController::class, 'getUpdates'])->name('get-updates');
        Route::get('/{trip}/location', [TripController::class, 'getCurrentLocation'])->name('location');

        // Trip Filtering & Views
        Route::get('/status/{status}', [TripController::class, 'filterByStatus'])->name('filter-status');
        Route::get('/driver/{driver}', [TripController::class, 'filterByDriver'])->name('filter-driver');
        Route::get('/vehicle/{vehicle}', [TripController::class, 'filterByVehicle'])->name('filter-vehicle');
        Route::get('/client/{client}', [TripController::class, 'filterByClient'])->name('filter-client');
        Route::get('/today', [TripController::class, 'todayTrips'])->name('today');
        Route::get('/active', [TripController::class, 'activeTrips'])->name('active');
        Route::get('/upcoming', [TripController::class, 'upcomingTrips'])->name('upcoming');
        Route::get('/completed', [TripController::class, 'completedTrips'])->name('completed');

        // Print & Export
        Route::get('/{trip}/print', [TripController::class, 'printTripSheet'])->name('print');
        Route::get('/export/excel', [TripController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export/pdf', [TripController::class, 'exportPdf'])->name('export-pdf');

        // Search
        Route::get('/search/query', [TripController::class, 'search'])->name('search');

        // AJAX Endpoints
        Route::get('/ajax/active', [TripController::class, 'getActiveTrips'])->name('ajax.active');
        Route::get('/ajax/{trip}/status', [TripController::class, 'getTripStatus'])->name('ajax.status');
    });

    // ============================================
    // DRIVERS MANAGEMENT
    // ============================================
    Route::prefix('drivers')->name('drivers.')->group(function () {
        // Standard CRUD Operations
        Route::get('/', [DriverController::class, 'index'])->name('index');
        Route::get('/create', [DriverController::class, 'create'])->name('create');
        Route::post('/', [DriverController::class, 'store'])->name('store');
        Route::get('/{driver}', [DriverController::class, 'show'])->name('show');
        Route::get('/{driver}/edit', [DriverController::class, 'edit'])->name('edit');
        Route::put('/{driver}', [DriverController::class, 'update'])->name('update');
        Route::delete('/{driver}', [DriverController::class, 'destroy'])->name('destroy');

        // Driver Status Management
        Route::post('/{driver}/update-status', [DriverController::class, 'updateStatus'])->name('update-status');
        Route::post('/{driver}/set-available', [DriverController::class, 'setAvailable'])->name('set-available');
        Route::post('/{driver}/set-off-duty', [DriverController::class, 'setOffDuty'])->name('set-off-duty');

        // Driver Information
        Route::get('/{driver}/trips', [DriverController::class, 'driverTrips'])->name('trips');
        Route::get('/{driver}/performance', [DriverController::class, 'performance'])->name('performance');
        Route::get('/{driver}/schedule', [DriverController::class, 'schedule'])->name('schedule');

        // Filtering & Views
        Route::get('/status/{status}', [DriverController::class, 'filterByStatus'])->name('filter-status');
        Route::get('/available/list', [DriverController::class, 'availableDrivers'])->name('available');
        Route::get('/on-trip/list', [DriverController::class, 'onTripDrivers'])->name('on-trip');

        // Search
        Route::get('/search/query', [DriverController::class, 'search'])->name('search');

        // AJAX Endpoints
        Route::get('/ajax/available', [DriverController::class, 'getAvailableDrivers'])->name('ajax.available');
    });

    // ============================================
    // VEHICLES MANAGEMENT
    // ============================================
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        // Standard CRUD Operations
        Route::get('/', [VehicleController::class, 'index'])->name('index');
        Route::get('/create', [VehicleController::class, 'create'])->name('create');
        Route::post('/', [VehicleController::class, 'store'])->name('store');
        Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('show');
        Route::get('/{vehicle}/edit', [VehicleController::class, 'edit'])->name('edit');
        Route::put('/{vehicle}', [VehicleController::class, 'update'])->name('update');
        Route::delete('/{vehicle}', [VehicleController::class, 'destroy'])->name('destroy');

        // Vehicle Status Management
        Route::post('/{vehicle}/update-status', [VehicleController::class, 'updateStatus'])->name('update-status');
        Route::post('/{vehicle}/set-available', [VehicleController::class, 'setAvailable'])->name('set-available');
        Route::post('/{vehicle}/set-maintenance', [VehicleController::class, 'setMaintenance'])->name('set-maintenance');

        // Vehicle Information
        Route::get('/{vehicle}/history', [VehicleController::class, 'tripHistory'])->name('history');
        Route::get('/{vehicle}/maintenance-log', [VehicleController::class, 'maintenanceLog'])->name('maintenance-log');

        // Filtering & Views
        Route::get('/status/{status}', [VehicleController::class, 'filterByStatus'])->name('filter-status');
        Route::get('/available/list', [VehicleController::class, 'availableVehicles'])->name('available');
        Route::get('/maintenance/list', [VehicleController::class, 'maintenanceVehicles'])->name('maintenance');

        // Search
        Route::get('/search/query', [VehicleController::class, 'search'])->name('search');

        // AJAX Endpoints
        Route::get('/ajax/available', [VehicleController::class, 'getAvailableVehicles'])->name('ajax.available');
    });

    // ============================================
    // CLIENTS MANAGEMENT
    // ============================================
    Route::prefix('clients')->name('clients.')->group(function () {
        // Standard CRUD Operations
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');

        // Client Information
        Route::get('/{client}/requests', [ClientController::class, 'clientRequests'])->name('requests');
        Route::get('/{client}/trips', [ClientController::class, 'clientTrips'])->name('trips');
        Route::get('/{client}/activity', [ClientController::class, 'activity'])->name('activity');
        Route::get('/{client}/recent-requests', [ClientController::class, 'recentRequests'])->name('recent-requests');

        // Search
        Route::get('/search/query', [ClientController::class, 'search'])->name('search');

        // Export
        Route::get('/export/excel', [ClientController::class, 'exportExcel'])->name('export-excel');
    });

    // ============================================
    // REPORTS & ANALYTICS
    // ============================================
    Route::prefix('reports')->name('reports.')->group(function () {
        // Report Dashboard
        Route::get('/', [ReportController::class, 'index'])->name('index');

        // Time-based Reports
        Route::get('/daily', [ReportController::class, 'dailyReport'])->name('daily');
        Route::get('/weekly', [ReportController::class, 'weeklyReport'])->name('weekly');
        Route::get('/monthly', [ReportController::class, 'monthlyReport'])->name('monthly');
        Route::get('/yearly', [ReportController::class, 'yearlyReport'])->name('yearly');
        Route::get('/custom', [ReportController::class, 'customReport'])->name('custom');

        // Performance Reports
        Route::get('/driver-performance', [ReportController::class, 'driverPerformance'])->name('driver-performance');
        Route::get('/vehicle-utilization', [ReportController::class, 'vehicleUtilization'])->name('vehicle-utilization');
        Route::get('/client-activity', [ReportController::class, 'clientActivity'])->name('client-activity');

        // Operational Reports
        Route::get('/on-time-delivery', [ReportController::class, 'onTimeDelivery'])->name('on-time-delivery');
        Route::get('/trip-summary', [ReportController::class, 'tripSummary'])->name('trip-summary');
        Route::get('/revenue-report', [ReportController::class, 'revenueReport'])->name('revenue-report');

        // Generate Dispatch Sheet
        Route::get('/dispatch-sheet', [ReportController::class, 'dispatchSheet'])->name('dispatch-sheet');
        Route::get('/dispatch-sheet/generate', [ReportController::class, 'generateDispatchSheet'])->name('generate-dispatch-sheet');

        // Export Functions
        Route::post('/export/daily', [ReportController::class, 'exportDaily'])->name('export-daily');
        Route::post('/export/weekly', [ReportController::class, 'exportWeekly'])->name('export-weekly');
        Route::post('/export/monthly', [ReportController::class, 'exportMonthly'])->name('export-monthly');
        Route::post('/export/custom', [ReportController::class, 'exportCustom'])->name('export-custom');

        // Google Sheets Integration
        Route::get('/sync/google-sheets', [ReportController::class, 'syncToGoogleSheets'])->name('sync-google-sheets');
        Route::post('/export/google-sheets', [ReportController::class, 'exportToGoogleSheets'])->name('export-google-sheets');
    });

    // ============================================
    // NOTIFICATIONS
    // ============================================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // Notification List
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');

        // Notification Actions
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clear-all');

        // Send Notifications
        Route::get('/send/client/{trip}', [NotificationController::class, 'sendClientNotification'])->name('send-client');
        Route::get('/send/driver/{trip}', [NotificationController::class, 'sendDriverNotification'])->name('send-driver');

        // AJAX Endpoints
        Route::get('/ajax/unread', [NotificationController::class, 'getUnread'])->name('ajax.unread');
        Route::get('/ajax/count', [NotificationController::class, 'getUnreadCount'])->name('ajax.count');
    });

    // ============================================
    // GLOBAL SEARCH
    // ============================================
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/', [DashboardController::class, 'globalSearch'])->name('index');
        Route::get('/requests', [DeliveryRequestController::class, 'search'])->name('requests');
        Route::get('/trips', [TripController::class, 'search'])->name('trips');
        Route::get('/drivers', [DriverController::class, 'search'])->name('drivers');
        Route::get('/vehicles', [VehicleController::class, 'search'])->name('vehicles');
        Route::get('/clients', [ClientController::class, 'search'])->name('clients');
    });

    // ============================================
    // UTILITIES & TOOLS
    // ============================================
    Route::prefix('utils')->name('utils.')->group(function () {
        // Backup & Export
        Route::get('/backup/database', [DashboardController::class, 'backupDatabase'])->name('backup-database');
        Route::get('/export/all-data', [DashboardController::class, 'exportAllData'])->name('export-all-data');

        // System Settings
        Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [DashboardController::class, 'updateSettings'])->name('update-settings');

        // Cache Management
        Route::post('/cache/clear', [DashboardController::class, 'clearCache'])->name('clear-cache');
    });

    // ============================================
    // CALENDAR VIEW
    // ============================================
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [TripController::class, 'calendarView'])->name('index');
        Route::get('/events', [TripController::class, 'calendarEvents'])->name('events');
    });

    // ============================================
    // MAP VIEW
    // ============================================
    Route::prefix('map')->name('map.')->group(function () {
        Route::get('/', [TripController::class, 'mapView'])->name('index');
        Route::get('/active-trips', [TripController::class, 'activeTripsMap'])->name('active-trips');
        Route::get('/trip/{trip}/track', [TripController::class, 'trackTrip'])->name('track-trip');
    });

    // ============================================
    // USER PROFILE
    // ============================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return view('profile.index');
        })->name('index');
        Route::get('/edit', function () {
            return view('profile.edit');
        })->name('edit');
        Route::put('/update', function () {
            // Update profile logic
        })->name('update');
        Route::put('/password', function () {
            // Update password logic
        })->name('update-password');
    });

    // ============================================
    // HELP & DOCUMENTATION
    // ============================================
    Route::prefix('help')->name('help.')->group(function () {
        Route::get('/', function () {
            return view('help.index');
        })->name('index');
        Route::get('/guide', function () {
            return view('help.guide');
        })->name('guide');
        Route::get('/faq', function () {
            return view('help.faq');
        })->name('faq');
    });
});

/*
|--------------------------------------------------------------------------
| Route List Command
|--------------------------------------------------------------------------
| To view all registered routes, run: php artisan route:list
| To view specific routes, run: php artisan route:list --name=trips
*/