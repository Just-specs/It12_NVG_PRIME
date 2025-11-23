<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripUpdate extends Model
{
    protected $fillable = [
        'trip_id',
        'update_type',
        'message',
        'location',
        'reported_by'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
