<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Report - Trip #{{ $trip->id }}</title>
    <style>
        body {
            color: #111827;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 24px;
        }

        .receipt {
            border: 1px solid #d1d5db;
            margin: 0 auto;
            max-width: 820px;
            padding: 28px;
        }

        .header {
            border-bottom: 2px solid #111827;
            display: flex;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
        }

        h1 {
            font-size: 24px;
            margin: 0 0 6px;
        }

        h2 {
            font-size: 16px;
            margin: 24px 0 10px;
        }

        .muted {
            color: #6b7280;
            font-size: 13px;
        }

        .grid {
            display: grid;
            gap: 12px;
            grid-template-columns: 1fr 1fr;
        }

        .field {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .label {
            color: #6b7280;
            display: block;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .value {
            font-size: 14px;
            font-weight: 700;
        }

        .totals {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            margin-top: 18px;
            padding: 16px;
        }

        .actions {
            margin: 20px auto;
            max-width: 820px;
            text-align: right;
        }

        button {
            background: #2563eb;
            border: 0;
            border-radius: 6px;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
            padding: 10px 16px;
        }

        @media print {
            .actions {
                display: none;
            }

            body {
                padding: 0;
            }

            .receipt {
                border: 0;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button onclick="window.print()">Print Receipt</button>
    </div>

    <main class="receipt">
        <section class="header">
            <div>
                <h1>NVG Prime Receipt Report</h1>
                <div class="muted">Generated from Reports</div>
                <div class="muted">Trip #{{ $trip->id }}</div>
            </div>
            <div>
                <span class="label">Official Receipt Number</span>
                <span class="value">{{ $trip->official_receipt_number ?: 'Not set' }}</span>
            </div>
        </section>

        <h2>Client And Delivery</h2>
        <section class="grid">
            <div class="field">
                <span class="label">Client</span>
                <span class="value">{{ $trip->deliveryRequest?->client?->name ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="label">ATW Reference</span>
                <span class="value">{{ $trip->deliveryRequest?->atw_reference ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="label">Pickup</span>
                <span class="value">{{ $trip->deliveryRequest?->pickup_location ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="label">Delivery</span>
                <span class="value">{{ $trip->deliveryRequest?->delivery_location ?? 'N/A' }}</span>
            </div>
        </section>

        <h2>Assigned Resources</h2>
        <section class="grid">
            <div class="field">
                <span class="label">Driver</span>
                <span class="value">{{ $trip->driver?->name ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="label">Vehicle</span>
                <span class="value">{{ $trip->vehicle?->plate_number ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="label">Scheduled Time</span>
                <span class="value">{{ $trip->scheduled_time?->format('M d, Y h:i A') ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="label">Status</span>
                <span class="value">{{ ucfirst(str_replace('-', ' ', $trip->status)) }}</span>
            </div>
        </section>

        <h2>Charges</h2>
        <section class="grid">
            <div class="field">
                <span class="label">Trip Rate</span>
                <span class="value">PHP {{ number_format($trip->trip_rate ?? 0, 2) }}</span>
            </div>
            <div class="field">
                <span class="label">Additional 20ft Charge</span>
                <span class="value">PHP {{ number_format($trip->additional_charge_20ft ?? 0, 2) }}</span>
            </div>
            <div class="field">
                <span class="label">Additional 50 Charge</span>
                <span class="value">PHP {{ number_format($trip->additional_charge_50 ?? 0, 2) }}</span>
            </div>
            <div class="field">
                <span class="label">Uploaded Receipt</span>
                <span class="value">
                    @if($trip->receipt_url)
                        <a href="{{ $trip->receipt_url }}">Open cloud file</a>
                    @else
                        None
                    @endif
                </span>
            </div>
        </section>

        <section class="totals">
            <span class="label">Total Revenue</span>
            <span class="value">PHP {{ number_format($trip->total_revenue, 2) }}</span>
        </section>
    </main>
</body>
</html>
