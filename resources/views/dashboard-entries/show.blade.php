@extends('layouts.app')

@section('title', $definition['title'].' entry')
@section('subtitle', 'Booking #'.$booking->id.' · '.$booking->customer->name)
@section('actions')
    <a href="{{ route('dashboard.entries.index', $scope) }}" class="btn btn-ghost">
        <x-icon name="arrow-right" size="sm" class="rotate-180" /> Back to list
    </a>
    <a href="{{ route('dashboard.entries.single.download', ['scope' => $scope, 'booking' => $booking]) }}" class="btn btn-ghost">
        <x-icon name="download" size="sm" /> Download PDF
    </a>
    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-primary">
        <x-icon name="receipt" size="sm" /> Open full booking
    </a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-1">
        <div class="space-y-5">
            <div class="card p-5">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <x-status-badge :status="$booking->status">{{ $booking->statusLabel() }}</x-status-badge>
                    <div class="text-xs text-muted">Created {{ $booking->created_at?->format('d M Y, h:i A') }}</div>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-panel-2 p-4">
                        <div class="text-xs uppercase tracking-wider text-muted">Guest</div>
                        <div class="mt-2 font-semibold">{{ $booking->customer->name }}</div>
                        <div class="text-sm text-muted">S/O {{ $booking->customer->father_name ?: '—' }}</div>
                        <div class="text-sm text-muted">Phone: {{ $booking->customer->phone }}</div>
                        <div class="text-sm text-muted">CNIC: {{ $booking->customer->cnic ?: '—' }}</div>
                        <div class="text-sm text-muted">Address: {{ $booking->customer->address ?: '—' }}</div>
                    </div>
                    <div class="rounded-2xl bg-panel-2 p-4">
                        <div class="text-xs uppercase tracking-wider text-muted">Stay details</div>
                        <div class="mt-2 font-semibold">Room {{ $booking->room->number }}</div>
                        <div class="text-sm text-muted">Check-in: {{ $booking->check_in->format('d M Y') }}</div>
                        <div class="text-sm text-muted">Check-out: {{ $booking->check_out->format('d M Y') }}</div>
                        <div class="text-sm text-muted">Nights: {{ $booking->nights }}</div>
                        <div class="text-sm text-muted">Guest count: {{ $booking->guestCount() }}</div>
                        <div class="text-sm text-muted">Vehicle: {{ $booking->vehicle_number ?: '—' }}</div>
                    </div>
                </div>

                @if($booking->notes)
                    <div class="mt-4 rounded-2xl bg-panel-2 p-4">
                        <div class="text-xs uppercase tracking-wider text-muted">Notes</div>
                        <p class="mt-2 text-sm text-muted">{{ $booking->notes }}</p>
                    </div>
                @endif
                @if($booking->checkout_note)
                    <div class="mt-4 rounded-2xl bg-panel-2 p-4">
                        <div class="text-xs uppercase tracking-wider text-muted">Checkout note</div>
                        <p class="mt-2 text-sm text-muted">{{ $booking->checkout_note }}</p>
                    </div>
                @endif
            </div>

            <div class="card p-5">
                <x-section-title icon="cart">Extra charges</x-section-title>
                <div class="mt-3 overflow-x-auto">
                    <table class="table">
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
                                    <td>{{ $item->qty }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-muted">No extra charges.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
