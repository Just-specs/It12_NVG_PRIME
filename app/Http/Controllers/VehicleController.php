<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Trip;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::withCount(['trips' => function ($query) {
            $query->where('status', 'completed');
        }])
            ->orderBy('plate_number')
            ->get();

        $stats = [
            'total' => $vehicles->count(),
            'available' => $vehicles->where('status', 'available')->count(),
            'in_use' => $vehicles->where('status', 'in-use')->count(),
            'maintenance' => $vehicles->where('status', 'maintenance')->count(),
        ];

        return view('dispatch.vehicles.index', compact('vehicles', 'stats'));
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
            'status' => 'sometimes|in:available,in-use,maintenance'
        ]);

        $vehicle = Vehicle::create($validated);

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
        ]);

        $vehicle->update($validated);

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
                ->with('error', 'Cannot delete vehicle with active trips.');
        }

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
            ->get();

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
        $vehicles = Vehicle::available()->get();
        return view('dispatch.vehicles.available', compact('vehicles'));
    }

    public function maintenanceVehicles()
    {
        $vehicles = Vehicle::where('status', 'maintenance')
            ->withCount('trips')
            ->get();

        return view('dispatch.vehicles.maintenance', compact('vehicles'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $vehicles = Vehicle::where('plate_number', 'LIKE', "%{$query}%")
            ->orWhere('vehicle_type', 'LIKE', "%{$query}%")
            ->orWhere('trailer_type', 'LIKE', "%{$query}%")
            ->withCount('trips')
            ->get();

        return view('dispatch.vehicles.search', compact('vehicles', 'query'));
    }

    // AJAX Endpoints
    public function getAvailableVehicles()
    {
        $vehicles = Vehicle::available()->get();
        return response()->json($vehicles);
    }
}
