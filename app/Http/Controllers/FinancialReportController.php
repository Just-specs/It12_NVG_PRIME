<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\DeliveryRequest;
use App\Models\Driver;
use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    /**
     * Display financial dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'today');
        $dateRange = $this->getDateRange($period);

        $stats = [
            'total_revenue' => $this->getTotalRevenue($dateRange),
            'total_expenses' => $this->getTotalExpenses($dateRange),
            'total_trips' => $this->getTotalTrips($dateRange),
            'profit_margin' => 0,
        ];

        $stats['profit_margin'] = $stats['total_revenue'] - $stats['total_expenses'];

        // Get top performing drivers
        $topDrivers = $this->getTopDrivers($dateRange);

        // Get client revenue breakdown
        $clientRevenue = $this->getClientRevenue($dateRange);

        // Daily revenue trend (last 7 days)
        $dailyTrend = $this->getDailyRevenueTrend();

        return view('dispatch.reports.financial', compact(
            'stats',
            'topDrivers',
            'clientRevenue',
            'dailyTrend',
            'period'
        ));
    }

    /**
     * Get date range based on period
     */
    private function getDateRange($period)
    {
        switch ($period) {
            case 'today':
                return [Carbon::today(), Carbon::tomorrow()];
            case 'week':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
            case 'month':
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
            case 'year':
                return [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
            default:
                return [Carbon::today(), Carbon::tomorrow()];
        }
    }

    /**
     * Calculate total revenue
     */
    private function getTotalRevenue($dateRange)
    {
        return Trip::whereBetween('created_at', $dateRange)
            ->whereNotNull('trip_rate')
            ->sum(DB::raw('trip_rate + additional_charge_20ft + additional_charge_50'));
    }

    /**
     * Calculate total expenses
     */
    private function getTotalExpenses($dateRange)
    {
        return Trip::whereBetween('created_at', $dateRange)
            ->sum(DB::raw('COALESCE(driver_payroll, 0) + COALESCE(driver_allowance, 0)'));
    }

    /**
     * Get total trips count
     */
    private function getTotalTrips($dateRange)
    {
        return Trip::whereBetween('created_at', $dateRange)->count();
    }

    /**
     * Get top performing drivers
     */
    private function getTopDrivers($dateRange)
    {
        return Driver::select('drivers.*')
            ->selectRaw('COUNT(trips.id) as trips_count')
            ->selectRaw('SUM(COALESCE(trips.driver_payroll, 0) + COALESCE(trips.driver_allowance, 0)) as total_earnings')
            ->leftJoin('trips', 'drivers.id', '=', 'trips.driver_id')
            ->whereBetween('trips.created_at', $dateRange)
            ->groupBy('drivers.id')
            ->orderByDesc('total_earnings')
            ->limit(10)
            ->get();
    }

    /**
     * Get client revenue breakdown
     */
    private function getClientRevenue($dateRange)
    {
        return Client::select('clients.*')
            ->selectRaw('COUNT(trips.id) as trips_count')
            ->selectRaw('SUM(COALESCE(trips.trip_rate, 0) + COALESCE(trips.additional_charge_20ft, 0) + COALESCE(trips.additional_charge_50, 0)) as total_revenue')
            ->leftJoin('delivery_requests', 'clients.id', '=', 'delivery_requests.client_id')
            ->leftJoin('trips', 'delivery_requests.id', '=', 'trips.delivery_request_id')
            ->whereBetween('trips.created_at', $dateRange)
            ->groupBy('clients.id')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
    }

    /**
     * Get daily revenue trend for last 7 days
     */
    private function getDailyRevenueTrend()
    {
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = Trip::whereDate('created_at', $date)
                ->sum(DB::raw('COALESCE(trip_rate, 0) + COALESCE(additional_charge_20ft, 0) + COALESCE(additional_charge_50, 0)'));
            
            $expenses = Trip::whereDate('created_at', $date)
                ->sum(DB::raw('COALESCE(driver_payroll, 0) + COALESCE(driver_allowance, 0)'));

            $last7Days[] = [
                'date' => $date->format('M d'),
                'revenue' => $revenue,
                'expenses' => $expenses,
                'profit' => $revenue - $expenses,
            ];
        }

        return $last7Days;
    }

    /**
     * Export financial report
     */
    public function export(Request $request)
    {
        $period = $request->get('period', 'today');
        $dateRange = $this->getDateRange($period);

        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereBetween('created_at', $dateRange)
            ->whereNotNull('trip_rate')
            ->get();

        return view('dispatch.reports.financial-export', compact('trips', 'period', 'dateRange'));
    }
}
