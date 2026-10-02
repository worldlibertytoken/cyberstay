@extends('layouts.app')

@section('title', $definition['title'])
@section('subtitle', $definition['subtitle'])
@section('actions')
    <a href="{{ route('dashboard.entries.download', $scope) }}" class="btn btn-ghost">
        <x-icon name="download" size="sm" /> Download PDF
    </a>
@endsection

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card p-4">
            <div class="text-xs uppercase tracking-wider text-muted">Entries</div>
            <div class="stat-num mt-2 text-3xl font-semibold">{{ $totals['count'] }}</div>
        </div>
    </div>

    <div class="card mt-5 overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Stay</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <div class="font-medium">#{{ $booking->id }}</div>
                            <div class="text-xs text-muted">Created {{ $booking->created_at?->format('d M Y, h:i A') }}</div>
                        </td>
                        <td>
                            <div class="font-medium">{{ $booking->customer->name }}</div>
                            <div class="text-xs text-muted">{{ $booking->customer->phone }}</div>
                        </td>
                        <td>
                            <span class="inline-flex items-center gap-1.5">
                                <x-icon name="bed" size="sm" class="text-muted" /> {{ $booking->room->number }}
                            </span>
                        </td>
                        <td>
                            <div>{{ $booking->check_in->format('d M Y') }} - {{ $booking->check_out->format('d M Y') }}</div>
                            <div class="text-xs text-muted">{{ $booking->nights }} night(s)</div>
                        </td>
                        <td>
                            <x-status-badge :status="$booking->status">{{ $booking->statusLabel() }}</x-status-badge>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('dashboard.entries.show', ['scope' => $scope, 'booking' => $booking]) }}" class="btn btn-ghost">
                                    <x-icon name="info" size="sm" /> View single entry
                                </a>
                                <a href="{{ route('dashboard.entries.single.download', ['scope' => $scope, 'booking' => $booking]) }}" class="btn btn-ghost">
                                    <x-icon name="download" size="sm" /> Single PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">No entries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $bookings->links() }}</div>
@endsection
