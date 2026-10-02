@extends('layouts.app')

@section('title', 'Edit customer')

@section('content')
    @include('customers._form', ['customer' => $customer, 'action' => route('customers.update', $customer), 'method' => 'PUT'])
    <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="mx-auto mt-4 max-w-xl" onsubmit="return confirm('Delete this customer?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Delete customer</button>
    </form>
@endsection
