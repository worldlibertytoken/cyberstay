@props(['tone' => 'crimson', 'size' => 'md'])

@php
    $tones = [
        'crimson' => 'bg-rose-100 text-crimson',
        'green' => 'bg-emerald-100 text-emerald-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'blue' => 'bg-sky-100 text-sky-700',
        'zinc' => 'bg-stone-100 text-muted',
        'white' => 'bg-white text-ink border border-line',
    ];
    $sizes = [
        'sm' => 'h-9 w-9 rounded-xl',
        'md' => 'h-11 w-11 rounded-2xl',
        'lg' => 'h-12 w-12 rounded-2xl',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center justify-center '.($sizes[$size] ?? $sizes['md']).' '.($tones[$tone] ?? $tones['crimson'])]) }}>
    {{ $slot }}
</span>
