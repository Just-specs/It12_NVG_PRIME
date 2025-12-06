<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRequest;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'pending_requests' => DeliveryRequest::where('status', 'pending')->count(),
            'active_trips' => Trip::where('status', 'in-transit')->count(),
            'available_drivers' => Driver::where('status', 'available')->count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'today_trips' => Trip::whereDate('scheduled_time', $today)->count(),
            'completed_today' => Trip::whereDate('actual_end_time', $today)
                ->where('status', 'completed')->count()
        ];

        $recentRequests = DeliveryRequest::with('client')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(3, ['*'], 'recent_page')
            ->withPath(route('dashboard.recent-requests'));


        $activeTrips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->where('status', 'in-transit')
            ->orderBy('scheduled_time', 'desc')
            ->simplePaginate(3, ['*'], 'active_page')
            ->withPath(route('dashboard.active-trips'));

        $todaySchedule = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereDate('scheduled_time', $today)
            ->orderBy('scheduled_time')
            ->simplePaginate(3, ['*'], 'schedule_page')
            ->withPath(route('dashboard.today-schedule'));

        return view('dispatch.dashboard', compact('stats', 'recentRequests', 'activeTrips', 'todaySchedule'));
    }

    public function recentRequests(Request $request)
    {
        $recentRequests = DeliveryRequest::with('client')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(3, ['*'], 'recent_page')
            ->withPath(route('dashboard.recent-requests'));

        if ($request->ajax()) {
            $html = view('dispatch.dashboard.partials.recent-requests', compact('recentRequests'))->render();

            return response()->json(['html' => $html]);
        }

        return redirect()->route('dashboard');
    }

    public function activeTrips(Request $request)
    {
        $activeTrips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->where('status', 'in-transit')
            ->orderBy('scheduled_time', 'desc')
            ->simplePaginate(5, ['*'], 'active_page')
            ->withPath(route('dashboard.active-trips'));

        if ($request->ajax()) {
            $html = view('dispatch.dashboard.partials.active-trips', compact('activeTrips'))->render();

            return response()->json(['html' => $html]);
        }

        return redirect()->route('dashboard');
    }

    public function todaySchedule(Request $request)
    {
        $today = Carbon::today();

        $todaySchedule = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereDate('scheduled_time', $today)
            ->orderBy('scheduled_time')
            ->simplePaginate(3, ['*'], 'schedule_page')
            ->withPath(route('dashboard.today-schedule'));

        if ($request->ajax()) {
            $html = view('dispatch.dashboard.partials.today-schedule', compact('todaySchedule'))->render();

            return response()->json(['html' => $html]);
        }

        return redirect()->route('dashboard');
    }

    public function getStats()
    {
        $stats = [
            'pending_requests' => DeliveryRequest::where('status', 'pending')->count(),
            'active_trips' => Trip::where('status', 'in-transit')->count(),
            'available_drivers' => Driver::where('status', 'available')->count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'today_trips' => Trip::whereDate('scheduled_time', \Carbon\Carbon::today())->count(),
            'completed_today' => Trip::whereDate('actual_end_time', \Carbon\Carbon::today())
                ->where('status', 'completed')->count()
        ];

        return response()->json(['success' => true, 'stats' => $stats]);
    }

    public function globalSearch(Request $request)
    {
        $query = $request->input('q');

        $results = [
            'requests' => DeliveryRequest::where('atw_reference', 'LIKE', "%{$query}%")
                ->orWhereHas('client', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->with('client')
                ->limit(5)
                ->get(),

            'trips' => Trip::whereHas('deliveryRequest', function ($q) use ($query) {
                $q->where('atw_reference', 'LIKE', "%{$query}%");
            })
                ->with(['deliveryRequest.client', 'driver', 'vehicle'])
                ->limit(5)
                ->get(),

            'drivers' => Driver::where('name', 'LIKE', "%{$query}%")
                ->orWhere('mobile', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(),

            'vehicles' => Vehicle::where('plate_number', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(),

            'clients' => Client::where('name', 'LIKE', "%{$query}%")
                ->orWhere('company', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(),
        ];

        return view('dispatch.search.results', compact('results', 'query'));
    }

    public function settings()
    {
        return view('dispatch.settings.index');
    }

    public function updateSettings(Request $request)
    {
        // Implement settings update logic
        return redirect()->route('utils.settings')->with('success', 'Settings updated successfully.');
    }

    public function clearCache()
    {


        return redirect()->route('utils.settings')->with('success', 'Cache cleared successfully.');
    }

    public function backupDatabase()
    {
        // Implement database backup logic
        return redirect()->route('utils.settings')->with('info', 'Database backup feature coming soon.');
    }

    public function exportAllData() {}
}
