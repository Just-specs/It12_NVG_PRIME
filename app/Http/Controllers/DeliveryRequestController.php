<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRequest;
use App\Models\Client;
use App\Services\CalendarificService;
use App\Services\DispatchService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class DeliveryRequestController extends Controller
{
    public function __construct(
        private readonly CalendarificService $calendarific,
        private readonly DispatchService $dispatchService
    ) {}

    public function index(Request $request)
    {
        // Add 'archived' status for admin users only
        $allowedStatuses = ['pending', 'verified', 'assigned'];
        if (auth()->user()->role === 'admin') {
            $allowedStatuses[] = 'archived';
        }
        $activeStatus = $request->query('status', 'all');

        // For archived status, use archived() scope instead of active()
        if ($request->query('status') === 'archived' && auth()->user()->role === 'admin') {
            $requestsQuery = DeliveryRequest::archived()->with('client');
        } else {
            $requestsQuery = DeliveryRequest::active()->with('client');
        }
        // Search functionality
        $search = $request->query('search');
        if ($search) {
            $requestsQuery->where(function ($query) use ($search) {
                $query->where('atw_reference', 'like', "%{$search}%")
                    ->orWhere('pickup_location', 'like', "%{$search}%")
                    ->orWhere('delivery_location', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }


        if ($activeStatus !== 'all') {
            if (in_array($activeStatus, $allowedStatuses, true)) {
                $requestsQuery->where('status', $activeStatus);
            } else {
                $activeStatus = 'all';
            }
        }

        if ($activeStatus === 'all') {
            $requestsQuery->orderByRaw("CASE WHEN status = 'pending' THEN 0 WHEN status = 'verified' THEN 1 WHEN status = 'assigned' THEN 2 ELSE 3 END");
        }

        $requests = $requestsQuery
            ->orderByDesc('preferred_schedule')
            ->orderByDesc('created_at')
            ->paginate(7);

        $requests->withPath(route('requests.index'));
        if ($activeStatus !== 'all') {
            $requests->appends(['status' => $activeStatus]);
        }
        if ($search) {
            $requests->appends(['search' => $search]);
        }

        $statusCounts = DeliveryRequest::active()->select('status')
            ->selectRaw('COUNT(*) as aggregate')
            ->whereIn('status', $allowedStatuses)
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $counts = [
            'all' => DeliveryRequest::active()->count(),
        ];

        foreach ($allowedStatuses as $status) {
            $counts[$status] = $statusCounts[$status] ?? 0;
        }

        if ($request->ajax()) {
            $html = view('dispatch.requests.partials.table', compact('requests'))->render();

            return response()->json([
                'html' => $html,
                'status' => $activeStatus,
                'counts' => $counts,
            ]);
        }

        return view('dispatch.requests.index', compact('requests', 'activeStatus', 'counts'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();

        $holidays = collect();
        $calendarificError = null;

        if (!config('services.calendarific.key')) {
            $calendarificError = 'Set the Calendarific API key to load national holidays.';
        } else {
            try {
                $holidays = $this->calendarific
                    ->holidays('PH', (int) now()->year, 'national')
                    ->map(function (array $holiday) {
                        $dateIso = data_get($holiday, 'date.iso');
                        $date = $dateIso ? Carbon::parse($dateIso) : null;

                        return [
                            'name' => data_get($holiday, 'name'),
                            'date_iso' => $dateIso,
                            'date' => $date?->toDateString(),
                            'date_display' => $date?->format('M d, Y'),
                            'type' => data_get($holiday, 'type.0'),
                            'description' => data_get($holiday, 'description'),
                            'is_future' => $date?->isToday() || $date?->isFuture(),
                        ];
                    })
                    ->filter(fn($holiday) => $holiday['is_future'] ?? false)
                    ->sortBy('date_iso')
                    ->values()
                    ->take(5);
            } catch (RequestException $exception) {
                report($exception);
                $calendarificError = 'Unable to load holidays right now.';
            }
        }

        return view('dispatch.requests.create', compact('clients', 'holidays', 'calendarificError'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'contact_method' => 'required|in:mobile,email,group_chat',
            'atw_reference' => 'required|string',
            'pickup_location' => 'required|string',
            'delivery_location' => 'required|string',
            'container_size' => 'required|string',
            'container_type' => 'required|string',
            'preferred_schedule' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
            'auto_assign' => 'nullable|boolean',
        ]);

        // Check for duplicate schedule
        $preferredSchedule = Carbon::parse($validated['preferred_schedule']);
        $duplicateRequest = DeliveryRequest::active()->where('client_id', $validated['client_id'])
            ->where('preferred_schedule', $preferredSchedule)
            ->whereIn('status', ['pending', 'verified', 'assigned'])
            ->exists();

        if ($duplicateRequest) {
            throw ValidationException::withMessages([
                'preferred_schedule' => 'This client already has a delivery request scheduled at ' . $preferredSchedule->format('Y-m-d H:i') . '. Please choose a different time.',
            ]);
        }

        // Debug: Log the data being saved
        \Log::info('Creating delivery request with data:', $validated);
        
        $deliveryRequest = DeliveryRequest::create([
            'client_id' => $validated['client_id'],
            'contact_method' => $validated['contact_method'],
            'atw_reference' => $validated['atw_reference'],
            'eir_number' => $request->input('eir_number'),
            'booking_number' => $request->input('booking_number'),
            'container_number' => $request->input('container_number'),
            'seal_number' => $request->input('seal_number'),
            'pickup_location' => $validated['pickup_location'],
            'delivery_location' => $validated['delivery_location'],
            'preferred_schedule' => $validated['preferred_schedule'],
            'container_size' => $validated['container_size'],
            'container_type' => $validated['container_type'],
            'notes' => $validated['notes'] ?? '',
            'status' => 'pending',
        ]);

        // Refresh the model to ensure all attributes are loaded
        $deliveryRequest->refresh();
        
        // Debug: Log the refreshed model
        \Log::info('Delivery request after refresh:', $deliveryRequest->toArray());

        // Check if auto-assignment is requested
        if ($request->input('auto_assign', false)) {
            return $this->attemptAutoAssignment($deliveryRequest);
        }

        return redirect()
            ->route('requests.show', $deliveryRequest)
            ->with('success', 'Delivery request created successfully. You can verify and assign it when ready.');
    }

    public function show(DeliveryRequest $deliveryRequest)
    {
        // Refresh and load relationships
        $deliveryRequest->refresh();
        $deliveryRequest->load(['client', 'trip.driver', 'trip.vehicle']);
        
        // Debug: Log what we're showing
        \Log::info('Showing delivery request:', [
            'id' => $deliveryRequest->id,
            'client_id' => $deliveryRequest->client_id,
            'client_name' => $deliveryRequest->client?->name,
            'atw_reference' => $deliveryRequest->atw_reference,
            'pickup_location' => $deliveryRequest->pickup_location,
            'delivery_location' => $deliveryRequest->delivery_location,
        ]);
        $clients = Client::orderBy('name')->get();
        return view('dispatch.requests.show', compact('deliveryRequest', 'clients'));
    }

    public function verify(DeliveryRequest $deliveryRequest)
    {
        // Authorization: Only admin and head-of-dispatch can verify
        if (!auth()->user()->canVerifyRequests()) {
            abort(403, 'Only Admin can verify delivery requests.');
        }

        $deliveryRequest->update([
            'atw_verified' => true,
            'status' => 'verified'
        ]);

        return redirect()
            ->back()
            ->with('success', 'ATW verified successfully. Dispatchers can now assign this request to a trip.');
    }

    /**
     * Verify AND automatically assign the request to a trip
     */
    public function verifyAndAssign(DeliveryRequest $deliveryRequest)
    {
        // Authorization: Only admin and head-of-dispatch can verify
        if (!auth()->user()->canVerifyRequests()) {
            abort(403, 'Only Admin can verify delivery requests.');
        }

        // First verify
        $deliveryRequest->update([
            'atw_verified' => true,
            'status' => 'verified'
        ]);

        // Then attempt auto-assignment
        return $this->attemptAutoAssignment($request);
    }

    /**
     * Auto-assign delivery request to available resources
     */
    public function autoAssign(DeliveryRequest $deliveryRequest)
    {
        // Authorization: Only admin and head-of-dispatch can verify
        if (!auth()->user()->canVerifyRequests()) {
            abort(403, 'Only Admin can verify and auto-assign delivery requests.');
        }

        if (!in_array($deliveryRequest->status, ['pending', 'verified'])) {
            return redirect()
                ->back()
                ->with('error', 'This request cannot be auto-assigned. Status: ' . $deliveryRequest->status);
        }

        // Verify if not already verified
        if (!$deliveryRequest->atw_verified) {
            $deliveryRequest->update([
                'atw_verified' => true,
                'status' => 'verified'
            ]);
        }

        return $this->attemptAutoAssignment($request);
    }

    /**
     * Helper method to attempt automatic trip assignment
     */
    private function attemptAutoAssignment(DeliveryRequest $deliveryRequest)
    {
        try {
            // Get available resources
            $drivers = $this->dispatchService->getAvailableDrivers();
            $vehicles = $this->dispatchService->getAvailableVehicles();

            // Check if resources are available
            if ($drivers->isEmpty()) {
                return redirect()
                    ->route('requests.show', $deliveryRequest)
                    ->with('warning', 'Delivery request created, but no drivers are currently available. Please assign manually when resources become available.');
            }

            if ($vehicles->isEmpty()) {
                return redirect()
                    ->route('requests.show', $deliveryRequest)
                    ->with('warning', 'Delivery request created, but no vehicles are currently available. Please assign manually when resources become available.');
            }

            // Auto-select first available driver and vehicle
            $selectedDriver = $drivers->first();
            $selectedVehicle = $vehicles->first();

            // Create trip using dispatch service (this will now check for duplicate schedules)
            $trip = $this->dispatchService->assignTrip(
                $deliveryRequest->id,
                $selectedDriver->id,
                $selectedVehicle->id,
                $deliveryRequest->preferred_schedule,
                "Auto-assigned by system"
            );

            // Send notification
            $this->sendClientNotification($trip, 'scheduled', 'Your delivery has been scheduled.');

            return redirect()
                ->route('trips.show', $trip)
                ->with('success', "Delivery request assigned successfully! Driver: {$selectedDriver->name}, Vehicle: {$selectedVehicle->plate_number}");
        } catch (\Exception $e) {
            return redirect()
                ->route('requests.show', $deliveryRequest)
                ->with('error', 'Auto-assignment failed: ' . $e->getMessage() . '. Please assign manually.');
        }
    }

    /**
     * Helper to send client notification
     */
    private function sendClientNotification($trip, string $type, string $message)
    {
        $client = $trip->deliveryRequest->client;

        \App\Models\ClientNotification::create([
            'trip_id' => $trip->id,
            'client_id' => $client->id,
            'notification_type' => $type,
            'message' => $message,
            'method' => $trip->deliveryRequest->contact_method ?? 'sms',
            'sent' => false
        ]);

        \Log::info("Notification created for client: {$client->name}", [
            'trip_id' => $trip->id,
            'type' => $type,
            'message' => $message
        ]);
    }

    public function edit(DeliveryRequest $deliveryRequest)
    {
        $clients = \App\Models\Client::orderBy('name')->get();
        return view('dispatch.requests.edit', compact('request', 'clients'));
    }

    public function update(Request $httpRequest, DeliveryRequest $deliveryRequest)
    {
        $validated = $validateRequest->validate([
            'client_id' => 'required|exists:clients,id',
            'contact_method' => 'required|in:mobile,email,group_chat',
            'atw_reference' => 'required|string',
            'pickup_location' => 'required|string',
            'delivery_location' => 'required|string',
            'container_size' => 'required|string',
            'container_type' => 'required|string',
            'preferred_schedule' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        // Check for duplicate schedule when updating (exclude current request)
        $preferredSchedule = Carbon::parse($validated['preferred_schedule']);
        $duplicateRequest = DeliveryRequest::active()->where('client_id', $validated['client_id'])
            ->where('preferred_schedule', $preferredSchedule)
            ->where('id', '!=', $deliveryRequest->id)
            ->whereIn('status', ['pending', 'verified', 'assigned'])
            ->exists();

        if ($duplicateRequest) {
            throw ValidationException::withMessages([
                'preferred_schedule' => 'This client already has another delivery request scheduled at ' . $preferredSchedule->format('Y-m-d H:i') . '. Please choose a different time.',
            ]);
        }

        $deliveryRequest->update($validated);

        return redirect()
            ->route('requests.show', $request)
            ->with('success', 'Request updated successfully.');
    }

    public function destroy(DeliveryRequest $deliveryRequest)
    {
        // Check if request has a trip assigned
        if ($deliveryRequest->trip && $deliveryRequest->trip->status !== 'cancelled') {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete request with active trip.');
        }

        $deliveryRequest->delete();

        return redirect()
            ->route('requests.index')
            ->with('success', 'Request deleted successfully.');
    }

    public function cancel(DeliveryRequest $deliveryRequest)
    {
        $deliveryRequest->update(['status' => 'cancelled']);

        // Cancel associated trip if exists
        if ($deliveryRequest->trip) {
            $deliveryRequest->trip->update(['status' => 'cancelled']);

            // Free up resources
            \App\Models\Driver::where('id', $deliveryRequest->trip->driver_id)
                ->update(['status' => 'available']);
            \App\Models\Vehicle::where('id', $deliveryRequest->trip->vehicle_id)
                ->update(['status' => 'available']);
        }

        return redirect()
            ->back()
            ->with('success', 'Request cancelled successfully.');
    }

    public function filterByStatus($status)
    {
        $requests = DeliveryRequest::active()->with('client')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dispatch.requests.index', compact('requests'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $requests = DeliveryRequest::active()->with('client')
            ->where('atw_reference', 'LIKE', "%{$query}%")
            ->orWhere('pickup_location', 'LIKE', "%{$query}%")
            ->orWhere('delivery_location', 'LIKE', "%{$query}%")
            ->orWhereHas('client', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dispatch.requests.search', compact('requests', 'query'));
    }

    public function exportExcel()
    {
        $requests = DeliveryRequest::active()->with('client')->get();

        $filename = 'delivery_requests_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($requests) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID',
                'Client',
                'ATW Reference',
                'Contact Method',
                'Pickup Location',
                'Delivery Location',
                'Container Size',
                'Container Type',
                'Preferred Schedule',
                'Status',
                'Created At'
            ]);

            foreach ($requests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->client->name,
                    $request->atw_reference,
                    $request->contact_method,
                    $request->pickup_location,
                    $request->delivery_location,
                    $request->container_size,
                    $request->container_type,
                    $request->preferred_schedule->format('Y-m-d H:i'),
                    $request->status,
                    $request->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $requests = DeliveryRequest::active()->with('client')->get();
        return redirect()->back()->with('info', 'PDF export feature requires DomPDF package.');
    }

    public function importForm()
    {
        return view('dispatch.requests.import');
    }

    public function importRequests(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);

        return redirect()
            ->route('requests.index')
            ->with('info', 'Import feature coming soon.');
    }
}











