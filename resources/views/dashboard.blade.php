@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', now()->format('l, d M Y').' · live hotel picture')
@section('actions')
    <a href="{{ route('bookings.create') }}" class="btn btn-primary hidden sm:inline-flex">
        <x-icon name="calendar-plus" size="sm" /> New booking
    </a>
@endsection

@section('content')
    @php
        $base = max(1, $roomCount);
        $occEnd = ($occupied / $base) * 360;
        $resEnd = $occEnd + ($reservedToday / $base) * 360;
        $availEnd = $resEnd + ($available / $base) * 360;
        $moneyBase = max(1, (float) $todayReceived + (float) $todayPending);
        $receivedShare = ((float) $todayReceived / $moneyBase) * 100;
        $pendingShare = ((float) $todayPending / $moneyBase) * 100;
    @endphp

    <section class="card hero-band overflow-hidden p-5 sm:p-7">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-crimson">Today at the desk</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">{{ $hotel->name ?? 'Hotel' }}</h2>
                <p class="mt-1 text-sm text-muted">{{ $roomCount }} rooms · {{ $checkedIn->count() }} guests in-house · {{ $todayCheckIns->count() }} arriving</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="badge bg-rose-100 text-crimson">{{ $occupancyPercent }}% occupied</span>
                <span class="badge bg-emerald-100 text-emerald-800">{{ $available }} free</span>
            </div>
        </div>

        <div class="mt-8 grid gap-8 xl:grid-cols-[auto_1fr]">
            <div class="flex flex-col items-center justify-center">
                <div class="occ-ring" style="--occ: {{ $occEnd }}deg; --res: {{ $resEnd }}deg; --avail: {{ $availEnd }}deg;">
                    <div class="occ-ring-inner">
                        <div>
                            <div class="stat-num text-4xl font-semibold">{{ $occupancyPercent }}%</div>
                            <div class="mt-1 text-[11px] font-semibold uppercase tracking-wider text-muted">Occupancy</div>
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-xs text-muted">{{ $occupied }} of {{ $roomCount }} rooms occupied</p>
            </div>

            <div class="space-y-5">
                <div class="stack-bar">
                    <span class="bg-crimson" style="width: {{ $occupied / $base * 100 }}%"></span>
                    <span class="bg-amber-500" style="width: {{ $reservedToday / $base * 100 }}%"></span>
                    <span class="bg-emerald-600" style="width: {{ $available / $base * 100 }}%"></span>
                    <span class="bg-stone-400" style="width: {{ $maintenance / $base * 100 }}%"></span>
                </div>
                <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                    <div class="rounded-2xl bg-rose-50 px-4 py-4">
                        <div class="flex items-center gap-2 text-xs font-medium text-crimson"><span class="legend-dot bg-crimson"></span> Occupied</div>
                        <div class="stat-num mt-2 text-3xl font-semibold">{{ $occupied }}</div>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 px-4 py-4">
                        <div class="flex items-center gap-2 text-xs font-medium text-emerald-700"><span class="legend-dot bg-emerald-600"></span> Available</div>
                        <div class="stat-num mt-2 text-3xl font-semibold">{{ $available }}</div>
                    </div>
                    <div class="rounded-2xl bg-amber-50 px-4 py-4">
                        <div class="flex items-center gap-2 text-xs font-medium text-amber-700"><span class="legend-dot bg-amber-500"></span> Reserved</div>
                        <div class="stat-num mt-2 text-3xl font-semibold">{{ $reservedToday }}</div>
                    </div>
                    <div class="rounded-2xl bg-stone-100 px-4 py-4">
                        <div class="flex items-center gap-2 text-xs font-medium text-stone-600"><span class="legend-dot bg-stone-400"></span> Maintenance</div>
                        <div class="stat-num mt-2 text-3xl font-semibold">{{ $maintenance }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-5 grid gap-4 md:grid-cols-3">
        <a href="{{ route('dashboard.entries.index', 'in-house') }}" class="card block p-5">
            <div class="flex items-center justify-between">
                <x-icon-box tone="green"><x-icon name="users" /></x-icon-box>
                <span class="text-xs font-semibold uppercase tracking-wider text-muted">In-house</span>
            </div>
            <div class="stat-num mt-4 text-4xl font-semibold">{{ $checkedIn->count() }}</div>
            <p class="mt-1 text-sm text-muted">Guests currently staying</p>
        </a>
        <a href="{{ route('dashboard.entries.index', 'arrivals') }}" class="card block p-5">
            <div class="flex items-center justify-between">
                <x-icon-box tone="amber"><x-icon name="door" /></x-icon-box>
                <span class="text-xs font-semibold uppercase tracking-wider text-muted">Arrivals</span>
            </div>
            <div class="stat-num mt-4 text-4xl font-semibold">{{ $todayCheckIns->count() }}</div>
            <p class="mt-1 text-sm text-muted">Check-ins today</p>
        </a>
        <a href="{{ route('dashboard.entries.index', 'departures') }}" class="card block p-5">
            <div class="flex items-center justify-between">
                <x-icon-box><x-icon name="logout" /></x-icon-box>
                <span class="text-xs font-semibold uppercase tracking-wider text-muted">Departures</span>
            </div>
            <div class="stat-num mt-4 text-4xl font-semibold">{{ $todayCheckOuts->count() }}</div>
            <p class="mt-1 text-sm text-muted">Check-outs today</p>
        </a>
    </section>

    <section class="mt-5 grid gap-4 lg:grid-cols-2">
        <div class="card p-5">
            <x-section-title icon="money" hint="Bookings created today">Today’s take</x-section-title>
            <div class="stat-num mt-5 text-4xl font-semibold">@money($todayRevenue)</div>
            <p class="mt-2 text-sm text-muted">{{ $todayCheckIns->count() }} arrivals · {{ $todayCheckOuts->count() }} departures</p>
        </div>
        <div class="card p-5">
            <x-section-title icon="trend" tone="green" hint="Collected and pending from today's bookings">Today collections</x-section-title>
            <div class="stat-num mt-5 text-4xl font-semibold">@money($todayReceived)</div>
            <div class="mt-4 stack-bar">
                <span class="bg-emerald-600" style="width: {{ $receivedShare }}%"></span>
                <span class="bg-rose-400" style="width: {{ $pendingShare }}%"></span>
            </div>
            <div class="mt-3 flex justify-between text-xs text-muted">
                <span class="inline-flex items-center gap-1.5"><span class="legend-dot bg-emerald-600"></span> Received @money($todayReceived)</span>
                <span class="inline-flex items-center gap-1.5"><span class="legend-dot bg-rose-400"></span> Pending @money($todayPending)</span>
            </div>
        </div>
    </section>

    <section class="mt-8">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <x-section-title icon="bed" hint="Only currently available rooms are shown in sequence.">Available rooms</x-section-title>
            <div class="flex flex-wrap gap-3 text-xs text-muted">
                <span class="inline-flex items-center gap-1.5"><span class="legend-dot bg-emerald-600"></span> Free</span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
            @forelse($availableRooms as $room)
                @php
                    $status = $room->occupancyStatus();
                    $stay = $room->bookings->first(fn ($booking) => $booking->status === 'checked_in')
                        ?? $room->bookings->first(fn ($booking) => $booking->status === 'reserved');
                @endphp
                <div class="card room-tile {{ $status }} p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="stat-num text-2xl font-semibold">{{ $room->number }}</div>
                            <div class="mt-0.5 text-xs capitalize text-muted">{{ $room->type }}</div>
                        </div>
                        <x-status-badge :status="$status">{{ ucfirst($status) }}</x-status-badge>
                    </div>
                    @if($stay)
                        <div class="mt-4 truncate text-sm font-medium">{{ $stay->customer->name ?? 'Guest' }}</div>
                        <a class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-crimson" href="{{ route('bookings.show', $stay) }}">
                            Open stay <x-icon name="arrow-right" size="sm" />
                        </a>
                    @else
                        <a class="mt-4 inline-flex items-center gap-1 text-xs font-medium text-crimson" href="{{ route('bookings.create', ['room_id' => $room->id]) }}">
                            Book room <x-icon name="plus" size="sm" />
                        </a>
                    @endif
                </div>
            @empty
                <p class="col-span-full text-sm text-muted">No available rooms right now.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="card p-5">
            <x-section-title icon="clock" tone="amber" hint="Arrivals scheduled for today">Today’s check-ins</x-section-title>
            <div class="mt-4 space-y-2">
                @forelse($todayCheckIns as $booking)
                    <a href="{{ route('bookings.show', $booking) }}" class="list-row">
                        <div class="flex min-w-0 items-center gap-3">
                            <x-icon-box tone="amber" size="sm"><x-icon name="door" size="sm" /></x-icon-box>
                            <div class="min-w-0">
                                <div class="truncate font-medium">{{ $booking->customer->name }}</div>
                                <div class="text-xs text-muted">Room {{ $booking->room->number }}</div>
                            </div>
                        </div>
                        <x-status-badge :status="$booking->status">{{ $booking->statusLabel() }}</x-status-badge>
                    </a>
                @empty
                    <p class="text-sm text-muted">No check-ins today.</p>
                @endforelse
            </div>
        </div>
        <div class="card p-5">
            <x-section-title icon="users" tone="green" hint="Guests currently occupying rooms">Currently in-house</x-section-title>
            <div class="mt-4 space-y-2">
                @forelse($checkedIn as $booking)
                    <a href="{{ route('bookings.show', $booking) }}" class="list-row">
                        <div class="flex min-w-0 items-center gap-3">
                            <x-icon-box tone="green" size="sm"><x-icon name="bed" size="sm" /></x-icon-box>
                            <div class="min-w-0">
                                <div class="truncate font-medium">{{ $booking->customer->name }}</div>
                                <div class="text-xs text-muted">Room {{ $booking->room->number }} · until {{ $booking->check_out->format('d M') }}</div>
                            </div>
                        </div>
                        <span class="stat-num text-sm font-semibold">@money($booking->grand_total)</span>
                    </a>
                @empty
                    <p class="text-sm text-muted">No guests checked in.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
