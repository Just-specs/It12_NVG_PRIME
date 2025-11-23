<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = ['name', 'mobile', 'license_number', 'status'];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
