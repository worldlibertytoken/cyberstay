@extends('layouts.app')

@section('title', 'Bookings Report')
@section('subtitle', 'View and export all bookings for selected dates')

@section('content')
    @php
        $totalBookings = $bookings->count();
        $totalRevenue = (float) $bookings->sum('grand_total');
        $totalPaid = (float) $bookings->sum(fn ($booking) => $booking->amountPaid());
        $outstandingBalance = (float) $bookings->sum('balance_due');
        $averageBookingValue = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;
    @endphp

    <div class="card hero-band mb-6 overflow-hidden p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-crimson">Reports</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight">Bookings Performance</h2>
                <p class="mt-1 text-sm text-muted">
                    {{ $from->format('d M Y') }} to {{ $to->format('d M Y') }} · {{ $totalBookings }} booking(s)
                </p>
            </div>
            <div class="rounded-2xl bg-white/80 px-5 py-4 text-right">
                <div class="text-xs font-semibold uppercase tracking-wider text-muted">Outstanding</div>
                <div class="stat-num mt-1 text-3xl font-semibold">Rs {{ number_format($outstandingBalance, 0) }}</div>
            </div>
        </div>

        <div class="kpi-row mt-6">
            <div class="rounded-2xl bg-white/80 p-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="text-xs font-medium text-muted">Total bookings</div>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-rose-50 text-crimson">
                        <x-icon name="calendar" size="sm" />
                    </span>
                </div>
                <div class="stat-num mt-2 text-xl font-semibold">{{ $totalBookings }}</div>
            </div>
            <div class="rounded-2xl bg-white/80 p-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="text-xs font-medium text-muted">Total revenue</div>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-rose-50 text-crimson">
                        <x-icon name="receipt" size="sm" />
                    </span>
                </div>
                <div class="stat-num mt-2 text-xl font-semibold">Rs {{ number_format($totalRevenue, 0) }}</div>
            </div>
            <div class="rounded-2xl bg-white/80 p-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="text-xs font-medium text-muted">Total paid</div>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-rose-50 text-crimson">
                        <x-icon name="money" size="sm" />
                    </span>
                </div>
                <div class="stat-num mt-2 text-xl font-semibold">Rs {{ number_format($totalPaid, 0) }}</div>
            </div>
            <div class="rounded-2xl bg-white/80 p-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="text-xs font-medium text-muted">Avg booking value</div>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-rose-50 text-crimson">
                        <x-icon name="trend" size="sm" />
                    </span>
                </div>
                <div class="stat-num mt-2 text-xl font-semibold">Rs {{ number_format($averageBookingValue, 0) }}</div>
            </div>
        </div>
    </div>

    <form method="GET" class="card mb-6 grid gap-4 p-5 sm:grid-cols-12 sm:items-end">
        <div class="sm:col-span-4">
            <label class="field-label"><x-icon name="calendar" size="sm" /> From Date</label>
            <input class="input" type="date" name="from" value="{{ $from->toDateString() }}" required>
        </div>
        <div class="sm:col-span-4">
            <label class="field-label"><x-icon name="calendar" size="sm" /> To Date</label>
            <input class="input" type="date" name="to" value="{{ $to->toDateString() }}" required>
        </div>
        <div class="sm:col-span-4 grid grid-cols-2 gap-2">
            <button type="submit" class="btn btn-ghost w-full"><x-icon name="search" size="sm" /> Apply</button>
            <a href="{{ route('bookings-report', ['from' => $from->toDateString(), 'to' => $to->toDateString(), 'export' => 'pdf']) }}" class="btn btn-primary w-full">
                <x-icon name="download" size="sm" /> Report PDF
            </a>
        </div>
    </form>

    <div class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-line px-5 py-4">
            <div>
                <h3 class="text-base font-semibold">Booking Records</h3>
                <p class="text-sm text-muted">Download guest-specific copies without prices from each row.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Stay</th>
                        <th>Nights</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="font-medium">BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="font-medium">{{ $booking->customer->name }}</div>
                                <div class="text-xs text-muted">{{ $booking->customer->phone }}</div>
                            </td>
                            <td>{{ $booking->room->number }}</td>
                            <td>
                                <div>{{ $booking->check_in->format('d M Y') }}</div>
                                <div class="text-xs text-muted">to {{ $booking->check_out->format('d M Y') }}</div>
                                <div class="text-xs text-muted">In: {{ $booking->checked_in_at?->format('d M Y, h:i A') ?? 'Pending' }}</div>
                                <div class="text-xs text-muted">Out: {{ $booking->checked_out_at?->format('d M Y, h:i A') ?? 'Pending' }}</div>
                            </td>
                            <td class="text-center">{{ $booking->nights }}</td>
                            <td class="text-right font-medium">Rs {{ number_format((float) $booking->grand_total, 0) }}</td>
                            <td class="text-right">Rs {{ number_format((float) $booking->amountPaid(), 0) }}</td>
                            <td class="text-right">Rs {{ number_format((float) $booking->balance_due, 0) }}</td>
                            <td>
                                <x-status-badge :status="$booking->paymentState()">
                                    {{ $booking->paymentStatusLabel() }}
                                </x-status-badge>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('bookings.guest-copy', $booking) }}" class="btn btn-ghost whitespace-nowrap">
                                    <x-icon name="download" size="sm" /> Guest Copy
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-10 text-center">
                                <div class="mx-auto max-w-md">
                                    <x-icon-box class="mx-auto" tone="amber"><x-icon name="calendar" /></x-icon-box>
                                    <p class="mt-4 font-semibold">No bookings in this date range</p>
                                    <p class="mt-1 text-sm text-muted">Change the dates above and apply filters to load records.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
