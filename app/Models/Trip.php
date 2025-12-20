<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Trip extends Model
{
    protected $fillable = [
        'delivery_request_id',
        'driver_id',
        'vehicle_id',
        'scheduled_time',
        'actual_start_time',
        'actual_end_time',
        'status',
        'route_instructions',
        'archived_at',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
        'archived_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to exclude archived trips by default
     */
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    /**
     * Scope to get only archived trips
     */
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Scope to get current/future scheduled trips
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_time', '>=', Carbon::now())
                     ->whereNull('archived_at');
    }

    /**
     * Scope to get trips that should be archived (7+ days overdue)
     */
    public function scopeReadyToArchive($query)
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        return $query->where('scheduled_time', '<', $sevenDaysAgo)
                     ->where('status', 'scheduled')
                     ->whereNull('archived_at');
    }

    /**
     * Check if trip is overdue
     */
    public function isOverdue(): bool
    {
        return $this->scheduled_time < Carbon::now() 
               && !in_array($this->status, ['completed', 'cancelled', 'archived', 'in-transit']);
    }

    /**
     * Check if trip is ready to be archived (7+ days overdue)
     */
    public function isReadyToArchive(): bool
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        return $this->scheduled_time < $sevenDaysAgo
               && $this->status === 'scheduled'
               && !$this->archived_at;
    }

    /**
     * Get days until auto-archive (negative means overdue)
     */
    public function daysUntilArchive(): int
    {
        if ($this->archived_at) {
            return 0;
        }
        
        $sevenDaysAfterSchedule = $this->scheduled_time->copy()->addDays(7);
        return Carbon::now()->diffInDays($sevenDaysAfterSchedule, false);
    }

    /**
     * Archive this trip
     */
    public function archive()
    {
        $this->update([
            'status' => 'archived',
            'archived_at' => Carbon::now()
        ]);
    }

    public function deliveryRequest()
    {
        return $this->belongsTo(DeliveryRequest::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function updates()
    {
        return $this->hasMany(TripUpdate::class);
    }

    public function notifications()
    {
        return $this->hasMany(ClientNotification::class);
    }
}

