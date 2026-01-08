<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accident Report #{{ $accident->id }} - NVG Prime Movers</title>
    @if(isset($print_pdf) && $print_pdf)
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            padding: 30px 40px;
            position: relative;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .header .subtitle {
            font-size: 16px;
            opacity: 0.9;
        }

        .header .report-id {
            position: absolute;
            top: 30px;
            right: 40px;
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
        }

        .content {
            padding: 40px;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #dc2626;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #fee2e2;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
        }

        .status-severity {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
        }

        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-gray { background: #f3f4f6; color: #374151; }
        .badge-orange { background: #fed7aa; color: #9a3412; }
        .badge-red { background: #fee2e2; color: #991b1b; }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #dc2626;
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
        }

        .description-box {
            background: #fef2f2;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #dc2626;
            margin-bottom: 20px;
        }

        .description-box p {
            line-height: 1.6;
            color: #374151;
            white-space: pre-wrap;
        }

        .detail-box {
            background: #fef2f2;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #dc2626;
            margin-bottom: 15px;
        }

        .detail-title {
            font-size: 14px;
            font-weight: 600;
            color: #991b1b;
            margin-bottom: 8px;
        }

        .footer {
            background: #f9fafb;
            padding: 20px 40px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }

        .footer .timestamp {
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
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
            .no-print {
                display: none !important;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="report-id">Report #{{ $accident->id }}</div>
            <h1>?? Accident Report</h1>
            <div class="subtitle">NVG Prime Movers Dispatch System</div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Status & Severity -->
            <div class="status-severity">
                <div>
                    <div class="info-label">Status</div>
                    <span class="badge 
                        {{ $accident->status === 'pending' ? 'badge-yellow' : '' }}
                        {{ $accident->status === 'under_investigation' ? 'badge-blue' : '' }}
                        {{ $accident->status === 'resolved' ? 'badge-green' : '' }}
                        {{ $accident->status === 'closed' ? 'badge-gray' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $accident->status)) }}
                    </span>
                </div>
                <div>
                    <div class="info-label">Severity</div>
                    <span class="badge 
                        {{ $accident->severity === 'minor' ? 'badge-green' : '' }}
                        {{ $accident->severity === 'moderate' ? 'badge-yellow' : '' }}
                        {{ $accident->severity === 'severe' ? 'badge-orange' : '' }}
                        {{ $accident->severity === 'fatal' ? 'badge-red' : '' }}">
                        {{ ucfirst($accident->severity) }}
                    </span>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="section">
                <div class="section-title">?? Basic Information</div>
                <div class="info-grid">
                    <div class="info-box">
                        <div class="info-label">Accident Date & Time</div>
                        <div class="info-value">{{ $accident->accident_date->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="info-box">
                        <div class="info-label">Location</div>
                        <div class="info-value">{{ $accident->location }}</div>
                    </div>
                    <div class="info-box">
                        <div class="info-label">Reported By</div>
                        <div class="info-value">{{ $accident->reportedBy->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-box">
                        <div class="info-label">Report Date</div>
                        <div class="info-value">{{ $accident->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>

            <!-- Trip Information -->
            <div class="section">
                <div class="section-title">?? Trip Information</div>
                <table>
                    <tr>
                        <th>Trip ID</th>
                        <th>Waybill Number</th>
                        <th>Driver</th>
                        <th>Vehicle</th>
                    </tr>
                    <tr>
                        <td>#{{ $accident->trip_id }}</td>
                        <td>{{ $accident->trip->waybill_number ?? 'N/A' }}</td>
                        <td>{{ $accident->driver->name ?? 'N/A' }}<br><small>{{ $accident->driver->license_number ?? '' }}</small></td>
                        <td>{{ $accident->vehicle->plate_number ?? 'N/A' }}<br><small>{{ $accident->vehicle->model ?? '' }}</small></td>
                    </tr>
                </table>
            </div>

            <!-- Description -->
            <div class="section">
                <div class="section-title">?? Accident Description</div>
                <div class="description-box">
                    <p>{{ $accident->description }}</p>
                </div>
            </div>

            <!-- Additional Details -->
            @if($accident->injuries || $accident->vehicle_damage || $accident->other_party_info || $accident->witness_info)
            <div class="section">
                <div class="section-title">?? Additional Details</div>
                
                @if($accident->injuries)
                <div class="detail-box">
                    <div class="detail-title">?? Injuries</div>
                    <p>{{ $accident->injuries }}</p>
                </div>
                @endif

                @if($accident->vehicle_damage)
                <div class="detail-box">
                    <div class="detail-title">?? Vehicle Damage</div>
                    <p>{{ $accident->vehicle_damage }}</p>
                </div>
                @endif

                @if($accident->other_party_info)
                <div class="detail-box">
                    <div class="detail-title">?? Other Party Information</div>
                    <p>{{ $accident->other_party_info }}</p>
                </div>
                @endif

                @if($accident->witness_info)
                <div class="detail-box">
                    <div class="detail-title">??? Witness Information</div>
                    <p>{{ $accident->witness_info }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Police Report -->
            @if($accident->police_report_filed || $accident->police_report_number)
            <div class="section">
                <div class="section-title">?? Police Report</div>
                <div class="info-grid">
                    <div class="info-box">
                        <div class="info-label">Report Filed</div>
                        <div class="info-value" style="color: {{ $accident->police_report_filed ? '#059669' : '#dc2626' }}">
                            {{ $accident->police_report_filed ? 'Yes' : 'No' }}
                        </div>
                    </div>
                    @if($accident->police_report_number)
                    <div class="info-box">
                        <div class="info-label">Police Report Number</div>
                        <div class="info-value">{{ $accident->police_report_number }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action & Cost -->
            <div class="section">
                <div class="section-title">?? Action & Costs</div>
                <div class="info-grid">
                    @if($accident->action_taken)
                    <div class="info-box" style="grid-column: span 1;">
                        <div class="info-label">Action Taken</div>
                        <p style="margin-top: 8px; line-height: 1.5;">{{ $accident->action_taken }}</p>
                    </div>
                    @endif

                    @if($accident->estimated_damage_cost)
                    <div class="info-box">
                        <div class="info-label">Estimated Damage Cost</div>
                        <div class="info-value" style="font-size: 24px; color: #dc2626;">
                            ?{{ number_format($accident->estimated_damage_cost, 2) }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Resolution -->
            @if($accident->status === 'resolved' || $accident->status === 'closed')
            <div class="section">
                <div class="section-title">? Resolution</div>
                <div class="info-box" style="border-left-color: #059669; background: #d1fae5;">
                    @if($accident->resolved_at)
                    <div class="info-label">Resolved At</div>
                    <div class="info-value">{{ $accident->resolved_at->format('M d, Y h:i A') }}</div>
                    <hr style="margin: 10px 0; border: none; border-top: 1px solid #a7f3d0;">
                    @endif
                    @if($accident->resolution_notes)
                    <div class="info-label">Resolution Notes</div>
                    <p style="margin-top: 8px; line-height: 1.5; color: #065f46;">{{ $accident->resolution_notes }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="timestamp">Generated: {{ now()->format('F d, Y h:i A') }}</div>
            <div>NVG Prime Movers Dispatch System - Accident Report</div>
            <div style="margin-top: 5px;">This is an official accident report document</div>
        </div>
    </div>
</body>
</html>
