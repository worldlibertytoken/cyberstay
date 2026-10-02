@extends('layouts.app')

@section('title', 'Edit room '.$room->number)

@section('content')
    @include('rooms._form', ['room' => $room, 'action' => route('rooms.update', $room), 'method' => 'PUT'])
    <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="mx-auto mt-4 max-w-xl" onsubmit="return confirm('Delete this room?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Delete room</button>
    </form>
@endsection
