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

        $driversQuery = Driver::query()->withCount('trips');

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


    public function create()
    {
        $availableDrivers = Driver::where('status', '!=', 'off-duty')
            ->orderBy('name')
            ->get(['id', 'name', 'status']);
        
        return view('dispatch.drivers.create', compact('availableDrivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'license_number' => 'required|string|max:50|unique:drivers,license_number',
            
            'status' => 'sometimes|in:available,on-trip,off-duty',
            'confirm_duplicate' => 'nullable|boolean',
        ]);

        // Normalize name (remove accents, convert to uppercase)
        $normalizedName = strtoupper($this->normalizeString($validated['name']));
        
        // Check for duplicate driver name (normalized)
        $existingDriver = Driver::whereRaw('UPPER(REPLACE(REPLACE(name, "Ñ", "N"), "ñ", "n")) = ?', [$normalizedName])->first();
        
        if ($existingDriver && !$request->input('confirm_duplicate')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'requires_confirmation' => true,
                    'similar_drivers' => [['id' => $existingDriver->id, 'name' => $existingDriver->name, 'license_number' => $existingDriver->license_number]],
                    'message' => 'A driver with this name already exists. Do you want to proceed anyway?'
                ], 200);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'A driver named "' . $existingDriver->name . '" already exists.');
        }

        // Check for similar names or license numbers unless user confirmed
        if (!$request->input('confirm_duplicate')) {
            $similar = Driver::findSimilar($validated['name'], $validated['license_number']);
            
            if (count($similar) > 0) {
                return response()->json([
                    'requires_confirmation' => true,
                    'similar_drivers' => $similar,
                    'message' => 'Similar driver names or license numbers found. Do you want to proceed?'
                ], 200);
            }
        }

        // Set default status if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = 'available';
        }

        $driver = Driver::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('drivers.show', $driver),
                'message' => 'Driver added successfully.'
            ]);
        }

        return redirect()
            ->route('drivers.show', $driver)
            ->with('success', 'Driver added successfully.');
    }
    
    /**
     * Normalize string by removing accents and special characters
     */
    private function normalizeString($string)
    {
        $string = str_replace(['Ñ', 'ñ'], 'N', $string);
        return preg_replace('/[^A-Za-z0-9\s\-]/', '', $string);
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
        $availableDrivers = Driver::where('status', '!=', 'off-duty')
            ->where('id', '!=', $driver->id)
            ->orderBy('name')
            ->get(['id', 'name', 'status']);
        
        return view('dispatch.drivers.edit', compact('driver', 'availableDrivers'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'license_number' => 'required|string|max:50',
            
            'confirm_duplicate' => 'nullable|boolean',
        ]);
        


        // Check for similar names or license numbers unless user confirmed (excluding current driver)
        if (!$request->input('confirm_duplicate')) {
            $similar = Driver::findSimilar($validated['name'], $validated['license_number'], $driver->id);
            
            if (count($similar) > 0) {
                return response()->json([
                    'requires_confirmation' => true,
                    'similar_drivers' => $similar,
                    'message' => 'Similar driver names or license numbers found. Do you want to proceed?'
                ], 200);
            }
        }

        $driver->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('drivers.show', $driver),
                'message' => 'Driver updated successfully.'
            ]);
        }

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
                ->with('error', 'Cannot delete driver with active trips. Complete or cancel active trips first.');
        }

        // If user is admin, delete directly
        if (auth()->user()->isAdmin()) {
            $driver->delete();
            return redirect()
                ->route('drivers.index')
                ->with('success', 'Driver deleted successfully.');
        }

        // If user is head_dispatch, create deletion request
        if (auth()->user()->role === 'head_dispatch') {
            return redirect()->route('drivers.requestDelete', $driver);
        }

        // Otherwise, unauthorized
        abort(403, 'You do not have permission to delete drivers.');
    }

    /**
     * Show deletion request form for head_dispatch
     */
    public function requestDelete(Driver $driver)
    {
        // Only head_dispatch can request deletion
        if (!in_array(auth()->user()->role, ['admin', 'head_dispatch'])) {
            abort(403, 'Only Admin and Head Dispatch can request deletions.');
        }

        // Check if driver has active trips
        if ($driver->trips()->whereIn('status', ['scheduled', 'in-transit'])->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot request deletion for driver with active trips.');
        }

        return view('dispatch.drivers.request-delete', compact('driver'));
    }

    /**
     * Submit deletion request for admin approval
     */
    public function submitDeleteRequest(Request $request, Driver $driver)
    {
        // Only head_dispatch can submit deletion requests
        if (!in_array(auth()->user()->role, ['admin', 'head_dispatch'])) {
            abort(403, 'Only Admin and Head Dispatch can request deletions.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        // Check for existing pending request
        $existingRequest = \App\Models\DeletionRequest::where('resource_type', 'driver')
            ->where('resource_id', $driver->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()
                ->route('drivers.index')
                ->with('error', 'A deletion request for this driver is already pending approval.');
        }

        \App\Models\DeletionRequest::create([
            'requested_by' => auth()->id(),
            'resource_type' => 'driver',
            'resource_id' => $driver->id,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('drivers.index')
            ->with('success', 'Deletion request submitted to admin for approval.');
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

    /**
     * Show deleted drivers
     */
    public function deleted()
    {
        $drivers = Driver::onlyTrashed()
            ->with('deletedBy')
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);
        
        return view('dispatch.drivers.deleted', compact('drivers'));
    }

    /**
     * Restore a soft-deleted driver
     */
    public function restore($id)
    {
        $driver = Driver::onlyTrashed()->findOrFail($id);
        $driver->restore();
        
        return redirect()
            ->route('drivers.index')
            ->with('success', 'Driver restored successfully.');
    }

    /**
     * Permanently delete a driver
     */
    public function forceDelete($id)
    {
        $driver = Driver::onlyTrashed()->findOrFail($id);
        
        // Check if driver has any trips
        if ($driver->trips()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot permanently delete driver with existing trips.');
        }
        
        $driver->forceDelete();
        
        return redirect()
            ->route('drivers.deleted')
            ->with('success', 'Driver permanently deleted.');
    }

    /**
     * Show co-driver management page
     */
    public function manageCoDrivers(Driver $driver)
    {
        $driver->load(['coDrivers', 'driversHavingAsCoDriver']);
        $availableDrivers = Driver::where('id', '!=', $driver->id)
            ->where('status', 'available')
            ->get();
        
        return view('dispatch.drivers.co-drivers', compact('driver', 'availableDrivers'));
    }
    
    /**
     * Add a co-driver
     */
    public function addCoDriver(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'co_driver_id' => 'required|exists:drivers,id',
        ]);
        
        $coDriverId = $validated['co_driver_id'];
        
        // Cannot be co-driver with themselves
        if ($coDriverId == $driver->id) {
            return redirect()
                ->back()
                ->with('error', 'A driver cannot be a co-driver with themselves.');
        }
        
        // Check if already co-drivers
        if ($driver->hasCoDriver($coDriverId)) {
            return redirect()
                ->back()
                ->with('error', 'This driver is already a co-driver.');
        }
        
        // Add co-driver relationship
        $driver->coDrivers()->attach($coDriverId);
        
        $coDriver = Driver::find($coDriverId);
        
        return redirect()
            ->back()
            ->with('success', $coDriver->name . ' has been added as a co-driver.');
    }
    
    /**
     * Remove a co-driver
     */
    public function removeCoDriver(Driver $driver, Driver $coDriver)
    {
        // Remove the relationship from both directions
        $driver->coDrivers()->detach($coDriver->id);
        $driver->driversHavingAsCoDriver()->detach($coDriver->id);
        
        return redirect()
            ->back()
            ->with('success', $coDriver->name . ' has been removed as a co-driver.');
    }
}