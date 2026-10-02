<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bookings Report</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1c1917;
            background: #ffffff;
            font-size: 11px;
            line-height: 1.4;
            padding: 20px;
        }

        .header {
            border-bottom: 2px solid #9f1239;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }

        .header-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            max-height: 50px;
            max-width: 100px;
        }

        .brand {
            flex: 1;
        }

        .brand h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #9f1239;
        }

        .brand p {
            margin: 2px 0;
            font-size: 10px;
            color: #6f675e;
        }

        .meta-info {
            text-align: right;
            font-size: 9px;
            color: #6f675e;
        }

        .filters {
            display: flex;
            gap: 30px;
            margin-top: 10px;
            font-size: 10px;
        }

        .title {
            font-size: 14px;
            font-weight: 700;
            margin: 0;
            color: #1c1917;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            padding: 8px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #f5f1eb;
            border-bottom: 1px solid #e0d5c8;
            color: #6f675e;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #f0e7dc;
            font-size: 10px;
        }

        tr:hover {
            background: #faf7f2;
        }

        .num {
            text-align: right;
            font-variant-numeric: tabular-nums;
            font-weight: 600;
        }

        .summary {
            margin-top: 30px;
            padding: 15px;
            background: #fff9f3;
            border: 1px solid #e7ddd3;
            border-radius: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 11px;
        }

        .summary-row strong {
            font-weight: 700;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e0d5c8;
            font-size: 9px;
            color: #6f675e;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-top">
            <div class="logo-section">
                @if($tenant->logo)
                    <img src="{{ public_path(str_replace('storage/', '', $tenant->logo)) }}" alt="Logo" class="logo">
                @endif
                <div class="brand">
                    <h1>{{ $tenant->name }}</h1>
                    @if($tenant->city)
                        <p>{{ $tenant->city }}</p>
                    @endif
                    @if($tenant->phone)
                        <p>{{ $tenant->phone }}</p>
                    @endif
                    @if($tenant->email)
                        <p>{{ $tenant->email }}</p>
                    @endif
                </div>
            </div>
            <div class="meta-info">
                <div>{{ $generatedAt->format('d M Y, H:i A') }}</div>
                <div style="margin-top: 5px;">Bookings Report</div>
            </div>
        </div>
        <div class="filters">
            <div><strong>From:</strong> {{ $from->format('d M Y') }}</div>
            <div><strong>To:</strong> {{ $to->format('d M Y') }}</div>
            <div><strong>Total Bookings:</strong> {{ $bookings->count() }}</div>
        </div>
    </div>

    <h2 class="title">Bookings Summary</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Guest Name</th>
                <th>Room</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Check-In Time</th>
                <th>Check-Out Time</th>
                <th>Nights</th>
                <th>Room Rent</th>
                <th>Extras</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $booking->customer->name }}</td>
                    <td>{{ $booking->room->number }}</td>
                    <td>{{ $booking->check_in->format('d M Y') }}</td>
                    <td>{{ $booking->check_out->format('d M Y') }}</td>
                    <td>{{ $booking->checked_in_at?->format('d M Y, h:i A') ?? 'Pending' }}</td>
                    <td>{{ $booking->checked_out_at?->format('d M Y, h:i A') ?? 'Pending' }}</td>
                    <td class="num">{{ $booking->nights }}</td>
                    <td class="num">Rs {{ number_format((float) $booking->room_amount, 0) }}</td>
                    <td class="num">Rs {{ number_format((float) $booking->extras_amount, 0) }}</td>
                    <td class="num"><strong>Rs {{ number_format((float) $booking->grand_total, 0) }}</strong></td>
                    <td class="num">Rs {{ number_format($booking->amountPaid(), 0) }}</td>
                    <td class="num">Rs {{ number_format((float) $booking->balance_due, 0) }}</td>
                    <td>{{ $booking->paymentStatusLabel() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($bookings->isNotEmpty())
        <div class="summary">
            <div class="summary-row">
                <span>Total Bookings:</span>
                <strong>{{ $bookings->count() }}</strong>
            </div>
            <div class="summary-row">
                <span>Total Room Rent:</span>
                <strong>Rs {{ number_format((float) $bookings->sum('room_amount'), 0) }}</strong>
            </div>
            <div class="summary-row">
                <span>Total Extras:</span>
                <strong>Rs {{ number_format((float) $bookings->sum('extras_amount'), 0) }}</strong>
            </div>
            <div class="summary-row" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #ddd;">
                <span><strong>Total Revenue:</strong></span>
                <strong style="font-size: 12px;">Rs {{ number_format((float) $bookings->sum('grand_total'), 0) }}</strong>
            </div>
            <div class="summary-row">
                <span>Total Paid:</span>
                <strong>Rs {{ number_format((float) $bookings->sum(fn ($b) => $b->amountPaid()), 0) }}</strong>
            </div>
            <div class="summary-row">
                <span>Outstanding Balance:</span>
                <strong>Rs {{ number_format((float) $bookings->sum('balance_due'), 0) }}</strong>
            </div>
        </div>
    @endif

    <div class="footer">
        <p>This is an automated report generated by {{ config('app.name') }}</p>
        @if($tenant->website)
            <p>{{ $tenant->website }}</p>
        @endif
        @if($tenant->tax_id)
            <p>Tax ID: {{ $tenant->tax_id }}</p>
        @endif
    </div>
</body>
</html>
