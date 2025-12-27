<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Client::withCount(['deliveryRequests', 'deliveryRequests as completed_requests' => function ($query) {
            $query->where('status', 'completed');
        }]);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('name')
            ->paginate(5)
            ->withPath(route('clients.index'));

        $stats = [
            'total_clients' => Client::count(),
            'active_clients' => Client::whereHas('deliveryRequests')->count(),
        ];

        if ($request->ajax()) {
            $html = view('dispatch.clients.partials.table', compact('clients'))->render();
            return response()->json(['html' => $html]);
        }

        return view('dispatch.clients.index', compact('clients', 'stats'));
    }

    public function create()
    {
        return view('dispatch.clients.create');
    }

    public function checkDuplicate(Request $request)
    {
        $name = $request->input('name');
        $excludeId = $request->input('exclude_id');
        
        $similar = Client::findSimilar($name, $excludeId);
        
        return response()->json([
            'has_similar' => count($similar) > 0,
            'similar_clients' => $similar
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email',
            'mobile' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'confirm_duplicate' => 'nullable|boolean',
        ]);

        // Check for similar names unless user confirmed
        if (!$request->input('confirm_duplicate')) {
            $similar = Client::findSimilar($validated['name']);
            
            if (count($similar) > 0) {
                return response()->json([
                    'requires_confirmation' => true,
                    'similar_clients' => $similar,
                    'message' => 'Similar client names found. Do you want to proceed?'
                ], 200);
            }
        }

        $client = Client::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('clients.show', $client),
                'message' => 'Client added successfully.'
            ]);
        }

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client added successfully.');
    }

    public function show(Client $client)
    {
        $client->load('deliveryRequests');

        $recentRequests = $this->buildRecentRequestsPaginator($client);

        $stats = [
            'total_requests' => $client->deliveryRequests()->count(),
            'pending_requests' => $client->deliveryRequests()->where('status', 'pending')->count(),
            'completed_requests' => $client->deliveryRequests()->where('status', 'completed')->count(),
            'active_trips' => $client->deliveryRequests()
                ->whereHas('trip', function ($query) {
                    $query->where('status', 'in-transit');
                })->count(),
        ];

        return view('dispatch.clients.show', compact('client', 'stats', 'recentRequests'));
    }

    public function recentRequests(Request $request, Client $client)
    {
        $recentRequests = $this->buildRecentRequestsPaginator($client);

        if ($request->ajax()) {
            $html = view('dispatch.clients.partials.recent-requests', compact('recentRequests'))->render();

            return response()->json(['html' => $html]);
        }

        return redirect()->route('clients.show', $client);
    }

    protected function buildRecentRequestsPaginator(Client $client)
    {
        return $client->deliveryRequests()
            ->with('trip')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(3, ['*'], 'client_recent_page')
            ->withPath(route('clients.recent-requests', $client));
    }

    public function edit(Client $client)
    {
        return view('dispatch.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
            'mobile' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'confirm_duplicate' => 'nullable|boolean',
        ]);

        // Check for similar names unless user confirmed (excluding current client)
        if (!$request->input('confirm_duplicate')) {
            $similar = Client::findSimilar($validated['name'], $client->id);
            
            if (count($similar) > 0) {
                return response()->json([
                    'requires_confirmation' => true,
                    'similar_clients' => $similar,
                    'message' => 'Similar client names found. Do you want to proceed?'
                ], 200);
            }
        }

        $client->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('clients.show', $client),
                'message' => 'Client updated successfully.'
            ]);
        }

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        // Check if client has any active delivery requests
        if ($client->deliveryRequests()->whereNotIn('status', ['completed', 'cancelled'])->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete client with active delivery requests. Complete or cancel active requests first.');
        }

        // Soft delete the client (audit log is automatic via Auditable trait)
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    public function clientRequests(Client $client)
    {
        $requests = $client->deliveryRequests()
            ->with('trip')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dispatch.clients.requests', compact('client', 'requests'));
    }

    public function clientTrips(Client $client)
    {
        $trips = $client->deliveryRequests()
            ->with(['trip.driver', 'trip.vehicle'])
            ->whereHas('trip')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dispatch.clients.trips', compact('client', 'trips'));
    }

    public function activity(Client $client)
    {
        $recentActivity = $client->deliveryRequests()
            ->with(['trip.driver', 'trip.vehicle', 'trip.updates'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('dispatch.clients.activity', compact('client', 'recentActivity'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $clients = Client::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('mobile', 'LIKE', "%{$query}%")
            ->orWhere('company', 'LIKE', "%{$query}%")
            ->withCount('deliveryRequests')
            ->get();

        return view('dispatch.clients.search', compact('clients', 'query'));
    }

    public function exportExcel()
    {
        $clients = Client::withCount('deliveryRequests')->get();

        // You can use Laravel Excel package here
        // For now, return a simple CSV
        $filename = 'clients_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($clients) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Mobile', 'Company', 'Total Requests']);

            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->name,
                    $client->email,
                    $client->mobile,
                    $client->company,
                    $client->delivery_requests_count,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show deleted clients
     */
    public function deleted()
    {
        $clients = Client::onlyTrashed()
            ->with('deletedBy')
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);
        
        return view('dispatch.clients.deleted', compact('clients'));
    }

    /**
     * Restore a soft-deleted client
     */
    public function restore($id)
    {
        $client = Client::onlyTrashed()->findOrFail($id);
        $client->restore();
        
        return redirect()
            ->route('clients.index')
            ->with('success', 'Client restored successfully.');
    }

    /**
     * Permanently delete a client
     */
    public function forceDelete($id)
    {
        $client = Client::onlyTrashed()->findOrFail($id);
        
        // Check if client has any delivery requests
        if ($client->deliveryRequests()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot permanently delete client with existing delivery requests.');
        }
        
        $client->forceDelete();
        
        return redirect()
            ->route('clients.deleted')
            ->with('success', 'Client permanently deleted.');
    }
}


