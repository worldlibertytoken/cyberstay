<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bill #{{ $booking->id }}</title>
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

        .bill-info {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 11px;
        }

        .guest-info {
            background: #f5f1eb;
            padding: 12px;
            margin: 15px 0;
            border-radius: 8px;
        }

        .guest-info strong {
            display: block;
            margin-bottom: 4px;
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
        }

        .total-row {
            font-size: 12px;
            font-weight: 700;
            background: #fff9f3;
        }

        .total-row td {
            border-bottom: 2px solid #9f1239;
            padding: 10px 8px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e0d5c8;
            font-size: 9px;
            color: #6f675e;
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">Print Bill</button>

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
        <div class="bill-info">
            <div>
                <strong>Bill #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</strong>
                <p>{{ now()->format('d M Y H:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="guest-info">
        <strong>Guest Information</strong>
        <strong>{{ $booking->customer->name }}</strong>
        <p>S/O {{ $booking->customer->father_name ?: '—' }}<br>
            Phone: {{ $booking->customer->phone }}<br>
            CNIC: {{ $booking->customer->cnic ?: '—' }}<br>
            {{ $booking->customer->address }}
        </p>
    </div>

    <p style="margin-top: 15px; font-size: 11px;">
        <strong>Stay Details:</strong> Room {{ $booking->room->number }} from {{ $booking->check_in->format('d M Y') }} to {{ $booking->check_out->format('d M Y') }} ({{ $booking->nights }} nights)<br>
        <strong>Check-in Date & Time:</strong> {{ $booking->checked_in_at?->format('d M Y, h:i A') ?? 'Pending' }}<br>
        <strong>Check-out Date & Time:</strong> {{ $booking->checked_out_at?->format('d M Y, h:i A') ?? 'Pending' }}<br>
        <strong>Guests:</strong> {{ $booking->males }} male, {{ $booking->females }} female, {{ $booking->children }} children
        @if($booking->vehicle_number)
            · Vehicle: {{ $booking->vehicle_number }}
        @endif
    </p>

    @if($booking->companions->isNotEmpty())
        <p style="font-size: 11px; margin-top: 8px;">
            <strong>Other Guests:</strong> {{ $booking->companions->pluck('name')->join(', ') }}
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Room {{ $booking->room->number }} @ Rs {{ number_format((float) $booking->price_per_night, 0) }}/night</td>
                <td class="num">{{ $booking->nights }}</td>
                <td class="num">Rs {{ number_format((float) $booking->room_amount, 0) }}</td>
            </tr>
            @foreach($booking->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="num">{{ $item->qty }}</td>
                    <td class="num">Rs {{ number_format((float) $item->total, 0) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2">Total Amount Due</td>
                <td class="num">Rs {{ number_format((float) $booking->grand_total, 0) }}</td>
            </tr>
            @if($booking->amountPaid() > 0)
                <tr>
                    <td colspan="2">Amount Paid</td>
                    <td class="num">Rs {{ number_format($booking->amountPaid(), 0) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="2">Balance Due</td>
                    <td class="num">Rs {{ number_format((float) $booking->balance_due, 0) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Thank you for your stay at {{ $booking->tenant->name ?? 'Hotel' }}</p>
        @if($booking->tenant->website)
            <p>{{ $booking->tenant->website }}</p>
        @endif
        @if($booking->tenant->tax_id)
            <p>Tax ID: {{ $booking->tenant->tax_id }}</p>
        @endif
    </div>
</body>
</html>
