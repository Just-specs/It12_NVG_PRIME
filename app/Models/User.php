<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'google_id',
        'avatar',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDispatcher()
    {
        return $this->role === 'dispatcher';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Check if user can verify requests (admin and head_dispatch)
    public function canVerifyRequests()
    {
        return in_array($this->role, ['admin', 'head_dispatch']);
    }

    // Check if user can assign trips (admin and dispatcher)
    public function canAssignTrips()
    {
        return in_array($this->role, ['admin', 'dispatcher']);
    }
}
