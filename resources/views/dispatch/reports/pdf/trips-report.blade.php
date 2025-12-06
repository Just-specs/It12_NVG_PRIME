<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Trip Report - {{ $period }}</title>
    @if(isset($print_pdf) && $print_pdf)
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
    @endif
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #1E40AF;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item .label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .stat-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #1E40AF;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-completed {
            background-color: #10b981;
            color: white;
        }

        .status-in-transit {
            background-color: #3b82f6;
            color: white;
        }

        .status-scheduled {
            background-color: #6b7280;
            color: white;
        }

        .status-cancelled {
            background-color: #ef4444;
            color: white;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Trip Report</h1>
        <p><strong>Period:</strong> {{ $period }}</p>
        <p><strong>Generated:</strong> {{ $generated_at }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="label">Total Trips</div>
            <div class="value">{{ $stats['total_trips'] }}</div>
        </div>
        <div class="stat-item">
            <div class="label">Completed</div>
            <div class="value" style="color: #10b981;">{{ $stats['completed'] }}</div>
        </div>
        <div class="stat-item">
            <div class="label">In Transit</div>
            <div class="value" style="color: #3b82f6;">{{ $stats['in_transit'] }}</div>
        </div>
        <div class="stat-item">
            <div class="label">Scheduled</div>
            <div class="value" style="color: #6b7280;">{{ $stats['scheduled'] }}</div>
        </div>
        <div class="stat-item">
            <div class="label">Cancelled</div>
            <div class="value" style="color: #ef4444;">{{ $stats['cancelled'] }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date & Time</th>
                <th>Client</th>
                <th>ATW Ref</th>
                <th>Driver</th>
                <th>Vehicle</th>
                <th>Pickup</th>
                <th>Delivery</th>
                <th>Status</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trips as $trip)
            <tr>
                <td>{{ $trip->id }}</td>
                <td>
                    {{ $trip->scheduled_time->format('M d, Y') }}<br>
                    {{ $trip->scheduled_time->format('h:i A') }}
                </td>
                <td>{{ $trip->deliveryRequest->client->name }}</td>
                <td>{{ $trip->deliveryRequest->atw_reference }}</td>
                <td>{{ $trip->driver->name }}</td>
                <td>{{ $trip->vehicle->plate_number }}</td>
                <td>{{ Str::limit($trip->deliveryRequest->pickup_location, 30) }}</td>
                <td>{{ Str::limit($trip->deliveryRequest->delivery_location, 30) }}</td>
                <td>
                    <span class="status-badge status-{{ $trip->status }}">
                        {{ ucfirst($trip->status) }}
                    </span>
                </td>
                <td>
                    @if($trip->actual_start_time && $trip->actual_end_time)
                    {{ $trip->actual_start_time->diffInMinutes($trip->actual_end_time) }} min
                    @else
                    -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px; color: #666;">
                    No trips found for this period
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the Dispatch Management System</p>
    </div>
</body>

</html>