<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $definition['title'] }} Report</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1c1917;
            background: #fff;
            font-size: 11px;
            line-height: 1.4;
            padding: 18px;
        }

        .header {
            border-bottom: 2px solid #9f1239;
            margin-bottom: 16px;
            padding-bottom: 12px;
        }

        .title {
            margin: 0;
            color: #9f1239;
            font-size: 18px;
            font-weight: 700;
        }

        .meta {
            margin-top: 4px;
            color: #6f675e;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        th {
            text-align: left;
            padding: 7px;
            background: #f5f1eb;
            border-bottom: 1px solid #e0d5c8;
            color: #6f675e;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 6px 7px;
            border-bottom: 1px solid #f0e7dc;
            font-size: 10px;
        }

        .num {
            text-align: right;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .summary {
            margin-top: 18px;
            border: 1px solid #e7ddd3;
            background: #fff9f3;
            padding: 12px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $definition['title'] }} Report</h1>
        <div class="meta">{{ $tenant?->name }} · Generated {{ $generatedAt->format('d M Y, h:i A') }}</div>
        <div class="meta">{{ $definition['subtitle'] }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Booking</th>
                <th>Guest</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Nights</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->customer->name }}</td>
                    <td>{{ $booking->room->number }}</td>
                    <td>{{ $booking->check_in->format('d M Y') }}</td>
                    <td>{{ $booking->check_out->format('d M Y') }}</td>
                    <td class="num">{{ $booking->nights }}</td>
                    <td>{{ $booking->statusLabel() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No entries found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span>Total entries</span>
            <strong>{{ $totals['count'] }}</strong>
        </div>
    </div>
</body>
</html>
