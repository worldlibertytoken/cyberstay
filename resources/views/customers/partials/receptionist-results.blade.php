@if($q !== '')
    <div class="mt-5 space-y-4">
        @forelse($customers as $customer)
            <div class="card p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $customer->name }}</h3>
                        <p class="text-sm text-muted">Phone: {{ $customer->phone }} · CNIC: {{ $customer->cnic ?: '—' }}</p>
                    </div>
                    <span class="badge bg-stone-100 text-stone-700">{{ $customer->bookings->count() }} stay(s)</span>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Stay date</th>
                                <th>Check-in time</th>
                                <th>Check-out time</th>
                                <th>Room</th>
                                <th>Status</th>
                                <th>Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->bookings as $booking)
                                <tr>
                                    <td>
                                        <div>{{ $booking->check_in->format('d M Y') }} - {{ $booking->check_out->format('d M Y') }}</div>
                                        <div class="text-xs text-muted">{{ $booking->nights }} night(s)</div>
                                    </td>
                                    <td>{{ $booking->checked_in_at?->format('h:i A') ?: '—' }}</td>
                                    <td>{{ $booking->checked_out_at?->format('h:i A') ?: '—' }}</td>
                                    <td>{{ $booking->room?->number ?: '—' }}</td>
                                    <td>
                                        <x-status-badge :status="$booking->status">{{ $booking->statusLabel() }}</x-status-badge>
                                    </td>
                                    <td class="font-medium">@money($booking->amountPaid())</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-muted">No stays found for this customer.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="card p-5 text-sm text-muted">No customer found for your search.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
@else
    <div class="mt-5 card p-5 text-sm text-muted">
        Search using phone number, customer name, or CNIC.
    </div>
@endif
