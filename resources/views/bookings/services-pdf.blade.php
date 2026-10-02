<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking #{{ $booking->id }} Services</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1c1917;
            background: #ffffff;
            font-size: 12px;
            line-height: 1.5;
            padding: 20px;
        }

        .header {
            border-bottom: 2px solid #9f1239;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo {
            max-height: 50px;
            max-width: 100px;
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

        .section {
            margin-top: 15px;
            padding: 12px;
            background: #f5f1eb;
            border-radius: 8px;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6f675e;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            padding: 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 1px solid #9f1239;
            background: #fff9f3;
            color: #6f675e;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e0d5c8;
            font-size: 11px;
        }

        .num {
            text-align: right;
            font-variant-numeric: tabular-nums;
            font-weight: 600;
        }

        .summary {
            margin-top: 15px;
            padding: 10px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 11px;
            border-bottom: 1px solid #e0d5c8;
        }

        .total-row {
            font-weight: 700;
            font-size: 12px;
            border-bottom: 2px solid #9f1239;
            padding: 6px 0;
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
        <div class="logo-section">
            @if($booking->tenant->logo)
                <img src="{{ public_path(str_replace('storage/', '', $booking->tenant->logo)) }}" alt="Logo" class="logo">
            @endif
            <div class="brand">
                <h1>{{ $booking->tenant->name ?? 'Hotel' }}</h1>
                @if($booking->tenant->city)
                    <p>{{ $booking->tenant->city }}</p>
                @endif
                @if($booking->tenant->phone)
                    <p>{{ $booking->tenant->phone }}</p>
                @endif
                @if($booking->tenant->email)
                    <p>{{ $booking->tenant->email }}</p>
                @endif
            </div>
        </div>
    </div>

    <p style="font-size: 11px; margin: 0;">Booking #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }} · Services Statement (Room Rent Excluded)</p>

    <div class="section">
        <div class="section-title">Guest Information</div>
        <p style="margin: 0;"><strong>{{ $booking->customer->name }}</strong><br>
            S/O {{ $booking->customer->father_name ?: '—' }}<br>
            Phone: {{ $booking->customer->phone }}<br>
            CNIC: {{ $booking->customer->cnic ?: '—' }}<br>
            {{ $booking->customer->address }}
        </p>
    </div>

    <div class="section">
        <div class="section-title">Stay Details</div>
        <p style="margin: 0;">
            Room {{ $booking->room->number }} · {{ $booking->check_in->format('d M Y') }} to {{ $booking->check_out->format('d M Y') }}<br>
            Check-in: {{ $booking->checked_in_at?->format('d M Y, h:i A') ?? 'Pending' }} · Check-out: {{ $booking->checked_out_at?->format('d M Y, h:i A') ?? 'Pending' }}<br>
            {{ $booking->nights }} night(s) · {{ $booking->males }} male, {{ $booking->females }} female, {{ $booking->children }} children
            @if($booking->companions->isNotEmpty())
                <br>Other Guests: {{ $booking->companions->pluck('name')->join(', ') }}
            @endif
        </p>
    </div>

    <h2 style="font-size: 13px; margin: 15px 0 10px; font-weight: 700;">Ordered Services</h2>

    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Qty</th>
                <th class="num">Rate</th>
                <th class="num">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($booking->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="num">{{ $item->qty }}</td>
                    <td class="num">Rs {{ number_format((float) $item->unit_price, 0) }}</td>
                    <td class="num">Rs {{ number_format((float) $item->total, 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #6f675e;">No ordered services for this booking.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span>Services Billed:</span>
            <span class="num">Rs {{ number_format((float) $booking->extras_amount, 0) }}</span>
        </div>
        <div class="summary-row">
            <span>Services Paid:</span>
            <span class="num">Rs {{ number_format((float) $booking->extras_paid_amount, 0) }}</span>
        </div>
        <div class="summary-row total-row">
            <span>Services Due:</span>
            <span class="num">Rs {{ number_format((float) $booking->extrasBalance(), 0) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Services Statement - Room Rent Not Included<br>
            {{ now()->format('d M Y, H:i A') }}
        </p>
        @if($booking->tenant->website)
            <p>{{ $booking->tenant->website }}</p>
        @endif
        @if($booking->tenant->tax_id)
            <p>Tax ID: {{ $booking->tenant->tax_id }}</p>
        @endif
    </div>
</body>
</html>
