@extends('layouts.app')

@section('title', 'Bookings')
@section('subtitle', 'Active stays and upcoming arrivals')
@section('actions')
    <a href="{{ route('bookings.create') }}" class="btn btn-primary">
        <x-icon name="calendar-plus" size="sm" /> New booking
    </a>
@endsection

@section('content')
    @php
        $filters = ['active' => 'Active', 'today' => 'Today', 'reserved' => 'Reserved', 'checked_in' => 'In-house', 'checked_out' => 'Checked out', 'cancelled' => 'Cancelled'];
    @endphp
    <div class="mb-4 flex gap-2 overflow-x-auto pb-1">
        @foreach($filters as $key => $label)
            <a href="{{ route('bookings.index', ['status' => $key]) }}" class="btn {{ $status === $key ? 'btn-primary' : 'btn-ghost' }} text-sm">{{ $label }}</a>
        @endforeach
    </div>
    <div class="card overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Dates</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <a href="{{ route('bookings.show', $booking) }}" class="font-medium hover:text-crimson-soft">{{ $booking->customer->name }}</a>
                            <div class="text-xs text-muted">{{ $booking->customer->phone }}</div>
                            @if($booking->status === 'checked_out')
                                <div class="text-xs text-muted">{{ $booking->paymentStatusLabel() }} · Due @money($booking->balance_due ?? 0)</div>
                            @endif
                        </td>
                        <td>
                            <span class="inline-flex items-center gap-1.5">
                                <x-icon name="bed" size="sm" class="text-muted" /> {{ $booking->room->number }}
                            </span>
                        </td>
                        <td>
                            <div>{{ $booking->check_in->format('d M') }} – {{ $booking->check_out->format('d M') }} <span class="text-muted">({{ $booking->nights }}n)</span></div>
                            <div class="text-xs text-muted">In: {{ $booking->checked_in_at?->format('d M, h:i A') ?? 'Pending' }}</div>
                            <div class="text-xs text-muted">Out: {{ $booking->checked_out_at?->format('d M, h:i A') ?? 'Pending' }}</div>
                        </td>
                        <td>
                            <div class="stat-num font-medium">@money($booking->grand_total)</div>
                            <div class="text-xs text-muted">Paid @money($booking->amountPaid())</div>
                        </td>
                        <td><x-status-badge :status="$booking->status">{{ $booking->statusLabel() }}</x-status-badge></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No bookings.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $bookings->links() }}</div>
@endsection
