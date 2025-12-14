<?php

namespace App\Http\Controllers;

use App\Models\ClientNotification;
use App\Models\Trip;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = ClientNotification::with(['trip.deliveryRequest.client', 'client'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => ClientNotification::count(),
            'sent' => ClientNotification::where('sent', true)->count(),
            'pending' => ClientNotification::where('sent', false)->count(),
        ];

        return view('dispatch.notifications.index', compact('notifications', 'stats'));
    }

    public function show(ClientNotification $notification)
    {
        $notification->load(['trip.deliveryRequest.client', 'trip.driver', 'trip.vehicle']);
        return view('dispatch.notifications.show', compact('notification'));
    }

    public function markAsRead(ClientNotification $notification)
    {
        $notification->update(['sent' => true]);

        return redirect()
            ->back()
            ->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        ClientNotification::where('sent', false)->update(['sent' => true]);

        return redirect()
            ->back()
            ->with('success', 'All notifications marked as read.');
    }

    public function destroy(ClientNotification $notification)
    {
        $notification->delete();

        return redirect()
            ->back()
            ->with('success', 'Notification deleted.');
    }

    public function clearAll()
    {
        ClientNotification::truncate();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'All notifications cleared.');
    }

    public function sendClientNotification(Trip $trip)
    {
        $client = $trip->deliveryRequest->client;

        $message = "Your delivery for ATW {$trip->deliveryRequest->atw_reference} is currently {$trip->status}.";

        ClientNotification::create([
            'trip_id' => $trip->id,
            'client_id' => $client->id,
            'notification_type' => 'status',
            'message' => $message,
            'method' => 'sms',
            'sent' => false
        ]);

        // Here you would integrate with SMS API (Twilio, etc.)
        // For now, just log it
        \Log::info("Notification sent to client: {$client->name}", [
            'trip_id' => $trip->id,
            'message' => $message
        ]);

        return redirect()
            ->back()
            ->with('success', 'Client notification created.');
    }

    public function sendDriverNotification(Trip $trip)
    {
        $driver = $trip->driver;

        $message = "Trip assignment for ATW {$trip->deliveryRequest->atw_reference}. " .
            "Pickup: {$trip->deliveryRequest->pickup_location}. " .
            "Delivery: {$trip->deliveryRequest->delivery_location}. " .
            "Scheduled: {$trip->scheduled_time->format('M d, Y h:i A')}";

        // Here you would send SMS to driver
        \Log::info("Notification sent to driver: {$driver->name}", [
            'trip_id' => $trip->id,
            'message' => $message
        ]);

        return redirect()
            ->back()
            ->with('success', 'Driver notification sent.');
    }

    // AJAX Endpoints
    public function getUnread()
    {
        $notifications = ClientNotification::where('sent', false)
            ->with(['trip.deliveryRequest.client'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    public function getUnreadCount()
    {
        $count = ClientNotification::where('sent', false)->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}

