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
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'two_factor_required',
        'two_factor_skip_until',
        'two_factor_remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_recovery_codes' => 'array',
        'two_factor_confirmed_at' => 'datetime',
        'two_factor_skip_until' => 'datetime',
    ];

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isHeadDispatch()
    {
        return $this->role === 'head_dispatch';
    }

    public function isDispatch()
    {
        return in_array($this->role, ['dispatch', 'dispatcher']);
    }

    public function isDispatcher()
    {
        return $this->isDispatch();
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

    // Check if user can assign trips (admin and dispatch/dispatcher)
    public function canAssignTrips()
    {
        return in_array($this->role, ['admin', 'dispatch', 'dispatcher']);
    }

    public function hasTwoFactorEnabled(): bool
    {
        return !empty($this->two_factor_secret) && !empty($this->two_factor_confirmed_at) && (bool) $this->two_factor_required;
    }
}
