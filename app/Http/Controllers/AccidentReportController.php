<?php

namespace App\Http\Controllers;

use App\Models\AccidentReport;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AccidentReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        $query = AccidentReport::with(['trip.deliveryRequest.client', 'driver', 'vehicle', 'reportedBy'])
            ->orderBy('accident_date', 'desc');
        
        // Status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Date range filter
        if ($startDate) {
            $query->whereDate('accident_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('accident_date', '<=', $endDate);
        }
        
        $reports = $query->paginate(10)->withQueryString();
        
        $stats = [
            'total' => AccidentReport::count(),
            'pending' => AccidentReport::pending()->count(),
            'under_investigation' => AccidentReport::underInvestigation()->count(),
            'resolved' => AccidentReport::resolved()->count(),
        ];
        
        return view('dispatch.reports.accidents.index', compact('reports', 'stats', 'status', 'startDate', 'endDate'));
    }

    public function create(Request $request)
    {
        $tripId = $request->query('trip_id');
        $trip = $tripId ? Trip::with(['driver', 'vehicle', 'deliveryRequest.client'])->find($tripId) : null;
        
        $trips = Trip::whereIn('status', ['scheduled', 'in-transit', 'delayed', 'completed'])
            ->with(['driver', 'vehicle', 'deliveryRequest.client'])
            ->orderBy('scheduled_time', 'desc')
            ->get();
        
        return view('dispatch.reports.accidents.create', compact('trips', 'trip'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'accident_date' => 'required|date',
            'location' => 'required|string|max:255',
            'severity' => 'required|in:minor,moderate,severe,fatal',
            'description' => 'required|string',
            'injuries' => 'nullable|string',
            'vehicle_damage' => 'nullable|string',
            'other_party_info' => 'nullable|string',
            'police_report_filed' => 'boolean',
            'police_report_number' => 'nullable|string|max:100',
            'witness_info' => 'nullable|string',
            'action_taken' => 'nullable|string',
            'estimated_damage_cost' => 'nullable|numeric|min:0',
        ]);

        $trip = Trip::with(['driver', 'vehicle'])->findOrFail($validated['trip_id']);
        
        $report = AccidentReport::create([
            ...$validated,
            'driver_id' => $trip->driver_id,
            'vehicle_id' => $trip->vehicle_id,
            'reported_by' => auth()->id(),
            'status' => 'pending',
        ]);

        // Log to audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => 'accident_report_created',
            'model_type' => 'AccidentReport',
            'model_id' => $report->id,
            'description' => "Accident report #{$report->id} created for Trip #{$trip->id}",
            'new_values' => [
                'severity' => $report->severity,
                'location' => $report->location,
                'status' => $report->status,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('reports.accidents.index')
            ->with('success', 'Accident report created successfully.');
    }

    public function show(AccidentReport $accident)
    {
        $accident->load(['trip.deliveryRequest.client', 'driver', 'vehicle', 'reportedBy']);
        return view('dispatch.reports.accidents.show', compact('accident'));
    }

    public function edit(AccidentReport $accident)
    {
        $accident->load(['trip.deliveryRequest.client', 'driver', 'vehicle']);
        
        $trips = Trip::whereIn('status', ['scheduled', 'in-transit', 'delayed', 'completed'])
            ->with(['driver', 'vehicle', 'deliveryRequest.client'])
            ->orderBy('scheduled_time', 'desc')
            ->get();
        
        return view('dispatch.reports.accidents.edit', compact('accident', 'trips'));
    }

    public function update(Request $request, AccidentReport $accident)
    {
        $validated = $request->validate([
            'accident_date' => 'required|date',
            'location' => 'required|string|max:255',
            'severity' => 'required|in:minor,moderate,severe,fatal',
            'description' => 'required|string',
            'injuries' => 'nullable|string',
            'vehicle_damage' => 'nullable|string',
            'other_party_info' => 'nullable|string',
            'police_report_filed' => 'boolean',
            'police_report_number' => 'nullable|string|max:100',
            'witness_info' => 'nullable|string',
            'action_taken' => 'nullable|string',
            'estimated_damage_cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,under_investigation,resolved,closed',
            'resolution_notes' => 'nullable|string',
        ]);

        $oldValues = $accident->only(['severity', 'status', 'location']);
        
        if ($validated['status'] === 'resolved' || $validated['status'] === 'closed') {
            if (!$accident->resolved_at) {
                $validated['resolved_at'] = now();
            }
        }
        
        $accident->update($validated);

        // Log to audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => 'accident_report_updated',
            'model_type' => 'AccidentReport',
            'model_id' => $accident->id,
            'description' => "Accident report #{$accident->id} updated",
            'old_values' => $oldValues,
            'new_values' => $accident->only(['severity', 'status', 'location']),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('reports.accidents.show', $accident)
            ->with('success', 'Accident report updated successfully.');
    }

    public function destroy(AccidentReport $accident)
    {
        if (!in_array(auth()->user()->role, ['admin', 'head_dispatch'])) {
            abort(403, 'Only admin and head dispatch can delete accident reports.');
        }

        $accident->delete();

        // Log to audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => 'accident_report_deleted',
            'model_type' => 'AccidentReport',
            'model_id' => $accident->id,
            'description' => "Accident report #{$accident->id} deleted",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('reports.accidents.index')
            ->with('success', 'Accident report deleted successfully.');
    }

    public function exportPdf(AccidentReport $accident)
    {
        $accident->load(['trip.deliveryRequest.client', 'driver', 'vehicle', 'reportedBy']);
        
        // Return HTML view optimized for browser print-to-PDF
        return response()->view('dispatch.reports.pdf.accident-report', [
            'accident' => $accident,
            'print_pdf' => true,
        ])->header('Content-Type', 'text/html');
    }
}
