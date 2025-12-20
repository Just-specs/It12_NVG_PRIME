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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Google OAuth Routes - Single callback for both login and register
    Route::get('auth/google/login', [AuthController::class, 'redirectToGoogleLogin'])->name('google.login');
    Route::get('auth/google/register', [AuthController::class, 'redirectToGoogleRegister'])->name('google.register');
    Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

    // ADD THESE PASSWORD RESET ROUTES:
    Route::get('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    Route::post('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {

    // ALL USERS (Admin, Head of Dispatch, Dispatch User)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Dashboard AJAX routes
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/recent-requests', [DashboardController::class, 'recentRequests'])->name('recent-requests');
        Route::get('/active-trips', [DashboardController::class, 'activeTrips'])->name('active-trips');
        Route::get('/today-schedule', [DashboardController::class, 'todaySchedule'])->name('today-schedule');
        Route::get('/stats', [DashboardController::class, 'getStats'])->name('stats');
        Route::get('/search', [DashboardController::class, 'globalSearch'])->name('search');
    });

    // DELIVERY REQUESTS
    Route::prefix('requests')->name('requests.')->group(function () {
        // Everyone can view and create requests
        Route::get('/', [DeliveryRequestController::class, 'index'])->name('index');
        Route::get('/create', [DeliveryRequestController::class, 'create'])->name('create');
        Route::post('/', [DeliveryRequestController::class, 'store'])->name('store');
        Route::get('/{request}', [DeliveryRequestController::class, 'show'])->name('show');
        Route::post('/{request}/cancel', [DeliveryRequestController::class, 'cancel'])->name('cancel');

        // VERIFICATION - Only Admin & Head of Dispatch can verify
        Route::middleware([CheckRole::class . ':admin'])->group(function () {
            Route::post('/{request}/verify', [DeliveryRequestController::class, 'verify'])->name('verify');
            Route::post('/{request}/verify-and-assign', [DeliveryRequestController::class, 'verifyAndAssign'])->name('verify-and-assign');
            Route::post('/{request}/auto-assign', [DeliveryRequestController::class, 'autoAssign'])->name('auto-assign');
        });

        // Edit/Delete - Admin & Head only
        Route::middleware([CheckRole::class . ':admin'])->group(function () {
            Route::get('/{request}/edit', [DeliveryRequestController::class, 'edit'])->name('edit');
            Route::put('/{request}', [DeliveryRequestController::class, 'update'])->name('update');
            Route::delete('/{request}', [DeliveryRequestController::class, 'destroy'])->name('destroy');
            Route::get('/export/excel', [DeliveryRequestController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export/pdf', [DeliveryRequestController::class, 'exportPdf'])->name('export-pdf');
        });
    });

    // TRIPS (Everyone)
    Route::prefix('trips')->name('trips.')->group(function () {
        Route::get('/', [TripController::class, 'index'])->name('index');
        Route::get('/create/{deliveryRequest}', [TripController::class, 'create'])->name('create');
        Route::post('/', [TripController::class, 'store'])->name('store');
        Route::get('/{trip}', [TripController::class, 'show'])->name('show');
        Route::post('/{trip}/start', [TripController::class, 'startTrip'])->name('start');
        Route::post('/{trip}/complete', [TripController::class, 'completeTrip'])->name('complete');
        Route::post('/{trip}/add-update', [TripController::class, 'addUpdate'])->name('add-update');

        // Edit/Delete/Cancel - Admin only
        Route::middleware([CheckRole::class . ':admin'])->group(function () {
            Route::get('/{trip}/edit', [TripController::class, 'edit'])->name('edit');
            Route::put('/{trip}', [TripController::class, 'update'])->name('update');
            Route::post('/{trip}/cancel', [TripController::class, 'cancelTrip'])->name('cancel');
            Route::delete('/{trip}', [TripController::class, 'destroy'])->name('destroy');
            Route::get('/export/excel', [TripController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export/pdf', [TripController::class, 'exportPdf'])->name('export-pdf');
        });
    });

    // NOTIFICATIONS (Everyone)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // Main notification pages
        Route::get('/', [NotificationController::class, 'index'])->name('index');

        // AJAX endpoints - must be before {notification} route
        Route::get('/ajax/unread', [NotificationController::class, 'getUnread'])->name('ajax.unread');
        Route::get('/ajax/unread-count', [NotificationController::class, 'getUnreadCount'])->name('ajax.unread-count');

        // Specific action routes - must be before {notification} route
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clear-all');

        // Send notifications - must be before {notification} route
        Route::post('/send-client/{trip}', [NotificationController::class, 'sendClientNotification'])->name('send-client');
        Route::post('/send-driver/{trip}', [NotificationController::class, 'sendDriverNotification'])->name('send-driver');

        // Parameterized routes - must be last
        Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // PROFILE (Everyone)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });

    //Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        // Main reports page
        Route::get('/', [ReportController::class, 'index'])->name('index');

        // Time-based reports
        Route::get('/daily', [ReportController::class, 'dailyReport'])->name('daily');
        Route::get('/weekly', [ReportController::class, 'weeklyReport'])->name('weekly');
        Route::get('/monthly', [ReportController::class, 'monthlyReport'])->name('monthly');
        Route::get('/yearly', [ReportController::class, 'yearlyReport'])->name('yearly');
        Route::get('/custom', [ReportController::class, 'customReport'])->name('custom');

        // Performance reports
        Route::get('/driver-performance', [ReportController::class, 'driverPerformance'])->name('driver-performance');
        Route::get('/vehicle-utilization', [ReportController::class, 'vehicleUtilization'])->name('vehicle-utilization');
        Route::get('/client-activity', [ReportController::class, 'clientActivity'])->name('client-activity');
        Route::get('/on-time-delivery', [ReportController::class, 'onTimeDelivery'])->name('on-time-delivery');
        Route::get('/trip-summary', [ReportController::class, 'tripSummary'])->name('trip-summary');

        // Dispatch sheet
        Route::get('/dispatch-sheet', [ReportController::class, 'dispatchSheet'])->name('dispatch-sheet');
        Route::post('/dispatch-sheet/generate', [ReportController::class, 'generateDispatchSheet'])->name('dispatch-sheet.generate');

        // Export routes
        Route::get('/export/daily', [ReportController::class, 'exportDaily'])->name('export-daily');
        Route::get('/export/weekly', [ReportController::class, 'exportWeekly'])->name('export-weekly');
        Route::get('/export/monthly', [ReportController::class, 'exportMonthly'])->name('export-monthly');
        Route::get('/export/custom', [ReportController::class, 'exportCustom'])->name('export-custom');

        // Google Sheets integration
        Route::post('/google-sheets/sync', [ReportController::class, 'syncToGoogleSheets'])->name('google-sheets.sync');
        Route::post('/google-sheets/export', [ReportController::class, 'exportToGoogleSheets'])->name('google-sheets.export');
    });
});

// ADMIN & HEAD OF DISPATCH ONLY
Route::middleware([CheckRole::class . ':admin'])->group(function () {

    // DRIVERS
    Route::prefix('drivers')->name('drivers.')->group(function () {
        Route::get('/', [DriverController::class, 'index'])->name('index');
        Route::get('/create', [DriverController::class, 'create'])->name('create');
        Route::post('/', [DriverController::class, 'store'])->name('store');
        Route::get('/{driver}', [DriverController::class, 'show'])->name('show');
        Route::get('/{driver}/edit', [DriverController::class, 'edit'])->name('edit');
        Route::put('/{driver}', [DriverController::class, 'update'])->name('update');
        Route::delete('/{driver}', [DriverController::class, 'destroy'])->name('destroy');
        Route::post('/{driver}/update-status', [DriverController::class, 'updateStatus'])->name('update-status');
    });

    // VEHICLES
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/', [VehicleController::class, 'index'])->name('index');
        Route::get('/create', [VehicleController::class, 'create'])->name('create');
        Route::post('/', [VehicleController::class, 'store'])->name('store');
        Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('show');
        Route::get('/{vehicle}/edit', [VehicleController::class, 'edit'])->name('edit');
        Route::put('/{vehicle}', [VehicleController::class, 'update'])->name('update');
        Route::delete('/{vehicle}', [VehicleController::class, 'destroy'])->name('destroy');
        Route::post('/{vehicle}/set-available', [VehicleController::class, 'setAvailable'])->name('set-available');
        Route::post('/{vehicle}/set-maintenance', [VehicleController::class, 'setMaintenance'])->name('set-maintenance');
    });

    // CLIENTS
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::get('/export/excel', [ClientController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export/pdf', [ClientController::class, 'exportPdf'])->name('export-pdf');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/requests', [ClientController::class, 'clientRequests'])->name('requests');
        Route::get('/{client}/recent-requests', [ClientController::class, 'recentRequests'])->name('recent-requests');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
});

    // ADMIN - Dispatcher Management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dispatchers', [AdminController::class, 'dispatchers'])->name('dispatchers.index');
        Route::get('/dispatchers/create', [AdminController::class, 'createDispatcher'])->name('dispatchers.create');
        Route::post('/dispatchers', [AdminController::class, 'storeDispatcher'])->name('dispatchers.store');
        Route::get('/dispatchers/{dispatcher}/edit', [AdminController::class, 'editDispatcher'])->name('dispatchers.edit');
        Route::put('/dispatchers/{dispatcher}', [AdminController::class, 'updateDispatcher'])->name('dispatchers.update');
        Route::delete('/dispatchers/{dispatcher}', [AdminController::class, 'destroyDispatcher'])->name('dispatchers.destroy');
    });


    // ADMIN ONLY
    Route::middleware([CheckRole::class . ':admin'])->group(function () {
        Route::prefix('utils')->name('utils.')->group(function () {
            Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
            Route::post('/settings/update', [DashboardController::class, 'updateSettings'])->name('update-settings');
            Route::post('/cache/clear', [DashboardController::class, 'clearCache'])->name('clear-cache');
        });
    });
});





// API routes for modal data fetching
Route::get('/api/available-drivers', function () {
    $drivers = \App\Models\Driver::where('status', 'available')
        ->withCount('trips')
        ->orderBy('name')
        ->get(['id', 'name', 'mobile', 'license_number']);
    
    return response()->json($drivers);
});

Route::get('/api/available-vehicles', function () {
    $vehicles = \App\Models\Vehicle::where('status', 'available')
        ->withCount('trips')
        ->orderBy('plate_number')
        ->get(['id', 'plate_number', 'vehicle_type', 'trailer_type']);
    
    return response()->json($vehicles);
});



// Debug routes (REMOVE IN PRODUCTION)
Route::get('/debug/test-driver', [App\Http\Controllers\DebugController::class, 'testDriverCreation']);
Route::post('/debug/test-driver-ajax', [App\Http\Controllers\DebugController::class, 'testAjaxDriverCreation']);


