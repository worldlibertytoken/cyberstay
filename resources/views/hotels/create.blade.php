@extends('layouts.app')

@section('title', 'Add hotel')
@section('subtitle', 'New tenant with an owner login')

@section('content')
    <form method="POST" action="{{ route('hotels.store') }}" class="card mx-auto max-w-2xl space-y-4 p-6">
        @csrf
        <x-section-title icon="building" hint="Each hotel is a separate workspace">Hotel</x-section-title>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="field-label"><x-icon name="building" size="sm" /> Hotel name</label>
                <input class="input" name="name" value="{{ old('name') }}" required>
            </div>
            <div>
                <label class="field-label"><x-icon name="phone" size="sm" /> Phone</label>
                <input class="input" name="phone" value="{{ old('phone') }}">
            </div>
            <div>
                <label class="field-label"><x-icon name="user" size="sm" /> Email</label>
                <input class="input" type="email" name="email" value="{{ old('email') }}">
            </div>
            <div>
                <label class="field-label"><x-icon name="map" size="sm" /> City</label>
                <input class="input" name="city" value="{{ old('city') }}">
            </div>
            <div>
                <label class="field-label"><x-icon name="map" size="sm" /> Address</label>
                <input class="input" name="address" value="{{ old('address') }}">
            </div>
        </div>
        <div class="border-t border-line pt-4">
            <x-section-title icon="staff" hint="This person can enter the hotel after you create it">Owner login</x-section-title>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="field-label"><x-icon name="user" size="sm" /> Owner name</label>
                    <input class="input" name="owner_name" value="{{ old('owner_name') }}" required>
                </div>
                <div>
                    <label class="field-label"><x-icon name="user" size="sm" /> Owner email</label>
                    <input class="input" type="email" name="owner_email" value="{{ old('owner_email') }}" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="field-label"><x-icon name="id" size="sm" /> Password</label>
                    <input class="input" type="password" name="owner_password" required>
                </div>
            </div>
        </div>
        <button class="btn btn-primary"><x-icon name="plus" size="sm" /> Create hotel</button>
    </form>
@endsection
