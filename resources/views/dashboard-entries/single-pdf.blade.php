<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $definition['title'] }} Entry #{{ $booking->id }}</title>
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
            line-height: 1.45;
            padding: 20px;
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

        .grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .grid td {
            border: 1px solid #e7ddd3;
            padding: 8px;
            vertical-align: top;
        }

        .label {
            font-size: 9px;
            text-transform: uppercase;
            color: #6f675e;
            letter-spacing: 0.04em;
            margin-bottom: 3px;
        }

        .value {
            font-size: 11px;
            color: #1c1917;
            font-weight: 600;
        }

        .section {
            margin-top: 16px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        .note {
            border: 1px solid #e7ddd3;
            background: #faf7f2;
            padding: 10px;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .items th,
        .items td {
            border: 1px solid #e7ddd3;
            padding: 7px;
        }

        .items th {
            background: #f5f1eb;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            color: #6f675e;
        }

        .num {
            text-align: right;
            font-variant-numeric: tabular-nums;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $definition['title'] }} Entry #{{ $booking->id }}</h1>
        <div class="meta">{{ $tenant?->name }} · Generated {{ $generatedAt->format('d M Y, h:i A') }}</div>
    </div>

    <table class="grid">
        <tr>
            <td>
                <div class="label">Guest name</div>
                <div class="value">{{ $booking->customer->name }}</div>
            </td>
            <td>
                <div class="label">Phone</div>
                <div class="value">{{ $booking->customer->phone }}</div>
            </td>
            <td>
                <div class="label">Room</div>
                <div class="value">{{ $booking->room->number }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="label">Check-in</div>
                <div class="value">{{ $booking->check_in->format('d M Y') }}</div>
            </td>
            <td>
                <div class="label">Check-out</div>
                <div class="value">{{ $booking->check_out->format('d M Y') }}</div>
            </td>
            <td>
                <div class="label">Nights</div>
                <div class="value">{{ $booking->nights }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="label">Status</div>
                <div class="value">{{ $booking->statusLabel() }}</div>
            </td>
            <td>
                <div class="label">Payment</div>
                <div class="value">{{ $booking->paymentStatusLabel() }}</div>
            </td>
            <td>
                <div class="label">Guest count</div>
                <div class="value">{{ $booking->guestCount() }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="label">Vehicle</div>
                <div class="value">{{ $booking->vehicle_number ?: '—' }}</div>
            </td>
        </tr>
    </table>

    @if($booking->notes)
        <div class="section">
            <h2 class="section-title">Booking note</h2>
            <div class="note">{{ $booking->notes }}</div>
        </div>
    @endif

    @if($booking->checkout_note)
        <div class="section">
            <h2 class="section-title">Checkout note</h2>
            <div class="note">{{ $booking->checkout_note }}</div>
        </div>
    @endif

    <div class="section">
        <h2 class="section-title">Extra charges</h2>
        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @forelse($booking->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="num">{{ $item->qty }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No extra charges.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
