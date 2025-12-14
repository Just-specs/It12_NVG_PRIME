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

    /**
     * Find similar plate numbers (case-insensitive)
     */
    public static function findSimilar(string $plateNumber, ?int $excludeId = null): array
    {
        $query = static::whereRaw('LOWER(plate_number) LIKE ?', ['%' . strtolower($plateNumber) . '%'])
            ->orWhereRaw('LOWER(plate_number) = ?', [strtolower($plateNumber)]);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->get(['id', 'plate_number', 'vehicle_type', 'trailer_type', 'status'])->toArray();
    }
}
