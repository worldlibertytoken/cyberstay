@extends('layouts.app')

@section('title', 'New booking')
@section('subtitle', 'Pick a room, set tonight’s rate, then take guest details')
@section('actions')
    <a href="{{ route('bookings.index') }}" class="btn btn-ghost hidden sm:inline-flex">
        <x-icon name="calendar" size="sm" /> All bookings
    </a>
@endsection

@section('content')
    <div class="card hero-band mb-6 overflow-hidden p-5 sm:p-6">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-crimson">Front desk</p>
        <h2 class="mt-2 text-2xl font-semibold tracking-tight">Create a stay</h2>
        <p class="mt-1 max-w-2xl text-sm text-muted">Rooms have no stored rate. Enter the price for this booking, then capture the main guest. Overlapping dates on the same room are blocked.</p>
        <div class="step-rail mt-5">
            <div class="step-pill">
                <span class="step-num">1</span>
                <div>
                    <div class="text-sm font-semibold">Stay</div>
                    <div class="text-xs text-muted">Room, dates, rate</div>
                </div>
            </div>
            <div class="step-pill">
                <span class="step-num">2</span>
                <div>
                    <div class="text-sm font-semibold">Guest</div>
                    <div class="text-xs text-muted">Name, ID, vehicle</div>
                </div>
            </div>
            <div class="step-pill">
                <span class="step-num">3</span>
                <div>
                    <div class="text-sm font-semibold">People</div>
                    <div class="text-xs text-muted">Male, female, children</div>
                </div>
            </div>
            <div class="step-pill">
                <span class="step-num">4</span>
                <div>
                    <div class="text-sm font-semibold">Confirm</div>
                    <div class="text-xs text-muted">Check the bill</div>
                </div>
            </div>
        </div>
    </div>

    @include('bookings._form', [
        'booking' => null,
        'action' => route('bookings.store'),
        'method' => 'POST',
        'rooms' => $rooms,
        'selectedRoom' => old('room_id', $selectedRoom),
    ])
@endsection
