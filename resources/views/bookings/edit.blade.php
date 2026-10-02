@extends('layouts.app')

@section('title', 'Edit booking')

@section('content')
    @include('bookings._form', [
        'booking' => $booking,
        'action' => route('bookings.update', $booking),
        'method' => 'PUT',
        'rooms' => $rooms,
        'selectedRoom' => old('room_id', $booking->room_id),
    ])
@endsection
