<?php

namespace App\Http\Controllers;

use App\Models\DeletionRequest;
use App\Models\Driver;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\DeliveryRequest;
use Illuminate\Http\Request;

class DeletionRequestController extends Controller
{
    /**
     * Display all deletion requests (Admin view)
     */
    public function index()
    {
        // Only admin can view deletion requests
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only Admin can manage deletion requests.');
        }

        $requests = DeletionRequest::with(['requestedBy', 'reviewedBy'])
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'pending' => DeletionRequest::pending()->count(),
            'approved' => DeletionRequest::approved()->count(),
            'rejected' => DeletionRequest::rejected()->count(),
        ];

        return view('dispatch.deletion-requests.index', compact('requests', 'stats'));
    }

    /**
     * Show specific deletion request
     */
    public function show(DeletionRequest $deletionRequest)
    {
        $deletionRequest->load(['requestedBy', 'reviewedBy']);
        $resource = $deletionRequest->resource();

        return view('dispatch.deletion-requests.show', compact('deletionRequest', 'resource'));
    }

    /**
     * Approve deletion request
     */
    public function approve(Request $request, DeletionRequest $deletionRequest)
    {
        // Only admin can approve
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only Admin can approve deletion requests.');
        }

        if ($deletionRequest->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'This deletion request has already been reviewed.');
        }

        $validated = $request->validate([
            'review_notes' => 'nullable|string|max:500',
        ]);

        // Update deletion request status
        $deletionRequest->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'review_notes' => $validated['review_notes'] ?? null,
            'reviewed_at' => now(),
        ]);

        // Perform the actual deletion
        $resource = $deletionRequest->resource();
        if ($resource) {
            $resource->delete();
        }

        return redirect()
            ->route('deletion-requests.index')
            ->with('success', ucfirst($deletionRequest->resource_type) . ' deletion approved and executed successfully.');
    }

    /**
     * Reject deletion request
     */
    public function reject(Request $request, DeletionRequest $deletionRequest)
    {
        // Only admin can reject
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only Admin can reject deletion requests.');
        }

        if ($deletionRequest->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'This deletion request has already been reviewed.');
        }

        $validated = $request->validate([
            'review_notes' => 'required|string|max:500',
        ]);

        $deletionRequest->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'review_notes' => $validated['review_notes'],
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('deletion-requests.index')
            ->with('success', 'Deletion request rejected successfully.');
    }

    /**
     * Get pending deletion requests count (for notifications)
     */
    public function getPendingCount()
    {
        $count = DeletionRequest::pending()->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }
}
