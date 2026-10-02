@extends('layouts.app')

@section('title', 'Edit hotel')

@section('content')
    <form method="POST" action="{{ route('hotels.update', $hotel) }}" class="card mx-auto max-w-2xl space-y-4 p-6">
        @csrf
        @method('PUT')
        <x-section-title icon="building">Hotel details</x-section-title>
        <div>
            <label class="field-label"><x-icon name="building" size="sm" /> Hotel name</label>
            <input class="input" name="name" value="{{ old('name', $hotel->name) }}" required>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="field-label"><x-icon name="phone" size="sm" /> Phone</label>
                <input class="input" name="phone" value="{{ old('phone', $hotel->phone) }}">
            </div>
            <div>
                <label class="field-label"><x-icon name="user" size="sm" /> Email</label>
                <input class="input" type="email" name="email" value="{{ old('email', $hotel->email) }}">
            </div>
            <div>
                <label class="field-label"><x-icon name="map" size="sm" /> City</label>
                <input class="input" name="city" value="{{ old('city', $hotel->city) }}">
            </div>
            <div>
                <label class="field-label"><x-icon name="map" size="sm" /> Address</label>
                <input class="input" name="address" value="{{ old('address', $hotel->address) }}">
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" {{ $hotel->is_active ? 'checked' : '' }}>
            Active
        </label>
        <button class="btn btn-primary"><x-icon name="check" size="sm" /> Save</button>
    </form>
@endsection
