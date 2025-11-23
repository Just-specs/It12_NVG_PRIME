<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'route_instructions'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime'
    ];

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
