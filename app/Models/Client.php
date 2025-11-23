<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'email', 'mobile', 'company'];

    public function deliveryRequests()
    {
        return $this->hasMany(DeliveryRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(ClientNotification::class);
    }
}
