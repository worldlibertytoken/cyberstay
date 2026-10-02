@extends('layouts.app')

@section('title', 'Hotels')
@section('subtitle', 'All tenant hotels')
@section('actions')
    <a href="{{ route('hotels.create') }}" class="btn btn-primary">
        <x-icon name="plus" size="sm" /> Add hotel
    </a>
@endsection

@section('content')
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($hotels as $hotel)
            <div class="card p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <x-icon-box><x-icon name="building" /></x-icon-box>
                        <div>
                            <h2 class="text-lg font-semibold">{{ $hotel->name }}</h2>
                            <p class="text-sm text-muted">{{ $hotel->city ?: 'No city' }}</p>
                        </div>
                    </div>
                    <x-status-badge :status="$hotel->is_active ? 'active' : 'disabled'">
                        {{ $hotel->is_active ? 'Active' : 'Disabled' }}
                    </x-status-badge>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-2 text-center text-sm">
                    <div class="rounded-xl bg-panel-2 p-3">
                        <x-icon name="bed" size="sm" class="mx-auto text-muted" />
                        <div class="stat-num mt-1 text-lg font-semibold">{{ $hotel->rooms_count }}</div>
                        <div class="text-xs text-muted">Rooms</div>
                    </div>
                    <div class="rounded-xl bg-panel-2 p-3">
                        <x-icon name="staff" size="sm" class="mx-auto text-muted" />
                        <div class="stat-num mt-1 text-lg font-semibold">{{ $hotel->users_count }}</div>
                        <div class="text-xs text-muted">Staff</div>
                    </div>
                    <div class="rounded-xl bg-panel-2 p-3">
                        <x-icon name="calendar" size="sm" class="mx-auto text-muted" />
                        <div class="stat-num mt-1 text-lg font-semibold">{{ $hotel->bookings_count }}</div>
                        <div class="text-xs text-muted">Bookings</div>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <form method="POST" action="{{ route('hotels.enter', $hotel) }}" class="flex-1">
                        @csrf
                        <button class="btn btn-primary w-full"><x-icon name="arrow-right" size="sm" /> Enter</button>
                    </form>
                    <a href="{{ route('hotels.edit', $hotel) }}" class="btn btn-ghost"><x-icon name="edit" size="sm" /> Edit</a>
                </div>
            </div>
        @empty
            <p class="text-muted">No hotels yet.</p>
        @endforelse
    </div>
@endsection
