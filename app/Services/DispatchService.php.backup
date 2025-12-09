<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\DeliveryRequest;
use App\Models\Driver;
use App\Models\Vehicle;
use Carbon\Carbon;

class DispatchService
{
    /**
     * Assign a trip to a driver and vehicle
     */
    public function assignTrip(
        int $deliveryRequestId,
        int $driverId,
        int $vehicleId,
        string $scheduledTime,
        ?string $routeInstructions = null
    ): Trip {
        // Validate that resources are available
        $driver = Driver::findOrFail($driverId);
        $vehicle = Vehicle::findOrFail($vehicleId);
        $deliveryRequest = DeliveryRequest::findOrFail($deliveryRequestId);

        if ($driver->status !== 'available') {
            throw new \Exception("Driver {$driver->name} is not available.");
        }

        if ($vehicle->status !== 'available') {
            throw new \Exception("Vehicle {$vehicle->plate_number} is not available.");
        }

        if (!in_array($deliveryRequest->status, ['pending', 'verified'])) {
            throw new \Exception("Delivery request is not available for assignment.");
        }

        // Create the trip
        $trip = Trip::create([
            'delivery_request_id' => $deliveryRequestId,
            'driver_id' => $driverId,
            'vehicle_id' => $vehicleId,
            'scheduled_time' => Carbon::parse($scheduledTime),
            'route_instructions' => $routeInstructions,
            'status' => 'scheduled',
        ]);

        // Update delivery request status
        $deliveryRequest->update(['status' => 'assigned']);

        // Update driver status
        $driver->update(['status' => 'on-trip']);

        // Update vehicle status
        $vehicle->update(['status' => 'in-use']);

        // Create initial trip update
        $trip->updates()->create([
            'update_type' => 'status',
            'message' => 'Trip assigned to driver and vehicle',
            'reported_by' => 'dispatcher',
        ]);

        return $trip->fresh(['deliveryRequest.client', 'driver', 'vehicle']);
    }

    /**
     * Update trip status with proper resource management
     */
    public function updateTripStatus(
        Trip $trip,
        string $status,
        ?string $message = null,
        ?string $location = null
    ): void {
        $oldStatus = $trip->status;

        // Update trip status
        $trip->update([
            'status' => $status,
            'actual_start_time' => $status === 'in-transit' && !$trip->actual_start_time
                ? now()
                : $trip->actual_start_time,
            'actual_end_time' => $status === 'completed' && !$trip->actual_end_time
                ? now()
                : $trip->actual_end_time,
        ]);

        // Create trip update
        $trip->updates()->create([
            'update_type' => 'status',
            'message' => $message ?? "Trip status changed from {$oldStatus} to {$status}",
            'location' => $location,
            'reported_by' => 'dispatcher',
        ]);

        // Handle resource status based on trip status
        if ($status === 'completed' || $status === 'cancelled') {
            // Free up driver and vehicle
            Driver::where('id', $trip->driver_id)->update(['status' => 'available']);
            Vehicle::where('id', $trip->vehicle_id)->update(['status' => 'available']);

            // Update delivery request status
            if ($status === 'completed') {
                DeliveryRequest::where('id', $trip->delivery_request_id)
                    ->update(['status' => 'completed']);
            } elseif ($status === 'cancelled') {
                DeliveryRequest::where('id', $trip->delivery_request_id)
                    ->update(['status' => 'verified']); // Back to verified, can be reassigned
            }
        }
    }

    /**
     * Check if a driver is available for assignment
     */
    public function isDriverAvailable(int $driverId): bool
    {
        $driver = Driver::find($driverId);

        if (!$driver || $driver->status !== 'available') {
            return false;
        }

        // Check for any active trips
        $hasActiveTrips = Trip::where('driver_id', $driverId)
            ->whereIn('status', ['scheduled', 'in-transit'])
            ->exists();

        return !$hasActiveTrips;
    }

    /**
     * Check if a vehicle is available for assignment
     */
    public function isVehicleAvailable(int $vehicleId): bool
    {
        $vehicle = Vehicle::find($vehicleId);

        if (!$vehicle || $vehicle->status !== 'available') {
            return false;
        }

        // Check for any active trips
        $hasActiveTrips = Trip::where('vehicle_id', $vehicleId)
            ->whereIn('status', ['scheduled', 'in-transit'])
            ->exists();

        return !$hasActiveTrips;
    }

    /**
     * Get available drivers for assignment
     */
    public function getAvailableDrivers()
    {
        return Driver::where('status', 'available')
            ->whereDoesntHave('trips', function ($query) {
                $query->whereIn('status', ['scheduled', 'in-transit']);
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Get available vehicles for assignment
     */
    public function getAvailableVehicles()
    {
        return Vehicle::where('status', 'available')
            ->whereDoesntHave('trips', function ($query) {
                $query->whereIn('status', ['scheduled', 'in-transit']);
            })
            ->orderBy('plate_number')
            ->get();
    }

    /**
     * Reassign a trip to different driver/vehicle
     */
    public function reassignTrip(
        Trip $trip,
        ?int $newDriverId = null,
        ?int $newVehicleId = null
    ): Trip {
        if ($trip->status === 'completed') {
            throw new \Exception('Cannot reassign a completed trip.');
        }

        $oldDriverId = $trip->driver_id;
        $oldVehicleId = $trip->vehicle_id;

        // Validate new resources if provided
        if ($newDriverId) {
            $newDriver = Driver::findOrFail($newDriverId);
            if (!$this->isDriverAvailable($newDriverId)) {
                throw new \Exception("Driver {$newDriver->name} is not available.");
            }
            $trip->driver_id = $newDriverId;
        }

        if ($newVehicleId) {
            $newVehicle = Vehicle::findOrFail($newVehicleId);
            if (!$this->isVehicleAvailable($newVehicleId)) {
                throw new \Exception("Vehicle {$newVehicle->plate_number} is not available.");
            }
            $trip->vehicle_id = $newVehicleId;
        }

        $trip->save();

        // Free old resources if they were changed
        if ($newDriverId && $oldDriverId !== $newDriverId) {
            Driver::where('id', $oldDriverId)->update(['status' => 'available']);
            Driver::where('id', $newDriverId)->update(['status' => 'on-trip']);
        }

        if ($newVehicleId && $oldVehicleId !== $newVehicleId) {
            Vehicle::where('id', $oldVehicleId)->update(['status' => 'available']);
            Vehicle::where('id', $newVehicleId)->update(['status' => 'in-use']);
        }

        // Log the reassignment
        $trip->updates()->create([
            'update_type' => 'status',
            'message' => 'Trip reassigned to new driver/vehicle',
            'reported_by' => 'dispatcher',
        ]);

        return $trip->fresh(['deliveryRequest.client', 'driver', 'vehicle']);
    }
}
