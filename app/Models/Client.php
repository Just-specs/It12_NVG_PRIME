<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'mobile', 'company'];

    public function deliveryRequests()
    {
        return $this->hasMany(DeliveryRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(ClientNotification::class);
    }

    /**
     * Find similar client names (case-insensitive)
     */
    public static function findSimilar(string $name, ?int $excludeId = null): array
    {
        $query = static::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%'])
            ->orWhereRaw('LOWER(name) = ?', [strtolower($name)]);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->get(['id', 'name', 'email', 'company'])->toArray();
    }
}
