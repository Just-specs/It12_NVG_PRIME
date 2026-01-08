<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Driver extends Model
{
    use SoftDeletes, Auditable;
    protected $fillable = ['name', 'mobile', 'license_number',  'status', 'deleted_by'];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function activeTrips()
    {
        return $this->hasMany(Trip::class)->whereNull('trips.deleted_at');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Find similar driver names or license numbers (case-insensitive)
     */
    public static function findSimilar(string $name = null, string $licenseNumber = null, ?int $excludeId = null): array
    {
        $query = static::query();
        $hasCondition = false;

        if ($name) {
            $query->where(function($q) use ($name) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%'])
                  ->orWhereRaw('LOWER(name) = ?', [strtolower($name)]);
            });
            $hasCondition = true;
        }

        if ($licenseNumber) {
            if ($hasCondition) {
                $query->orWhereRaw('LOWER(license_number) = ?', [strtolower($licenseNumber)]);
            } else {
                $query->whereRaw('LOWER(license_number) = ?', [strtolower($licenseNumber)]);
            }
        }
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->get(['id', 'name', 'mobile', 'license_number', 'status'])->toArray();
    }
    // Co-driver relationships (many-to-many)
    public function coDrivers()
    {
        return $this->belongsToMany(Driver::class, 'co_drivers', 'driver_id', 'co_driver_id')
            ->withTimestamps();
    }
    
    // Inverse relationship - drivers who have this driver as co-driver
    public function driversHavingAsCoDriver()
    {
        return $this->belongsToMany(Driver::class, 'co_drivers', 'co_driver_id', 'driver_id')
            ->withTimestamps();
    }
    
    // Get all co-driver relationships (both directions)
    public function getAllCoDrivers()
    {
        return $this->coDrivers->merge($this->driversHavingAsCoDriver)->unique('id');
    }
    
    // Check if this driver has a specific co-driver
    public function hasCoDriver($driverId)
    {
        return $this->coDrivers->contains('id', $driverId) || 
               $this->driversHavingAsCoDriver->contains('id', $driverId);
    }
}