<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\DeliveryRequest;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'today_trips' => Trip::whereDate('scheduled_time', Carbon::today())->count(),
            'week_trips' => Trip::whereBetween('scheduled_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'month_trips' => Trip::whereMonth('scheduled_time', Carbon::now()->month)->count(),
            'total_drivers' => Driver::count(),
            'total_vehicles' => Vehicle::count(),
            'total_clients' => Client::count(),
        ];

        // Status distribution for pie chart
        $statusData = [
            'completed' => Trip::where('status', 'completed')->count(),
            'in_transit' => Trip::where('status', 'in-transit')->count(),
            'scheduled' => Trip::where('status', 'scheduled')->count(),
            'cancelled' => Trip::where('status', 'cancelled')->count(),
        ];

        // Weekly trips for bar chart (last 7 days)
        $weeklyTripsData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyTripsData[] = [
                'date' => $date->format('M d'),
                'count' => Trip::whereDate('scheduled_time', $date)->count(),
            ];
        }

        // Monthly trips for bar chart (last 6 months)
        $monthlyTripsData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyTripsData[] = [
                'month' => $month->format('M Y'),
                'count' => Trip::whereMonth('scheduled_time', $month->month)
                    ->whereYear('scheduled_time', $month->year)
                    ->count(),
            ];
        }

        // Driver performance top 5
        $topDrivers = Driver::withCount(['trips as completed_trips' => function ($query) {
            $query->where('status', 'completed');
        }])
            ->orderByDesc('completed_trips')
            ->limit(5)
            ->get()
            ->map(function ($driver) {
                return [
                    'name' => $driver->name,
                    'completed' => $driver->completed_trips,
                ];
            });

        return view('dispatch.reports.index', compact('stats', 'statusData', 'weeklyTripsData', 'monthlyTripsData', 'topDrivers'));
    }

    public function dailyReport(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereDate('scheduled_time', $date)
            ->orderBy('scheduled_time')
            ->get();

        $stats = [
            'total_trips' => $trips->count(),
            'completed' => $trips->where('status', 'completed')->count(),
            'in_transit' => $trips->where('status', 'in-transit')->count(),
            'scheduled' => $trips->where('status', 'scheduled')->count(),
            'cancelled' => $trips->where('status', 'cancelled')->count(),
        ];

        // Chart data for status distribution
        $statusChartData = [
            'labels' => ['Completed', 'In Transit', 'Scheduled', 'Cancelled'],
            'data' => [
                $stats['completed'],
                $stats['in_transit'],
                $stats['scheduled'],
                $stats['cancelled'],
            ],
            'colors' => ['#10b981', '#3b82f6', '#6b7280', '#ef4444'],
        ];

        // Hourly distribution for bar chart
        $hourlyData = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourlyData[] = [
                'hour' => $hour . ':00',
                'count' => $trips->filter(function ($trip) use ($hour) {
                    return $trip->scheduled_time->hour == $hour;
                })->count(),
            ];
        }

        return view('dispatch.reports.daily', compact('trips', 'stats', 'date', 'statusChartData', 'hourlyData'));
    }

    public function weeklyReport(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();

        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereBetween('scheduled_time', [$startDate, $endDate])
            ->orderBy('scheduled_time')
            ->get();

        $stats = [
            'total_trips' => $trips->count(),
            'completed' => $trips->where('status', 'completed')->count(),
            'in_transit' => $trips->where('status', 'in-transit')->count(),
            'scheduled' => $trips->where('status', 'scheduled')->count(),
            'cancelled' => $trips->where('status', 'cancelled')->count(),
        ];

        // Group by day
        $tripsByDay = $trips->groupBy(function ($trip) {
            return $trip->scheduled_time->format('Y-m-d');
        });

        // Chart data
        $statusChartData = [
            'labels' => ['Completed', 'In Transit', 'Scheduled', 'Cancelled'],
            'data' => [
                $stats['completed'],
                $stats['in_transit'],
                $stats['scheduled'],
                $stats['cancelled'],
            ],
            'colors' => ['#10b981', '#3b82f6', '#6b7280', '#ef4444'],
        ];

        // Daily distribution for bar chart
        $dailyData = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateKey = $currentDate->format('Y-m-d');
            $dailyData[] = [
                'date' => $currentDate->format('M d'),
                'count' => $tripsByDay->get($dateKey, collect())->count(),
            ];
            $currentDate->addDay();
        }

        return view('dispatch.reports.weekly', compact('trips', 'stats', 'startDate', 'endDate', 'tripsByDay', 'statusChartData', 'dailyData'));
    }

    public function monthlyReport(Request $request)
    {
        $month = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();
        $startDate = $month->copy()->startOfMonth();
        $endDate = $month->copy()->endOfMonth();

        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereBetween('scheduled_time', [$startDate, $endDate])
            ->orderBy('scheduled_time')
            ->get();

        $stats = [
            'total_trips' => $trips->count(),
            'completed' => $trips->where('status', 'completed')->count(),
            'in_transit' => $trips->where('status', 'in-transit')->count(),
            'scheduled' => $trips->where('status', 'scheduled')->count(),
            'cancelled' => $trips->where('status', 'cancelled')->count(),
        ];

        // Group by week
        $tripsByWeek = $trips->groupBy(function ($trip) {
            return $trip->scheduled_time->weekOfYear;
        });

        // Chart data
        $statusChartData = [
            'labels' => ['Completed', 'In Transit', 'Scheduled', 'Cancelled'],
            'data' => [
                $stats['completed'],
                $stats['in_transit'],
                $stats['scheduled'],
                $stats['cancelled'],
            ],
            'colors' => ['#10b981', '#3b82f6', '#6b7280', '#ef4444'],
        ];

        // Weekly distribution for bar chart
        $weeklyData = [];
        $currentWeek = $startDate->copy()->startOfWeek();
        while ($currentWeek <= $endDate) {
            $weekEnd = $currentWeek->copy()->endOfWeek();
            if ($weekEnd > $endDate) {
                $weekEnd = $endDate;
            }
            $weekTrips = $trips->filter(function ($trip) use ($currentWeek, $weekEnd) {
                return $trip->scheduled_time >= $currentWeek && $trip->scheduled_time <= $weekEnd;
            });
            $weeklyData[] = [
                'week' => 'Week ' . $currentWeek->weekOfYear . ' (' . $currentWeek->format('M d') . ')',
                'count' => $weekTrips->count(),
            ];
            $currentWeek->addWeek();
        }

        return view('dispatch.reports.monthly', compact('trips', 'stats', 'month', 'tripsByWeek', 'statusChartData', 'weeklyData'));
    }

    public function yearlyReport(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereYear('scheduled_time', $year)
            ->orderBy('scheduled_time')
            ->get();

        $stats = [
            'total_trips' => $trips->count(),
            'completed' => $trips->where('status', 'completed')->count(),
            'in_transit' => $trips->where('status', 'in-transit')->count(),
            'scheduled' => $trips->where('status', 'scheduled')->count(),
            'cancelled' => $trips->where('status', 'cancelled')->count(),
        ];

        // Group by month
        $tripsByMonth = $trips->groupBy(function ($trip) {
            return $trip->scheduled_time->format('Y-m');
        });

        return view('dispatch.reports.yearly', compact('trips', 'stats', 'year', 'tripsByMonth'));
    }

    public function customReport(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();

        $query = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereBetween('scheduled_time', [$startDate, $endDate]);

        // Apply filters
        if ($request->has('driver_id') && $request->driver_id) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->has('vehicle_id') && $request->vehicle_id) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->has('client_id') && $request->client_id) {
            $query->whereHas('deliveryRequest', function ($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $trips = $query->orderBy('scheduled_time')->get();

        $stats = [
            'total_trips' => $trips->count(),
            'completed' => $trips->where('status', 'completed')->count(),
            'in_transit' => $trips->where('status', 'in-transit')->count(),
            'scheduled' => $trips->where('status', 'scheduled')->count(),
            'cancelled' => $trips->where('status', 'cancelled')->count(),
        ];

        $drivers = Driver::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('plate_number')->get();
        $clients = Client::orderBy('name')->get();

        return view('dispatch.reports.custom', compact('trips', 'stats', 'startDate', 'endDate', 'drivers', 'vehicles', 'clients'));
    }

    public function driverPerformance()
    {
        $drivers = Driver::withCount([
            'trips',
            'trips as completed_trips' => function ($query) {
                $query->where('status', 'completed');
            },
            'trips as cancelled_trips' => function ($query) {
                $query->where('status', 'cancelled');
            }
        ])
            ->with(['trips' => function ($query) {
                $query->where('status', 'completed')
                    ->whereNotNull('actual_start_time')
                    ->whereNotNull('actual_end_time');
            }])
            ->get();

        // Calculate performance metrics for each driver
        $driverMetrics = $drivers->map(function ($driver) {
            $completedTrips = $driver->trips;

            $avgDuration = $completedTrips->avg(function ($trip) {
                if ($trip->actual_start_time && $trip->actual_end_time) {
                    return $trip->actual_end_time->diffInMinutes($trip->actual_start_time);
                }
                return 0;
            });

            $completionRate = $driver->trips_count > 0
                ? round(($driver->completed_trips / $driver->trips_count) * 100, 2)
                : 0;

            return [
                'driver' => $driver,
                'total_trips' => $driver->trips_count,
                'completed' => $driver->completed_trips,
                'cancelled' => $driver->cancelled_trips,
                'completion_rate' => $completionRate,
                'avg_duration_minutes' => round($avgDuration, 2),
            ];
        });

        return view('dispatch.reports.driver-performance', compact('driverMetrics'));
    }

    public function vehicleUtilization()
    {
        $vehicles = Vehicle::withCount([
            'trips',
            'trips as completed_trips' => function ($query) {
                $query->where('status', 'completed');
            }
        ])->get();

        $vehicleMetrics = $vehicles->map(function ($vehicle) {
            $utilizationRate = $vehicle->trips_count > 0
                ? round(($vehicle->completed_trips / $vehicle->trips_count) * 100, 2)
                : 0;

            return [
                'vehicle' => $vehicle,
                'total_trips' => $vehicle->trips_count,
                'completed' => $vehicle->completed_trips,
                'utilization_rate' => $utilizationRate,
            ];
        });

        return view('dispatch.reports.vehicle-utilization', compact('vehicleMetrics'));
    }

    public function clientActivity()
    {
        $clients = Client::withCount([
            'deliveryRequests',
            'deliveryRequests as completed_requests' => function ($query) {
                $query->where('status', 'completed');
            },
            'deliveryRequests as pending_requests' => function ($query) {
                $query->where('status', 'pending');
            }
        ])
            ->with(['deliveryRequests' => function ($query) {
                $query->latest()->limit(5);
            }])
            ->orderByDesc('delivery_requests_count')
            ->get();

        return view('dispatch.reports.client-activity', compact('clients'));
    }

    public function onTimeDelivery()
    {
        $trips = Trip::where('status', 'completed')
            ->whereNotNull('scheduled_time')
            ->whereNotNull('actual_end_time')
            ->with(['deliveryRequest.client', 'driver'])
            ->get();

        $onTimeTrips = $trips->filter(function ($trip) {
            return $trip->actual_end_time <= $trip->scheduled_time->addHours(2); // 2 hour grace period
        });

        $stats = [
            'total_completed' => $trips->count(),
            'on_time' => $onTimeTrips->count(),
            'delayed' => $trips->count() - $onTimeTrips->count(),
            'on_time_percentage' => $trips->count() > 0
                ? round(($onTimeTrips->count() / $trips->count()) * 100, 2)
                : 0,
        ];

        return view('dispatch.reports.on-time-delivery', compact('trips', 'onTimeTrips', 'stats'));
    }

    public function tripSummary()
    {
        $summary = [
            'total_trips' => Trip::count(),
            'completed' => Trip::where('status', 'completed')->count(),
            'in_transit' => Trip::where('status', 'in-transit')->count(),
            'scheduled' => Trip::where('status', 'scheduled')->count(),
            'cancelled' => Trip::where('status', 'cancelled')->count(),
        ];

        $recentTrips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->orderBy('scheduled_time', 'desc')
            ->limit(50)
            ->get();

        return view('dispatch.reports.trip-summary', compact('summary', 'recentTrips'));
    }

    public function dispatchSheet(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        return view('dispatch.reports.dispatch-sheet-form', compact('date'));
    }

    public function generateDispatchSheet(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle', 'updates'])
            ->whereDate('scheduled_time', $date)
            ->orderBy('scheduled_time')
            ->get();

        return view('dispatch.reports.dispatch-sheet', compact('trips', 'date'));
    }

    // Export Functions - PDF
    public function exportDaily(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereDate('scheduled_time', $date)
            ->orderBy('scheduled_time')
            ->get();

        $stats = [
            'total_trips' => $trips->count(),
            'completed' => $trips->where('status', 'completed')->count(),
            'in_transit' => $trips->where('status', 'in-transit')->count(),
            'scheduled' => $trips->where('status', 'scheduled')->count(),
            'cancelled' => $trips->where('status', 'cancelled')->count(),
        ];

        return $this->exportTripsToPDF($trips, 'daily_report_' . $date->format('Y-m-d'), $date->format('F d, Y'), $stats);
    }

    public function exportWeekly(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();

        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereBetween('scheduled_time', [$startDate, $endDate])
            ->orderBy('scheduled_time')
            ->get();

        $stats = [
            'total_trips' => $trips->count(),
            'completed' => $trips->where('status', 'completed')->count(),
            'in_transit' => $trips->where('status', 'in-transit')->count(),
            'scheduled' => $trips->where('status', 'scheduled')->count(),
            'cancelled' => $trips->where('status', 'cancelled')->count(),
        ];

        $period = $startDate->format('M d') . ' - ' . $endDate->format('M d, Y');
        return $this->exportTripsToPDF($trips, 'weekly_report_' . $startDate->format('Y-m-d'), $period, $stats);
    }

    public function exportMonthly(Request $request)
    {
        $month = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();
        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereMonth('scheduled_time', $month->month)
            ->whereYear('scheduled_time', $month->year)
            ->orderBy('scheduled_time')
            ->get();

        $stats = [
            'total_trips' => $trips->count(),
            'completed' => $trips->where('status', 'completed')->count(),
            'in_transit' => $trips->where('status', 'in-transit')->count(),
            'scheduled' => $trips->where('status', 'scheduled')->count(),
            'cancelled' => $trips->where('status', 'cancelled')->count(),
        ];

        return $this->exportTripsToPDF($trips, 'monthly_report_' . $month->format('Y-m'), $month->format('F Y'), $stats);
    }

    public function exportCustom(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $trips = Trip::with(['deliveryRequest.client', 'driver', 'vehicle'])
            ->whereBetween('scheduled_time', [$startDate, $endDate])
            ->orderBy('scheduled_time')
            ->get();

        $stats = [
            'total_trips' => $trips->count(),
            'completed' => $trips->where('status', 'completed')->count(),
            'in_transit' => $trips->where('status', 'in-transit')->count(),
            'scheduled' => $trips->where('status', 'scheduled')->count(),
            'cancelled' => $trips->where('status', 'cancelled')->count(),
        ];

        $period = $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y');
        return $this->exportTripsToPDF($trips, 'custom_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d'), $period, $stats);
    }

    private function exportTripsToPDF($trips, $filename, $period, $stats)
    {
        // Return HTML view optimized for browser print-to-PDF
        // This works natively in all modern browsers without external dependencies
        return response()->view('dispatch.reports.pdf.trips-report', [
            'trips' => $trips,
            'period' => $period,
            'stats' => $stats,
            'generated_at' => Carbon::now()->format('F d, Y h:i A'),
            'print_pdf' => true,
        ])->header('Content-Type', 'text/html');
    }

    public function syncToGoogleSheets()
    {
        // Implement Google Sheets API integration
        // This requires google/apiclient package
        return redirect()->back()->with('info', 'Google Sheets sync feature coming soon.');
    }

    public function exportToGoogleSheets(Request $request)
    {
        // Implement Google Sheets export
        return redirect()->back()->with('info', 'Google Sheets export feature coming soon.');
    }
}
