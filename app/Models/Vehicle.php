<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['plate_number', 'vehicle_type', 'trailer_type', 'status'];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
