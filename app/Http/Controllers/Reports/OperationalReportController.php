<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OperationalReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        // Get trips with financial data
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereNotNull('trip_rate')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate totals
        $totalRevenue = $trips->sum('trip_rate');
        $totalPayroll = $trips->sum('driver_payroll');
        $totalAllowance = $trips->sum('driver_allowance');
        $totalAdditionalCharges = $trips->sum('additional_charge_20ft') + $trips->sum('additional_charge_50');
        $totalProfit = $totalRevenue - $totalPayroll - $totalAllowance;
        
        // Monthly breakdown
        $monthlyData = Trip::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(trip_rate) as revenue'),
                DB::raw('SUM(driver_payroll) as payroll'),
                DB::raw('SUM(driver_allowance) as allowance'),
                DB::raw('SUM(trip_rate - driver_payroll - COALESCE(driver_allowance, 0)) as profit')
            )
            ->whereNotNull('trip_rate')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        return view('dispatch.reports.financial', compact(
            'trips',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalPayroll',
            'totalAllowance',
            'totalAdditionalCharges',
            'totalProfit',
            'monthlyData'
        ));
    }
}
