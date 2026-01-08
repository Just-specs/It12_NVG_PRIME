<?php

namespace App\Jobs;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class DetectDelayedTrips implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find trips that are 30+ minutes past scheduled time and not started yet
        $delayedTrips = Trip::where('status', 'scheduled')
            ->whereNotNull('scheduled_time')
            ->where('scheduled_time', '<=', Carbon::now()->subMinutes(30))
            ->where('is_delayed', false)
            ->get();

        foreach ($delayedTrips as $trip) {
            // Mark trip as delayed
            $trip->update([
                'is_delayed' => true,
                'status' => 'delayed',
                'delay_detected_at' => now(),
                'delay_minutes' => $trip->calculateDelayMinutes(),
            ]);

            // Mark associated delivery request as delayed
            if ($trip->deliveryRequest) {
                $trip->deliveryRequest->update([
                    'is_delayed' => true,
                    'status' => 'delayed',
                    'delay_detected_at' => now(),
                ]);
            }

            // Send notifications to admin and head dispatcher
            $this->notifyStakeholders($trip);
        }
    }

    /**
     * Notify admin and head dispatcher about the delay
     */
    protected function notifyStakeholders(Trip $trip): void
    {
        // Get admin and head_dispatch users
        $admins = User::where('role', 'admin')->get();
        $headDispatchers = User::where('role', 'head_dispatch')->get();

        $recipients = $admins->merge($headDispatchers);

        // TODO: Implement actual notification
        // For now, we'll log or you can add notification class later
        foreach ($recipients as $recipient) {
            Notification::send($recipient, new \App\Notifications\TripDelayedNotification($trip));
            \Log::info("Trip #{$trip->id} delayed. Notifying {$recipient->name} ({$recipient->role})");
        }
    }
}
