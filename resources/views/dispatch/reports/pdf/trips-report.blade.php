<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Report - {{ $period }}</title>
    @if(isset($print_pdf) && $print_pdf)
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
    @endif
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');
        
        @media print {
            @page {
                size: A4 landscape;
                margin: 1cm;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            .watermark {
                opacity: 0.05 !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1a1a1a;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            position: relative;
        }

        /* Watermark Logo */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            opacity: 0.03;
            z-index: 0;
            pointer-events: none;
        }

        .watermark img {
            width: 600px;
            height: auto;
        }

        /* Main Container */
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #1E40AF 0%, #3b82f6 100%);
            color: white;
            padding: 30px 40px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            flex: 1;
        }

        .header-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
            background: white;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .header-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .company-name {
            font-size: 14px;
            font-weight: 300;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .report-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .report-period {
            font-size: 13px;
            font-weight: 400;
            opacity: 0.95;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .report-period-icon {
            font-size: 16px;
        }

        .header-right {
            text-align: right;
        }

        .report-meta {
            background: rgba(255,255,255,0.15);
            padding: 12px 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .report-meta-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            margin-bottom: 4px;
        }

        .report-meta-value {
            font-size: 12px;
            font-weight: 600;
        }

        /* Stats Container */
        .stats-section {
            padding: 30px 40px;
            background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
            border-bottom: 1px solid #e5e7eb;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--card-color);
        }

        .stat-card.total { --card-color: #3b82f6; }
        .stat-card.completed { --card-color: #10b981; }
        .stat-card.in-transit { --card-color: #f59e0b; }
        .stat-card.scheduled { --card-color: #6b7280; }
        .stat-card.cancelled { --card-color: #ef4444; }

        .stat-icon {
            font-size: 24px;
            margin-bottom: 8px;
            opacity: 0.8;
        }

        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--card-color);
            line-height: 1;
        }

        /* Table Section */
        .table-section {
            padding: 30px 40px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, #3b82f6 0%, #1E40AF 100%);
            border-radius: 2px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        thead {
            background: linear-gradient(135deg, #1E40AF 0%, #3b82f6 100%);
        }

        th {
            color: white;
            padding: 14px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.2s ease;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        td {
            padding: 12px;
            font-size: 10px;
            vertical-align: middle;
        }

        td strong {
            color: #1a1a1a;
            font-weight: 600;
        }

        .trip-id {
            font-weight: 700;
            color: #3b82f6;
            font-size: 11px;
        }

        .trip-date {
            font-weight: 600;
            color: #1a1a1a;
        }

        .trip-time {
            color: #6b7280;
            font-size: 9px;
        }

        .client-name {
            font-weight: 600;
            color: #1a1a1a;
        }

        .vehicle-plate {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            background: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
            color: #1a1a1a;
        }

        .location-text {
            font-size: 9px;
            color: #4b5563;
            line-height: 1.4;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-completed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
        }

        .status-in-transit {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
        }

        .status-scheduled {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);
        }

        .status-cancelled {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

        /* Footer */
        .footer {
            padding: 25px 40px;
            background: linear-gradient(to top, #f8fafc 0%, #ffffff 100%);
            border-top: 2px solid #e5e7eb;
            text-align: center;
        }

        .footer-timestamp {
            font-size: 11px;
            color: #1a1a1a;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .footer-text {
            font-size: 9px;
            color: #6b7280;
            letter-spacing: 0.5px;
        }

        /* No Data */
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .no-data-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .no-data-text {
            font-size: 14px;
            font-weight: 500;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 30px;
            right: 30px;
            background: linear-gradient(135deg, #3b82f6 0%, #1E40AF 100%);
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(59, 130, 246, 0.5);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Watermark Logo -->
    <div class="watermark">
        <img src="{{ asset('img/NVG_LOGO.png') }}" alt="NVG Logo">
    </div>

    <!-- Print Button -->
    <button onclick="window.print()" class="print-button no-print">
        <span>???</span>
        <span>Print / Save as PDF</span>
    </button>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-logo">
                        <img src="{{ asset('img/NVG_LOGO.png') }}" alt="NVG Logo">
                    </div>
                    <div class="company-name">NVG Prime Movers</div>
                    <h1 class="report-title">Trip Report</h1>
                    <div class="report-period">
                        <span class="report-period-icon">??</span>
                        <span>{{ $period }}</span>
                    </div>
                </div>
                <div class="header-right">
                    <div class="report-meta">
                        <div class="report-meta-label">Generated</div>
                        <div class="report-meta-value">{{ $generated_at }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card total">
                    <div class="stat-icon">??</div>
                    <div class="stat-label">Total Trips</div>
                    <div class="stat-value">{{ $stats['total_trips'] }}</div>
                </div>
                <div class="stat-card completed">
                    <div class="stat-icon">?</div>
                    <div class="stat-label">Completed</div>
                    <div class="stat-value">{{ $stats['completed'] }}</div>
                </div>
                <div class="stat-card in-transit">
                    <div class="stat-icon">??</div>
                    <div class="stat-label">In Transit</div>
                    <div class="stat-value">{{ $stats['in_transit'] }}</div>
                </div>
                <div class="stat-card scheduled">
                    <div class="stat-icon">??</div>
                    <div class="stat-label">Scheduled</div>
                    <div class="stat-value">{{ $stats['scheduled'] }}</div>
                </div>
                <div class="stat-card cancelled">
                    <div class="stat-icon">?</div>
                    <div class="stat-label">Cancelled</div>
                    <div class="stat-value">{{ $stats['cancelled'] }}</div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-section">
            <h2 class="section-title">Trip Details</h2>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 10%;">Date & Time</th>
                        <th style="width: 13%;">Client</th>
                        <th style="width: 10%;">ATW Ref</th>
                        <th style="width: 11%;">Driver</th>
                        <th style="width: 9%;">Vehicle</th>
                        <th style="width: 16%;">Pickup</th>
                        <th style="width: 16%;">Delivery</th>
                        <th style="width: 10%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trips as $trip)
                    <tr>
                        <td><span class="trip-id">#{{ $trip->id }}</span></td>
                        <td>
                            <div class="trip-date">{{ $trip->scheduled_time->format('M d, Y') }}</div>
                            <div class="trip-time">{{ $trip->scheduled_time->format('h:i A') }}</div>
                        </td>
                        <td><span class="client-name">{{ $trip->deliveryRequest->client->name }}</span></td>
                        <td>{{ $trip->deliveryRequest->atw_reference }}</td>
                        <td>{{ $trip->driver->name }}</td>
                        <td><span class="vehicle-plate">{{ $trip->vehicle->plate_number }}</span></td>
                        <td><div class="location-text">{{ Str::limit($trip->deliveryRequest->pickup_location, 45) }}</div></td>
                        <td><div class="location-text">{{ Str::limit($trip->deliveryRequest->delivery_location, 45) }}</div></td>
                        <td>
                            <span class="status-badge status-{{ str_replace(' ', '-', $trip->status) }}">
                                {{ ucfirst($trip->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="no-data">
                                <div class="no-data-icon">??</div>
                                <div class="no-data-text">No trips found for this period</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-timestamp">?? Report generated on {{ $generated_at }}</div>
            <div class="footer-text">NVG Prime Movers Dispatch Management System | Confidential Document</div>
        </div>
    </div>
</body>
</html>
