<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'contact_method',
        'atw_reference',
        'pickup_location',
        'delivery_location',
        'container_size',
        'container_type',
        'preferred_schedule',
        'status',
        'notes',
        'atw_verified'
    ];

    protected $casts = [
        'preferred_schedule' => 'datetime',
        'atw_verified' => 'boolean'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function trip()
    {
        return $this->hasOne(Trip::class);
    }
}
