<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Traits\Auditable;

class Vehicle extends Model
{
    use SoftDeletes, Auditable;
    protected $fillable = ['plate_number', 'vehicle_type', 'trailer_type', 'photo_path', 'status', 'deleted_by'];

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

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }

        $disk = $this->mediaDisk();

        return $disk === 's3'
            ? Storage::disk($disk)->temporaryUrl($this->photo_path, now()->addHours(6))
            : Storage::disk($disk)->url($this->photo_path);
    }

    public function mediaDisk(): string
    {
        return config('filesystems.default') === 's3' ? 's3' : 'public';
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



