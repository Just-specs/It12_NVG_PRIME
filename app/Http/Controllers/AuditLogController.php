<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs
     */
    public function index(Request $request)
    {
        $this->authorizeAdminOnly();

        $query = AuditLog::query()->with('user');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(7);

        // Get unique actions and model types for filters
        $actions = AuditLog::distinct()->pluck('action');
        $modelTypes = AuditLog::distinct()->pluck('model_type');

        return view('admin.audit-logs.index', compact('logs', 'actions', 'modelTypes'));
    }

    /**
     * Display the specified audit log
     */
    public function show(AuditLog $auditLog)
    {
        $this->authorizeAdminOnly();

        $auditLog->load('user');
        
        return view('admin.audit-logs.show', compact('auditLog'));
    }

    /**
     * Get audit logs for a specific model
     */
    public function forModel($modelType, $modelId)
    {
        $this->authorizeAdminOnly();

        $logs = AuditLog::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(7);

        return view('admin.audit-logs.index', compact('logs'));
    }

    private function authorizeAdminOnly(): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Only Admin can access audit logs.');
        }
    }
}