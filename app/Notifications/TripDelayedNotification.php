<?php

namespace App\Notifications;

use App\Models\Trip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripDelayedNotification extends Notification
{
    use Queueable;

    protected $trip;

    /**
     * Create a new notification instance.
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Trip Delayed',
            'message' => "Trip #{$this->trip->id} (Waybill: {$this->trip->waybill_number}) is delayed by {$this->trip->delay_minutes} minutes.",
            'trip_id' => $this->trip->id,
            'delivery_request_id' => $this->trip->delivery_request_id,
            'delay_minutes' => $this->trip->delay_minutes,
            'scheduled_time' => $this->trip->scheduled_time,
            'driver_name' => $this->trip->driver->name ?? 'N/A',
            'action_url' => route('trips.show', $this->trip->id),
            'requires_action' => true,
            'action_text' => 'Provide Delay Reason',
        ];
    }
}
