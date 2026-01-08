<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletionRequest extends Model
{
    protected $fillable = [
        'requested_by',
        'resource_type',
        'resource_id',
        'reason',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Get the actual resource being requested for deletion
    public function resource()
    {
        return match($this->resource_type) {
            'driver' => Driver::withTrashed()->find($this->resource_id),
            'client' => Client::withTrashed()->find($this->resource_id),
            'vehicle' => Vehicle::withTrashed()->find($this->resource_id),
            'delivery_request' => DeliveryRequest::withTrashed()->find($this->resource_id),
            default => null,
        };
    }

    // Get resource name/title
    public function getResourceNameAttribute()
    {
        $resource = $this->resource();
        
        if (!$resource) {
            return 'Resource not found';
        }

        return match($this->resource_type) {
            'driver' => $resource->name,
            'client' => $resource->name,
            'vehicle' => $resource->plate_number . ' (' . $resource->model . ')',
            'delivery_request' => 'ATW #' . $resource->atw_reference,
            default => 'Unknown',
        };
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
