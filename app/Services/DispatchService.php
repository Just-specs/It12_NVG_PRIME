<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\DeliveryRequest;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\ClientNotification;
use Illuminate\Support\Facades\DB;

class DispatchService
{
    public function assignTrip($deliveryRequestId, $driverId, $vehicleId, $scheduledTime, $routeInstructions = null)
    {
        return DB::transaction(function () use ($deliveryRequestId, $driverId, $vehicleId, $scheduledTime, $routeInstructions) {
            // Create trip
            $trip = Trip::create([
                'delivery_request_id' => $deliveryRequestId,
                'driver_id' => $driverId,
                'vehicle_id' => $vehicleId,
                'scheduled_time' => $scheduledTime,
                'route_instructions' => $routeInstructions,
                'status' => 'scheduled'
            ]);

            // Update request status
            DeliveryRequest::where('id', $deliveryRequestId)->update(['status' => 'assigned']);

            // Update driver status
            Driver::where('id', $driverId)->update(['status' => 'on-trip']);

            // Update vehicle status
            Vehicle::where('id', $vehicleId)->update(['status' => 'in-use']);

            // Notify driver (in real app, send SMS/call)
            $this->notifyDriver($trip);

            // Notify client
            $this->notifyClient($trip, 'assignment', 'Your delivery has been assigned and scheduled.');

            return $trip;
        });
    }

    public function updateTripStatus(Trip $trip, $status, $message = null, $location = null)
    {
        DB::transaction(function () use ($trip, $status, $message, $location) {
            // Update trip status
            $updateData = ['status' => $status];

            if ($status === 'in-transit' && !$trip->actual_start_time) {
                $updateData['actual_start_time'] = now();
            }

            if ($status === 'completed' && !$trip->actual_end_time) {
                $updateData['actual_end_time'] = now();

                // Free up resources
                Driver::where('id', $trip->driver_id)->update(['status' => 'available']);
                Vehicle::where('id', $trip->vehicle_id)->update(['status' => 'available']);
                DeliveryRequest::where('id', $trip->delivery_request_id)->update(['status' => 'completed']);
            }

            $trip->update($updateData);

            // Add update record
            if ($message) {
                $trip->updates()->create([
                    'update_type' => 'status',
                    'message' => $message,
                    'location' => $location,
                    'reported_by' => 'dispatcher'
                ]);
            }

            // Notify client of status change
            $this->notifyClient($trip, 'in-transit', "Trip status updated: {$status}");
        });
    }

    public function notifyDriver(Trip $trip)
    {
        $deliveryRequest = $trip->deliveryRequest;
        $driver = $trip->driver;

        // In production: Send SMS or make call to driver
        // For now, just log
        Log::info("Driver Notification", [
            'driver' => $driver->name,
            'mobile' => $driver->mobile,
            'atw' => $deliveryRequest->atw_reference,
            'pickup' => $deliveryRequest->pickup_location,
            'delivery' => $deliveryRequest->delivery_location,
            'schedule' => $trip->scheduled_time
        ]);
    }

    public function notifyClient(Trip $trip, $notificationType, $message)
    {
        $client = $trip->deliveryRequest->client;

        ClientNotification::create([
            'trip_id' => $trip->id,
            'client_id' => $client->id,
            'notification_type' => $notificationType,
            'message' => $message,
            'method' => 'sms', // or email, based on client preference
            'sent' => false
        ]);

        // In production: Actually send the notification
        Log::info("Client Notification", [
            'client' => $client->name,
            'type' => $notificationType,
            'message' => $message
        ]);
    }
}
