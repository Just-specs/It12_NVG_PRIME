<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class AccidentReport extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'trip_id',
        'driver_id',
        'vehicle_id',
        'reported_by',
        'accident_date',
        'location',
        'severity',
        'description',
        'injuries',
        'vehicle_damage',
        'other_party_info',
        'police_report_filed',
        'police_report_number',
        'witness_info',
        'action_taken',
        'estimated_damage_cost',
        'status',
        'resolution_notes',
        'resolved_at',
    ];

    protected $casts = [
        'accident_date' => 'datetime',
        'resolved_at' => 'datetime',
        'police_report_filed' => 'boolean',
        'estimated_damage_cost' => 'decimal:2',
    ];

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderInvestigation($query)
    {
        return $query->where('status', 'under_investigation');
    }

    public function scopeResolved($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }
}
