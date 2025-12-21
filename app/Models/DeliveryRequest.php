<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DeliveryRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'client_id',
        'contact_method',
        'atw_reference',
        'eir_number',
        'booking_number',
        'container_number',
        'seal_number',
        'pickup_location',
        'delivery_location',
        'container_size',
        'container_type',
        'shipping_line',
        'shipper_name',
        'preferred_schedule',
        'eir_time',
        'container_status',
        'dispatcher_id',
        'status',
        'notes',
        'atw_verified',
        'archived_at',
    ];

    protected $casts = [
        'preferred_schedule' => 'datetime',
        'atw_verified' => 'boolean',
        'archived_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to exclude archived requests by default
     */
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    /**
     * Scope to get only archived requests
     */
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Scope to get current/future scheduled requests
     */
    public function scopeUpcoming($query)
    {
        return $query->where('preferred_schedule', '>=', Carbon::now())
                     ->whereNull('archived_at');
    }

    /**
     * Scope to get requests that should be archived (7+ days overdue)
     */
    public function scopeReadyToArchive($query)
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        return $query->where('preferred_schedule', '<', $sevenDaysAgo)
                     ->whereIn('status', ['pending', 'verified', 'assigned'])
                     ->whereNull('archived_at');
    }

    /**
     * Check if request is overdue
     */
    public function isOverdue(): bool
    {
        return $this->preferred_schedule < Carbon::now() 
               && !in_array($this->status, ['completed', 'cancelled', 'archived', 'in-transit']);
    }

    /**
     * Check if request is ready to be archived (7+ days overdue)
     */
    public function isReadyToArchive(): bool
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        return $this->preferred_schedule < $sevenDaysAgo
               && in_array($this->status, ['pending', 'verified', 'assigned'])
               && !$this->archived_at;
    }

    /**
     * Get days until auto-archive (negative means overdue)
     */
    public function daysUntilArchive(): int
    {
        if ($this->archived_at) {
            return 0;
        }
        
        $sevenDaysAfterSchedule = $this->preferred_schedule->copy()->addDays(7);
        return Carbon::now()->diffInDays($sevenDaysAfterSchedule, false);
    }

    /**
     * Archive this request
     */
    public function archive()
    {
        $this->update([
            'status' => 'archived',
            'archived_at' => Carbon::now()
        ]);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function trip()
    {
        return $this->hasOne(Trip::class);
    }

    /**
     * Get the dispatcher who served this request
     */
    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatcher_id');
    }

    /**
     * Calculate total revenue for this request
     */
    public function getTotalRevenueAttribute()
    {
        return $this->trips->sum(function ($trip) {
            return ($trip->trip_rate ?? 0) + 
                   ($trip->additional_charge_20ft ?? 0) + 
                   ($trip->additional_charge_50 ?? 0);
        });
    }

    /**
     * Calculate total cost for this request
     */
    public function getTotalCostAttribute()
    {
        return $this->trips->sum(function ($trip) {
            return ($trip->driver_payroll ?? 0) + 
                   ($trip->driver_allowance ?? 0);
        });
    }

    /**
     * Calculate profit margin
     */
    public function getProfitMarginAttribute()
    {
        return $this->total_revenue - $this->total_cost;
    }
}



