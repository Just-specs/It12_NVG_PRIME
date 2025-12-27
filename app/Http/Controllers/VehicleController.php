<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Trip;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $allowedStatuses = ['available', 'in-use', 'maintenance'];
        $activeStatus = $request->query('status', 'all');

        $vehiclesQuery = Vehicle::withCount(['trips' => function ($query) {
            $query->where('status', 'completed');
        }]);

        // Search functionality
        $search = $request->query('search');
        if ($search) {
            $vehiclesQuery->where(function ($query) use ($search) {
                $query->where('plate_number', 'like', "%{$search}%")
                    ->orWhere('vehicle_type', 'like', "%{$search}%")
                    ->orWhere('trailer_type', 'like', "%{$search}%");
            });
        }

        if ($activeStatus !== 'all') {
            if (in_array($activeStatus, $allowedStatuses, true)) {
                $vehiclesQuery->where('status', $activeStatus);
            } else {
                $activeStatus = 'all';
            }
        }

        $vehicles = $vehiclesQuery
            ->orderBy('plate_number')
            ->paginate(7);

        $vehicles->withPath(route('vehicles.index'));
        if ($activeStatus !== 'all') {
            $vehicles->appends(['status' => $activeStatus]);
        }
        if ($search) {
            $vehicles->appends(['search' => $search]);
        }

        $statusCounts = Vehicle::select('status')
            ->selectRaw('COUNT(*) as aggregate')
            ->whereIn('status', $allowedStatuses)
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $counts = [
            'all' => Vehicle::count(),
        ];

        foreach ($allowedStatuses as $status) {
            $counts[$status] = $statusCounts[$status] ?? 0;
        }

        if ($request->ajax()) {
            $html = view('dispatch.vehicles.partials.table', compact('vehicles'))->render();

            return response()->json([
                'html' => $html,
                'status' => $activeStatus,
                'counts' => $counts,
            ]);
        }

        return view('dispatch.vehicles.index', compact('vehicles', 'activeStatus', 'counts'));
    }

    public function create()
    {
        return view('dispatch.vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:vehicles,plate_number',
            'vehicle_type' => 'required|string',
            'trailer_type' => 'required|string',
            'status' => 'sometimes|in:available,in-use,maintenance',
            'confirm_duplicate' => 'nullable|boolean',
        ]);

        // Check for similar plate numbers unless user confirmed
        if (!$request->input('confirm_duplicate')) {
            $similar = Vehicle::findSimilar($validated['plate_number']);
            
            if (count($similar) > 0) {
                return response()->json([
                    'requires_confirmation' => true,
                    'similar_vehicles' => $similar,
                    'message' => 'Similar plate numbers found. Do you want to proceed?'
                ], 200);
            }
        }

        $vehicle = Vehicle::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('vehicles.show', $vehicle),
                'message' => 'Vehicle added successfully.'
            ]);
        }

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with('success', 'Vehicle added successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['trips' => function ($query) {
            $query->with(['deliveryRequest.client', 'driver'])
                ->orderBy('scheduled_time', 'desc')
                ->limit(20);
        }]);

        $stats = [
            'total_trips' => $vehicle->trips()->count(),
            'completed_trips' => $vehicle->trips()->where('status', 'completed')->count(),
            'active_trip' => $vehicle->trips()->where('status', 'in-transit')->first(),
            'last_maintenance' => $vehicle->updated_at,
        ];

        return view('dispatch.vehicles.show', compact('vehicle', 'stats'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('dispatch.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'vehicle_type' => 'required|string',
            'trailer_type' => 'required|string',
            'confirm_duplicate' => 'nullable|boolean',
        ]);

        // Check for similar plate numbers unless user confirmed (excluding current vehicle)
        if (!$request->input('confirm_duplicate')) {
            $similar = Vehicle::findSimilar($validated['plate_number'], $vehicle->id);
            
            if (count($similar) > 0) {
                return response()->json([
                    'requires_confirmation' => true,
                    'similar_vehicles' => $similar,
                    'message' => 'Similar plate numbers found. Do you want to proceed?'
                ], 200);
            }
        }

        $vehicle->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('vehicles.show', $vehicle),
                'message' => 'Vehicle updated successfully.'
            ]);
        }

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        // Check if vehicle has active trips
        if ($vehicle->trips()->whereIn('status', ['scheduled', 'in-transit'])->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete vehicle with active trips. Complete or cancel active trips first.');
        }

        // Soft delete the vehicle (audit log is automatic via Auditable trait)
        $vehicle->delete();

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    public function updateStatus(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,in-use,maintenance'
        ]);

        $vehicle->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', 'Vehicle status updated to ' . $validated['status'] . '.');
    }

    public function setAvailable(Vehicle $vehicle)
    {
        $vehicle->update(['status' => 'available']);

        return redirect()
            ->back()
            ->with('success', 'Vehicle set to available.');
    }

    public function setMaintenance(Vehicle $vehicle)
    {
        // Check if vehicle has active trips
        if ($vehicle->trips()->whereIn('status', ['scheduled', 'in-transit'])->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot set maintenance mode - vehicle has active trips.');
        }

        $vehicle->update(['status' => 'maintenance']);

        return redirect()
            ->back()
            ->with('success', 'Vehicle set to maintenance mode.');
    }

    public function tripHistory(Vehicle $vehicle)
    {
        $trips = $vehicle->trips()
            ->with(['deliveryRequest.client', 'driver'])
            ->orderBy('scheduled_time', 'desc')
            ->paginate(20);

        return view('dispatch.vehicles.history', compact('vehicle', 'trips'));
    }

    public function maintenanceLog(Vehicle $vehicle)
    {
        // You can create a separate maintenance_logs table
        // For now, we'll show trips with notes
        $maintenanceRecords = $vehicle->trips()
            ->where('status', 'cancelled')
            ->orWhereNotNull('notes')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dispatch.vehicles.maintenance-log', compact('vehicle', 'maintenanceRecords'));
    }

    public function filterByStatus($status)
    {
        $vehicles = Vehicle::where('status', $status)
            ->withCount('trips')
            ->orderBy('plate_number')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Vehicle::count(),
            'available' => Vehicle::where('status', 'available')->count(),
            'in_use' => Vehicle::where('status', 'in-use')->count(),
            'maintenance' => Vehicle::where('status', 'maintenance')->count(),
        ];

        return view('dispatch.vehicles.index', compact('vehicles', 'stats'));
    }

    public function availableVehicles()
    {
        $vehicles = Vehicle::available()->orderBy('plate_number')->paginate(12)->withQueryString();
        return view('dispatch.vehicles.available', compact('vehicles'));
    }

    public function maintenanceVehicles()
    {
        $vehicles = Vehicle::where('status', 'maintenance')
            ->withCount('trips')
            ->orderBy('plate_number')
            ->paginate(12)
            ->withQueryString();

        return view('dispatch.vehicles.maintenance', compact('vehicles'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $vehicles = Vehicle::where('plate_number', 'LIKE', "%{$query}%")
            ->orWhere('vehicle_type', 'LIKE', "%{$query}%")
            ->orWhere('trailer_type', 'LIKE', "%{$query}%")
            ->withCount('trips')
            ->orderBy('plate_number')
            ->paginate(12)
            ->withQueryString();

        return view('dispatch.vehicles.search', compact('vehicles', 'query'));
    }

    // AJAX Endpoints
    public function getAvailableVehicles()
    {
        $vehicles = Vehicle::available()->get();
        return response()->json($vehicles);
    }

    /**
     * Show deleted vehicles
     */
    public function deleted()
    {
        $vehicles = Vehicle::onlyTrashed()
            ->with('deletedBy')
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);
        
        return view('dispatch.vehicles.deleted', compact('vehicles'));
    }

    /**
     * Restore a soft-deleted vehicle
     */
    public function restore($id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        $vehicle->restore();
        
        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle restored successfully.');
    }

    /**
     * Permanently delete a vehicle
     */
    public function forceDelete($id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        
        // Check if vehicle has any trips
        if ($vehicle->trips()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot permanently delete vehicle with existing trips.');
        }
        
        $vehicle->forceDelete();
        
        return redirect()
            ->route('vehicles.deleted')
            ->with('success', 'Vehicle permanently deleted.');
    }
}


