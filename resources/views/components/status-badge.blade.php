@props(['status' => ''])

@php
    $key = strtolower((string) $status);
    $classes = match ($key) {
        'available', 'active' => 'bg-emerald-100 text-emerald-800',
        'paid' => 'bg-emerald-100 text-emerald-800',
        'partial' => 'bg-amber-100 text-amber-800',
        'unpaid', 'due' => 'bg-rose-100 text-crimson',
        'occupied', 'checked_in', 'in-house' => 'bg-rose-100 text-crimson',
        'reserved' => 'bg-amber-100 text-amber-800',
        'maintenance', 'cancelled', 'disabled', 'inactive' => 'bg-stone-200 text-stone-600',
        'checked_out' => 'bg-sky-100 text-sky-800',
        default => 'bg-stone-100 text-muted',
    };
@endphp

<span {{ $attributes->merge(['class' => 'badge '.$classes]) }}>{{ $slot }}</span>
