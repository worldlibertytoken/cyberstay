<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Guest Booking #{{ $booking->id }}</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 20px;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1c1917;
            font-size: 12px;
            line-height: 1.5;
        }

        .header {
            border-bottom: 2px solid #9f1239;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo {
            max-height: 52px;
            max-width: 104px;
        }

        h1 {
            margin: 0;
            color: #9f1239;
            font-size: 18px;
        }

        .muted { color: #6f675e; }

        .section {
            margin-top: 14px;
            background: #f5f1eb;
            border-radius: 8px;
            padding: 12px;
        }

        .section-title {
            margin: 0 0 8px;
            font-size: 11px;
            text-transform: uppercase;
            color: #6f675e;
            font-weight: 700;
        }

        .row {
            margin: 3px 0;
        }

        .footer {
            margin-top: 22px;
            border-top: 1px solid #e0d5c8;
            padding-top: 10px;
            font-size: 10px;
            color: #6f675e;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-wrap">
            @if($booking->tenant->logo)
                <img src="{{ public_path(str_replace('storage/', '', $booking->tenant->logo)) }}" alt="Logo" class="logo">
            @endif

            <div>
                <h1>{{ $booking->tenant->name ?? 'Hotel' }}</h1>
                @if($booking->tenant->city)
                    <div class="muted">{{ $booking->tenant->city }}</div>
                @endif
                @if($booking->tenant->phone)
                    <div class="muted">{{ $booking->tenant->phone }}</div>
                @endif
                @if($booking->tenant->email)
                    <div class="muted">{{ $booking->tenant->email }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="row"><strong>Booking #:</strong> {{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
    <div class="row"><strong>Generated:</strong> {{ $generatedAt->format('d M Y, h:i A') }}</div>

    <div class="section">
        <p class="section-title">Guest Details</p>
        <div class="row"><strong>Name:</strong> {{ $booking->customer->name }}</div>
        <div class="row"><strong>Father Name:</strong> {{ $booking->customer->father_name ?: '—' }}</div>
        <div class="row"><strong>Phone:</strong> {{ $booking->customer->phone }}</div>
        <div class="row"><strong>CNIC:</strong> {{ $booking->customer->cnic ?: '—' }}</div>
        <div class="row"><strong>Address:</strong> {{ $booking->customer->address ?: '—' }}</div>
    </div>

    <div class="section">
        <p class="section-title">Stay Details</p>
        <div class="row"><strong>Room:</strong> {{ $booking->room->number }}</div>
        <div class="row"><strong>Check-in:</strong> {{ $booking->check_in->format('d M Y') }}</div>
        <div class="row"><strong>Check-out:</strong> {{ $booking->check_out->format('d M Y') }}</div>
        <div class="row"><strong>Check-in Date & Time:</strong> {{ $booking->checked_in_at?->format('d M Y, h:i A') ?? 'Pending' }}</div>
        <div class="row"><strong>Check-out Date & Time:</strong> {{ $booking->checked_out_at?->format('d M Y, h:i A') ?? 'Pending' }}</div>
        <div class="row"><strong>Nights:</strong> {{ $booking->nights }}</div>
        <div class="row"><strong>Guests:</strong> {{ $booking->males }} male, {{ $booking->females }} female, {{ $booking->children }} children</div>
        <div class="row"><strong>Status:</strong> {{ ucfirst($booking->status) }}</div>
    </div>

    @if($booking->companions->isNotEmpty())
        <div class="section">
            <p class="section-title">Companions</p>
            @foreach($booking->companions as $companion)
                <div class="row">{{ $loop->iteration }}. {{ $companion->name }} @if($companion->cnic) ({{ $companion->cnic }}) @endif</div>
            @endforeach
        </div>
    @endif

    <div class="footer">
        <div>Guest Booking Copy - Prices Hidden</div>
        @if($booking->tenant->website)
            <div>{{ $booking->tenant->website }}</div>
        @endif
    </div>
</body>
</html>
