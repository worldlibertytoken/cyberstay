@extends('layouts.app')

@section('title', 'Customers')
@section('subtitle', 'Guests saved for faster check-in')
@section('actions')
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <x-icon name="plus" size="sm" /> Add customer
    </a>
@endsection

@section('content')
    <form class="mb-4" method="GET">
        <label class="field-label"><x-icon name="search" size="sm" /> Search</label>
        <input class="input max-w-md" name="q" value="{{ $q }}" placeholder="Search name, phone, CNIC">
    </form>
    <div class="card overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Father</th>
                    <th>Phone</th>
                    <th>ID card</th>
                    <th>Address</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="font-medium">{{ $customer->name }}</td>
                        <td>{{ $customer->father_name ?: '—' }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->cnic ?: '—' }}</td>
                        <td class="max-w-xs truncate">{{ $customer->address ?: '—' }}</td>
                        <td class="text-right">
                            <a class="inline-flex items-center gap-1 text-sm text-crimson-soft" href="{{ route('customers.edit', $customer) }}">
                                Edit <x-icon name="edit" size="sm" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $customers->links() }}</div>
@endsection
