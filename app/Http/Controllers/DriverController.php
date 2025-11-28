<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;


class DriverController extends Controller
{
    public function index(Request $request)
    {
        $allowedStatuses = ['available', 'on-trip', 'off-duty'];
        $activeStatus = $request->query('status', 'all');

        $driversQuery = Driver::withCount('trips');

        // Search functionality
        $search = $request->query('search');
        if ($search) {
            $driversQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($activeStatus !== 'all') {
            if (in_array($activeStatus, $allowedStatuses, true)) {
                $driversQuery->where('status', $activeStatus);
            } else {
                $activeStatus = 'all';
            }
        }

        $drivers = $driversQuery
            ->orderBy('name')
            ->paginate(7);

        $drivers->withPath(route('drivers.index'));
        if ($activeStatus !== 'all') {
            $drivers->appends(['status' => $activeStatus]);
        }
        if ($search) {
            $drivers->appends(['search' => $search]);
        }

        $statusCounts = Driver::select('status')
            ->selectRaw('COUNT(*) as aggregate')
            ->whereIn('status', $allowedStatuses)
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $counts = [
            'all' => Driver::count(),
        ];

        foreach ($allowedStatuses as $status) {
            $counts[$status] = $statusCounts[$status] ?? 0;
        }

        if ($request->ajax()) {
            $html = view('dispatch.drivers.partials.table', compact('drivers'))->render();

            return response()->json([
                'html' => $html,
                'status' => $activeStatus,
                'counts' => $counts,
            ]);
        }

        return view('dispatch.drivers.index', compact('drivers', 'activeStatus', 'counts'));
    }

    public function show(Driver $driver)
    {
        $driver->load(['trips' => function ($query) {
            $query->orderBy('scheduled_time', 'desc')->limit(10);
        }]);

        return view('dispatch.drivers.show', compact('driver'));
    }

    public function updateStatus(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,on-trip,off-duty'
        ]);

        $driver->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Driver status updated.');
    }
    public function edit(Driver $driver)
    {
        return view('dispatch.drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'license_number' => 'required|string|max:50',
        ]);

        $driver->update($validated);

        return redirect()
            ->route('drivers.show', $driver)
            ->with('success', 'Driver updated successfully.');
    }

    public function destroy(Driver $driver)
    {
        // Check if driver has active trips
        if ($driver->trips()->whereIn('status', ['scheduled', 'in-transit'])->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete driver with active trips.');
        }

        $driver->delete();

        return redirect()
            ->route('drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }

    public function setAvailable(Driver $driver)
    {
        $driver->update(['status' => 'available']);

        return redirect()
            ->back()
            ->with('success', 'Driver set to available.');
    }

    public function setOffDuty(Driver $driver)
    {
        // Check if driver has active trips
        if ($driver->trips()->whereIn('status', ['scheduled', 'in-transit'])->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot set off-duty - driver has active trips.');
        }

        $driver->update(['status' => 'off-duty']);

        return redirect()
            ->back()
            ->with('success', 'Driver set to off-duty.');
    }

    public function driverTrips(Driver $driver)
    {
        $trips = $driver->trips()
            ->with(['deliveryRequest.client', 'vehicle'])
            ->orderBy('scheduled_time', 'desc')
            ->paginate(20);

        return view('dispatch.drivers.trips', compact('driver', 'trips'));
    }

    public function performance(Driver $driver)
    {
        $stats = [
            'total_trips' => $driver->trips()->count(),
            'completed' => $driver->trips()->where('status', 'completed')->count(),
            'in_progress' => $driver->trips()->where('status', 'in-transit')->count(),
            'cancelled' => $driver->trips()->where('status', 'cancelled')->count(),
        ];

        $recentTrips = $driver->trips()
            ->with(['deliveryRequest.client', 'vehicle'])
            ->orderBy('scheduled_time', 'desc')
            ->limit(10)
            ->get();

        return view('dispatch.drivers.performance', compact('driver', 'stats', 'recentTrips'));
    }

    public function schedule(Driver $driver)
    {
        $upcomingTrips = $driver->trips()
            ->with(['deliveryRequest.client', 'vehicle'])
            ->where('scheduled_time', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_time')
            ->get();

        return view('dispatch.drivers.schedule', compact('driver', 'upcomingTrips'));
    }

    public function onTripDrivers()
    {
        $drivers = Driver::where('status', 'on-trip')
            ->with(['trips' => function ($query) {
                $query->where('status', 'in-transit')->latest();
            }])
            ->get();

        return view('dispatch.drivers.on-trip', compact('drivers'));
    }
}
