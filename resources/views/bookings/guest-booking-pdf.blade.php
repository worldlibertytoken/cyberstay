<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmation #{{ $booking->id }}</title>
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

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-block p {
            margin: 2px 0;
            font-size: 11px;
            line-height: 1.4;
        }

        .info-block strong {
            display: block;
            color: #1c1917;
            margin-bottom: 4px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e0d5c8;
            font-size: 9px;
            color: #6f675e;
            text-align: center;
        }

        .footer-info {
            margin-top: 8px;
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

    <h2 style="font-size: 14px; margin: 0 0 10px; font-weight: 700; color: #9f1239;">Booking Confirmation #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</h2>

    <div class="section">
        <div class="section-title">Guest Information</div>
        <div class="info-grid">
            <div class="info-block">
                <strong>Name</strong>
                <p>{{ $booking->customer->name }}</p>
                <p style="color: #6f675e;">S/O {{ $booking->customer->father_name ?: '—' }}</p>
            </div>
            <div class="info-block">
                <strong>Contact</strong>
                <p>{{ $booking->customer->phone }}</p>
                <p style="color: #6f675e;">CNIC: {{ $booking->customer->cnic ?: '—' }}</p>
            </div>
        </div>
        @if($booking->customer->address)
            <p style="margin-top: 8px; font-size: 11px;">{{ $booking->customer->address }}</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Stay Details</div>
        <div class="info-grid">
            <div class="info-block">
                <strong>Room</strong>
                <p>{{ $booking->room->number }}</p>
            </div>
            <div class="info-block">
                <strong>Check-in</strong>
                <p>{{ $booking->check_in->format('d M Y') }}</p>
                <p style="color: #6f675e;">{{ $booking->checked_in_at?->format('d M Y, h:i A') ?? 'Pending' }}</p>
            </div>
            <div class="info-block">
                <strong>Check-out</strong>
                <p>{{ $booking->check_out->format('d M Y') }}</p>
                <p style="color: #6f675e;">{{ $booking->checked_out_at?->format('d M Y, h:i A') ?? 'Pending' }}</p>
            </div>
            <div class="info-block">
                <strong>Duration</strong>
                <p>{{ $booking->nights }} night(s)</p>
            </div>
            <div class="info-block">
                <strong>Guests</strong>
                <p>{{ $booking->males }} adult(s), {{ $booking->females }} adult(s), {{ $booking->children }} child(ren)</p>
            </div>
            @if($booking->vehicle_number)
                <div class="info-block">
                    <strong>Vehicle</strong>
                    <p>{{ $booking->vehicle_number }}</p>
                </div>
            @endif
        </div>

        @if($booking->companions->isNotEmpty())
            <p style="margin-top: 10px; font-size: 11px;"><strong>Other Guests:</strong> {{ $booking->companions->pluck('name')->join(', ') }}</p>
        @endif
    </div>

    @if($booking->items->isNotEmpty())
        <h3 style="font-size: 12px; margin: 15px 0 10px; font-weight: 700;">Ordered Services</h3>
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Quantity</th>
                    <th>Dates/Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->description ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>This is your booking confirmation document.</p>
        @if($booking->tenant->website)
            <p style="margin-top: 4px;">{{ $booking->tenant->website }}</p>
        @endif
        <div class="footer-info">
            Generated {{ $generatedAt->format('d M Y, H:i A') }}
        </div>
    </div>
</body>
</html>
