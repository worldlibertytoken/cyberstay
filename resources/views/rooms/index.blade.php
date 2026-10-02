@extends('layouts.app')

@section('title', 'Rooms')
@section('subtitle', 'Physical rooms only — pricing is set at booking')
@section('actions')
    <a href="{{ route('rooms.create') }}" class="btn btn-primary">
        <x-icon name="plus" size="sm" /> Add room
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
        @forelse($rooms as $room)
            <div class="card room-tile {{ $room->status === 'maintenance' ? 'maintenance' : 'available' }} p-4">
                <div class="flex items-start justify-between gap-2">
                    <x-icon-box size="sm" :tone="$room->status === 'maintenance' ? 'zinc' : 'crimson'">
                        <x-icon name="bed" size="sm" />
                    </x-icon-box>
                    <x-status-badge :status="$room->status">{{ ucfirst($room->status) }}</x-status-badge>
                </div>
                <div class="stat-num mt-4 text-2xl font-semibold">{{ $room->number }}</div>
                <div class="mt-1 text-xs capitalize text-muted">{{ $room->type }} · {{ $room->bed_type }} bed · Floor {{ $room->floor ?: '—' }}</div>
                <div class="mt-1 text-xs text-muted">Capacity: {{ $room->max_capacity }} guest{{ $room->max_capacity === 1 ? '' : 's' }}</div>
                <a class="mt-3 inline-flex items-center gap-1 text-xs text-crimson-soft" href="{{ route('rooms.edit', $room) }}">
                    Edit <x-icon name="edit" size="sm" />
                </a>
            </div>
        @empty
            <p class="col-span-full text-sm text-muted">No rooms yet.</p>
        @endforelse
    </div>
@endsection
