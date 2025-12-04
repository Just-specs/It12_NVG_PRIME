<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientNotification extends Model
{
    protected $fillable = [
        'trip_id',
        'client_id',
        'notification_type',
        'mobile',
        'message',
        'sent'
    ];

    protected $casts = ['sent' => 'boolean'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
