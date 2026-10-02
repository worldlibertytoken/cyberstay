@extends('layouts.app')

@section('title', 'Add room')

@section('content')
    @include('rooms._form', ['room' => null, 'action' => route('rooms.store'), 'method' => 'POST'])
@endsection
