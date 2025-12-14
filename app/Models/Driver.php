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
}
