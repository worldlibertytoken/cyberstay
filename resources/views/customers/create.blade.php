@extends('layouts.app')

@section('title', 'Add customer')

@section('content')
    @include('customers._form', ['customer' => null, 'action' => route('customers.store'), 'method' => 'POST'])
@endsection
