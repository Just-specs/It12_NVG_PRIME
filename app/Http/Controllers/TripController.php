<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\DeliveryRequest;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Services\DispatchService;
use Illuminate\Http\Request;

class TripController extends Controller
{
    protected $dispatchService;

    public function __construct(DispatchService $dispatchService)
    {
        $this->dispatchService = $dispatchService;
    }

    public function index()
    {
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->orderBy('scheduled_time', 'desc')
            ->get();

        return view('dispatch.trips.index', compact('trips'));
    }

    public function create(DeliveryRequest $deliveryRequest)
    {
        $drivers = Driver::available()->get();
        $vehicles = Vehicle::available()->get();

        return view('dispatch.trips.create', compact('deliveryRequest', 'drivers', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_request_id' => 'required|exists:delivery_requests,id',
            'driver_id' => 'required|exists:drivers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'scheduled_time' => 'required|date',
            'route_instructions' => 'nullable|string'
        ]);

        $trip = $this->dispatchService->assignTrip(
            $validated['delivery_request_id'],
            $validated['driver_id'],
            $validated['vehicle_id'],
            $validated['scheduled_time'],
            $validated['route_instructions'] ?? null
        );

        return redirect()
            ->route('trips.show', $trip)
            ->with('success', 'Trip assigned successfully. Driver has been notified.');
    }

    public function show(Trip $trip)
    {
        $trip->load(['deliveryRequest.client', 'driver', 'vehicle', 'updates']);
        return view('dispatch.trips.show', compact('trip'));
    }

    public function updateStatus(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,in-transit,completed,cancelled',
            'update_message' => 'nullable|string',
            'location' => 'nullable|string'
        ]);

        $this->dispatchService->updateTripStatus(
            $trip,
            $validated['status'],
            $validated['update_message'] ?? null,
            $validated['location'] ?? null
        );

        return redirect()
            ->back()
            ->with('success', 'Trip status updated successfully.');
    }

    public function addUpdate(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'update_type' => 'required|in:status,location,delay,incident,completed',
            'message' => 'required|string',
            'location' => 'nullable|string'
        ]);

        $trip->updates()->create([
            'update_type' => $validated['update_type'],
            'message' => $validated['message'],
            'location' => $validated['location'] ?? null,
            'reported_by' => 'dispatcher'
        ]);

        // Notify client if significant update
        if (in_array($validated['update_type'], ['delay', 'incident', 'completed'])) {
            $this->dispatchService->notifyClient($trip, $validated['update_type'], $validated['message']);
        }

        return redirect()->back()->with('success', 'Update added successfully.');
    }
    public function edit(Trip $trip)
    {
        $drivers = \App\Models\Driver::available()->get();
        $vehicles = \App\Models\Vehicle::available()->get();

        return view('dispatch.trips.edit', compact('trip', 'drivers', 'vehicles'));
    }

    public function update(Request $validateRequest, Trip $trip)
    {
        $validated = $validateRequest->validate([
            'driver_id' => 'sometimes|exists:drivers,id',
            'vehicle_id' => 'sometimes|exists:vehicles,id',
            'scheduled_time' => 'sometimes|date',
            'route_instructions' => 'nullable|string'
        ]);

        $trip->update($validated);

        return redirect()
            ->route('trips.show', $trip)
            ->with('success', 'Trip updated successfully.');
    }

    public function destroy(Trip $trip)
    {
        if ($trip->status === 'in-transit') {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete trip that is in transit.');
        }

        // Free up resources
        \App\Models\Driver::where('id', $trip->driver_id)
            ->update(['status' => 'available']);
        \App\Models\Vehicle::where('id', $trip->vehicle_id)
            ->update(['status' => 'available']);

        $trip->delete();

        return redirect()
            ->route('trips.index')
            ->with('success', 'Trip deleted successfully.');
    }

    public function startTrip(Trip $trip)
    {
        $trip->update([
            'status' => 'in-transit',
            'actual_start_time' => now()
        ]);

        $trip->updates()->create([
            'update_type' => 'status',
            'message' => 'Trip started',
            'reported_by' => 'dispatcher'
        ]);

        return redirect()
            ->back()
            ->with('success', 'Trip started successfully.');
    }

    public function completeTrip(Trip $trip)
    {
        $trip->update([
            'status' => 'completed',
            'actual_end_time' => now()
        ]);

        // Free up resources
        \App\Models\Driver::where('id', $trip->driver_id)
            ->update(['status' => 'available']);
        \App\Models\Vehicle::where('id', $trip->vehicle_id)
            ->update(['status' => 'available']);

        // Update delivery request
        DeliveryRequest::where('id', $trip->delivery_request_id)
            ->update(['status' => 'completed']);

        $trip->updates()->create([
            'update_type' => 'completed',
            'message' => 'Trip completed successfully',
            'reported_by' => 'dispatcher'
        ]);

        return redirect()
            ->back()
            ->with('success', 'Trip completed successfully.');
    }

    public function cancelTrip(Trip $trip)
    {
        $trip->update(['status' => 'cancelled']);

        // Free up resources
        \App\Models\Driver::where('id', $trip->driver_id)
            ->update(['status' => 'available']);
        \App\Models\Vehicle::where('id', $trip->vehicle_id)
            ->update(['status' => 'available']);

        $trip->updates()->create([
            'update_type' => 'status',
            'message' => 'Trip cancelled',
            'reported_by' => 'dispatcher'
        ]);

        return redirect()
            ->back()
            ->with('success', 'Trip cancelled.');
    }

    public function getUpdates(Trip $trip)
    {
        $updates = $trip->updates()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'updates' => $updates
        ]);
    }

    public function getCurrentLocation(Trip $trip)
    {
        // Get the latest location update
        $locationUpdate = $trip->updates()
            ->where('update_type', 'location')
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'location' => $locationUpdate ? $locationUpdate->location : null
        ]);
    }

    public function filterByDriver(Driver $driver)
    {
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->where('driver_id', $driver->id)
            ->orderBy('scheduled_time', 'desc')
            ->get();

        return view('dispatch.trips.index', compact('trips'));
    }

    public function filterByVehicle(Vehicle $vehicle)
    {
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->where('vehicle_id', $vehicle->id)
            ->orderBy('scheduled_time', 'desc')
            ->get();

        return view('dispatch.trips.index', compact('trips'));
    }

    public function filterByClient(Client $client)
    {
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereHas('deliveryRequest', function ($q) use ($client) {
                $q->where('client_id', $client->id);
            })
            ->orderBy('scheduled_time', 'desc')
            ->get();

        return view('dispatch.trips.index', compact('trips'));
    }

    public function todayTrips()
    {
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereDate('scheduled_time', \Carbon\Carbon::today())
            ->orderBy('scheduled_time')
            ->get();

        return view('dispatch.trips.today', compact('trips'));
    }

    public function upcomingTrips()
    {
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->where('scheduled_time', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_time')
            ->get();

        return view('dispatch.trips.upcoming', compact('trips'));
    }

    public function completedTrips()
    {
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->where('status', 'completed')
            ->orderBy('actual_end_time', 'desc')
            ->paginate(20);

        return view('dispatch.trips.completed', compact('trips'));
    }

    public function printTripSheet(Trip $trip)
    {
        $trip->load(['deliveryRequest.client', 'driver', 'vehicle', 'updates']);
        return view('dispatch.trips.print', compact('trip'));
    }

    public function exportPdf()
    {
        return redirect()->back()->with('info', 'PDF export requires DomPDF package.');
    }

    public function calendarView()
    {
        return view('dispatch.trips.calendar');
    }

    public function calendarEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $trips = Trip::with(['deliveryRequest.client', 'driver'])
            ->whereBetween('scheduled_time', [$start, $end])
            ->get();

        $events = $trips->map(function ($trip) {
            return [
                'id' => $trip->id,
                'title' => $trip->deliveryRequest->client->name . ' - ' . $trip->driver->name,
                'start' => $trip->scheduled_time->toIso8601String(),
                'end' => $trip->actual_end_time ? $trip->actual_end_time->toIso8601String() : null,
                'color' => $trip->status === 'completed' ? '#10b981' : ($trip->status === 'in-transit' ? '#3b82f6' : '#6b7280'),
                'url' => route('trips.show', $trip)
            ];
        });

        return response()->json($events);
    }

    public function mapView()
    {
        $activeTrips = Trip::with(['deliveryRequest', 'driver', 'vehicle'])
            ->where('status', 'in-transit')
            ->get();

        return view('dispatch.trips.map', compact('activeTrips'));
    }

    public function activeTripsMap()
    {
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->where('status', 'in-transit')
            ->get();

        return response()->json([
            'success' => true,
            'trips' => $trips
        ]);
    }

    public function trackTrip(Trip $trip)
    {
        $trip->load(['deliveryRequest.client', 'driver', 'vehicle', 'updates']);
        return view('dispatch.trips.track', compact('trip'));
    }

    public function getTripStatus(Trip $trip)
    {
        return response()->json([
            'success' => true,
            'status' => $trip->status,
            'last_update' => $trip->updates()->latest()->first()
        ]);
    }
}
