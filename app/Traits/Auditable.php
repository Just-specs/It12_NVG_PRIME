<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    /**
     * Boot the auditable trait
     */
    protected static function bootAuditable()
    {
        // Log when model is created
        static::created(function ($model) {
            AuditLog::log('created', $model, 
                "{->getAuditName()} was created", 
                null, 
                $model->getAttributes()
            );
        });

        // Log when model is updated
        static::updated(function ($model) {
            if ($model->isDirty() && !$model->isDeleting) {
                AuditLog::log('updated', $model, 
                    "{->getAuditName()} was updated", 
                    $model->getOriginal(), 
                    $model->getChanges()
                );
            }
        });

        // Log when model is soft deleted
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                // Soft delete - set deleted_by
                if (auth()->check()) {
                    $model->deleted_by = auth()->id();
                    $model->saveQuietly(); // Save without triggering events
                }
                
                AuditLog::log('deleted', $model, 
                    "{->getAuditName()} was deleted", 
                    $model->getAttributes(), 
                    null
                );
            } else {
                // Permanent delete
                AuditLog::log('permanently_deleted', $model, 
                    "{->getAuditName()} was permanently deleted", 
                    $model->getAttributes(), 
                    null
                );
            }
        });

        // Log when model is restored
        static::restored(function ($model) {
            $model->deleted_by = null;
            $model->saveQuietly();
            
            AuditLog::log('restored', $model, 
                "{->getAuditName()} was restored", 
                null, 
                $model->getAttributes()
            );
        });
    }

    /**
     * Get audit-friendly name for the model
     */
    public function getAuditName(): string
    {
        $class = class_basename($this);
        $name = $this->name ?? $this->plate_number ?? $this->id ?? 'Unknown';
        return "{} #{->id} ({})";
    }

    /**
     * Get user who deleted this record
     */
    public function deletedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }
}
