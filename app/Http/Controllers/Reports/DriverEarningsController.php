<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverEarningsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $driverId = $request->input('driver_id');
        
        // Get driver earnings summary
        $driverEarnings = Driver::select('drivers.*')
            ->withCount(['trips as total_trips' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('trips.created_at', [$startDate, $endDate . ' 23:59:59']);
            }])
            ->withSum(['trips as total_payroll' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('trips.created_at', [$startDate, $endDate . ' 23:59:59']);
            }], 'driver_payroll')
            ->withSum(['trips as total_allowance' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('trips.created_at', [$startDate, $endDate . ' 23:59:59']);
            }], 'driver_allowance')
            ->having('total_trips', '>', 0)
            ->orderBy('total_payroll', 'desc')
            ->get();
        
        // Get specific driver trips if selected
        $driverTrips = null;
        $selectedDriver = null;
        if ($driverId) {
            $selectedDriver = Driver::find($driverId);
            $driverTrips = Trip::with(['deliveryRequest.client'])
                ->where('driver_id', $driverId)
                ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        $allDrivers = Driver::orderBy('name')->get();
        
        return view('dispatch.reports.driver-earnings', compact(
            'driverEarnings',
            'startDate',
            'endDate',
            'driverTrips',
            'selectedDriver',
            'allDrivers',
            'driverId'
        ));
    }
}
