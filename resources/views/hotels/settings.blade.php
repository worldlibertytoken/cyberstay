@extends('layouts.app')

@section('title', 'Hotel Settings')
@section('subtitle', 'Manage hotel information and branding')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="card p-5 sm:p-6">
                <x-section-title icon="settings" hint="Update your hotel's basic information and branding.">
                    Hotel Information
                </x-section-title>

                <form method="PUT" action="{{ route('hotel-settings.update') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label class="field-label"><x-icon name="building" size="sm" /> Hotel Name</label>
                        <input class="input" type="text" name="name" value="{{ old('name', $hotel->name) }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label"><x-icon name="mail" size="sm" /> Email Address</label>
                        <input class="input" type="email" name="email" value="{{ old('email', $hotel->email) }}" placeholder="hotel@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label"><x-icon name="phone" size="sm" /> Phone Number</label>
                        <input class="input" type="tel" name="phone" value="{{ old('phone', $hotel->phone) }}" placeholder="0300-1234567">
                        @error('phone')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="field-label"><x-icon name="map" size="sm" /> City</label>
                            <input class="input" type="text" name="city" value="{{ old('city', $hotel->city) }}" placeholder="Lahore">
                            @error('city')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="field-label"><x-icon name="shield" size="sm" /> Tax ID / Registration</label>
                            <input class="input" type="text" name="tax_id" value="{{ old('tax_id', $hotel->tax_id) }}" placeholder="NTN / CNIC">
                            @error('tax_id')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="field-label"><x-icon name="map-pin" size="sm" /> Address</label>
                        <textarea class="input" name="address" rows="2" placeholder="Street address, area, postal code">{{ old('address', $hotel->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label"><x-icon name="globe" size="sm" /> Website (optional)</label>
                        <input class="input" type="url" name="website" value="{{ old('website', $hotel->website) }}" placeholder="https://example.com">
                        @error('website')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label"><x-icon name="edit" size="sm" /> Description (optional)</label>
                        <textarea class="input" name="description" rows="3" placeholder="Brief description about your hotel">{{ old('description', $hotel->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button class="btn btn-primary w-full sm:w-auto">
                            <x-icon name="check" size="sm" /> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-5 sm:p-6">
                <x-section-title icon="image" hint="Upload a logo for your hotel branding.">
                    Logo
                </x-section-title>

                <form method="PUT" action="{{ route('hotel-settings.update') }}" enctype="multipart/form-data" class="mt-6">
                    @csrf

                    @if($hotel->logo)
                        <div class="mb-4 flex justify-center rounded-xl bg-panel-2 p-4">
                            <img src="{{ asset($hotel->logo) }}" alt="Hotel logo" class="max-h-32 max-w-full">
                        </div>
                    @endif

                    <div class="mb-4 rounded-xl border-2 border-dashed border-line px-6 py-8 text-center">
                        <input class="hidden" type="file" name="logo" id="logo-input" accept="image/*">
                        <label for="logo-input" class="cursor-pointer">
                            <x-icon name="image" class="mx-auto mb-2" size="lg" />
                            <p class="text-sm font-medium">Click to upload logo</p>
                            <p class="text-xs text-muted">PNG, JPG up to 4MB</p>
                        </label>
                    </div>

                    <button class="btn btn-primary w-full">
                        <x-icon name="check" size="sm" /> Upload Logo
                    </button>
                </form>

                @if($hotel->logo)
                    <form method="PUT" action="{{ route('hotel-settings.update') }}" class="mt-3">
                        @csrf
                        <input type="hidden" name="logo" value="">
                        <button type="submit" class="btn btn-ghost w-full" onclick="return confirm('Remove logo?')">
                            <x-icon name="trash" size="sm" /> Remove Logo
                        </button>
                    </form>
                @endif
            </div>

            <div class="card rounded-2xl border border-line bg-panel-2 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Info</p>
                <p class="mt-2 text-sm text-muted">
                    Hotel settings are used on invoices, reports, and throughout the system. Keep them up to date for proper branding.
                </p>
                <p class="mt-3 text-xs text-muted">
                    Last updated <strong>{{ $hotel->updated_at->format('d M Y, H:i A') }}</strong>
                </p>
            </div>
        </div>
    </div>
@endsection
