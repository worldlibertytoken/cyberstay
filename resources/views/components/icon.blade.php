@props(['name', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
        'lg' => 'h-6 w-6',
        'xl' => 'h-8 w-8',
    ];

    $paths = [
        'home' => '<path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z"/>',
        'calendar' => '<rect x="3.5" y="5" width="17" height="15" rx="2"/><path d="M8 3.5v3M16 3.5v3M3.5 10h17"/>',
        'calendar-plus' => '<rect x="3.5" y="5" width="17" height="15" rx="2"/><path d="M8 3.5v3M16 3.5v3M3.5 10h17M12 13v5M9.5 15.5h5"/>',
        'bed' => '<path d="M4 18V9.5A2.5 2.5 0 0 1 6.5 7H12v4h8v7M4 15h16M7 11a1.5 1.5 0 1 0 0-3"/>',
        'users' => '<circle cx="9" cy="8" r="3"/><path d="M3.5 19a5.5 5.5 0 0 1 11 0"/><circle cx="17" cy="9" r="2.2"/><path d="M16 19a4.5 4.5 0 0 1 4.5-4.2"/>',
        'user' => '<circle cx="12" cy="8" r="3.2"/><path d="M5 19.5a7 7 0 0 1 14 0"/>',
        'cart' => '<path d="M4 5h2l1.5 11h11L20 8H7"/><circle cx="9.5" cy="19" r="1.4"/><circle cx="16.5" cy="19" r="1.4"/>',
        'wallet' => '<rect x="3.5" y="6" width="17" height="13" rx="2"/><path d="M3.5 10h17"/><circle cx="16.5" cy="14.5" r="1"/>',
        'card' => '<rect x="3.5" y="6.5" width="17" height="11" rx="2"/><path d="M3.5 10h17M7 15h4"/>',
        'bookmark' => '<path d="M7 4.5h10a1 1 0 0 1 1 1V20l-6-3.2L6 20V5.5a1 1 0 0 1 1-1z"/>',
        'briefcase' => '<rect x="3.5" y="7.5" width="17" height="12" rx="2"/><path d="M9 7.5V6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1.5M3.5 13h17"/>',
        'staff' => '<circle cx="12" cy="8" r="3"/><path d="M5 19a7 7 0 0 1 14 0M16.5 6.5 18 5M7.5 6.5 6 5"/>',
        'building' => '<rect x="5" y="4" width="14" height="16" rx="1.5"/><path d="M9 8h1.5M13.5 8H15M9 12h1.5M13.5 12H15M9 16h1.5M13.5 16H15"/>',
        'logout' => '<path d="M10 5H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3M13 12h8M17 8.5 20.5 12 17 15.5"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'search' => '<circle cx="11" cy="11" r="6"/><path d="m20 20-3.5-3.5"/>',
        'phone' => '<path d="M7 4.5c0-.8.7-1.5 1.5-1.5h2c.6 0 1.1.4 1.3 1l.6 1.8a1.4 1.4 0 0 1-.3 1.5L11 9.5a12 12 0 0 0 3.5 3.5l1.2-1.1a1.4 1.4 0 0 1 1.5-.3l1.8.6c.6.2 1 .7 1 1.3v2c0 .8-.7 1.5-1.5 1.5C10.8 17 7 13.2 7 8.5z"/>',
        'id' => '<rect x="3.5" y="6" width="17" height="12" rx="2"/><circle cx="9" cy="12" r="2"/><path d="M13 11h5M13 14h3.5"/>',
        'map' => '<path d="M9 5 4 7v12l5-2 6 2 5-2V5l-5 2-6-2zM9 5v12M15 7v12"/>',
        'car' => '<path d="M4 14.5V16a1.5 1.5 0 0 0 1.5 1.5h.7a1.8 1.8 0 0 0 3.6 0h4.4a1.8 1.8 0 0 0 3.6 0h.7A1.5 1.5 0 0 0 20 16v-1.5M4 14.5 6 9.5A2 2 0 0 1 7.8 8h8.4A2 2 0 0 1 18 9.5l2 5"/>',
        'male' => '<circle cx="12" cy="14" r="5"/><path d="M16 8V5h3M19 5l-4.2 4.2"/>',
        'female' => '<circle cx="12" cy="10" r="4.5"/><path d="M12 14.5v5M9.5 17h5"/>',
        'child' => '<circle cx="12" cy="8" r="3"/><path d="M7 19v-3a5 5 0 0 1 10 0v3M9 13.5 7.5 16M15 13.5 16.5 16"/>',
        'camera' => '<path d="M8 8 9.3 6h5.4L16 8h3a1.5 1.5 0 0 1 1.5 1.5v8A1.5 1.5 0 0 1 19 19H5a1.5 1.5 0 0 1-1.5-1.5v-8A1.5 1.5 0 0 1 5 8z"/><circle cx="12" cy="13" r="3"/>',
        'money' => '<circle cx="12" cy="12" r="8"/><path d="M12 7.5v9M9.5 9.5c.6-.8 1.5-1.2 2.5-1.2 1.6 0 2.6.8 2.6 1.9s-1 1.8-2.8 2.2c-1.9.4-2.8 1-2.8 2.2 0 1.1 1.1 2 2.8 2 1.1 0 2.1-.4 2.6-1.2"/>',
        'trend' => '<path d="M4 16.5 9.5 11l3.5 3.5L20 7.5M14.5 7.5H20V13"/>',
        'clock' => '<circle cx="12" cy="12" r="8"/><path d="M12 8v4.5l3 1.8"/>',
        'check' => '<path d="m5 13 4.5 4.5L19 7"/>',
        'door' => '<path d="M6 20V6.5A1.5 1.5 0 0 1 7.5 5H16v15M6 20h13M13.5 12.5h.01"/>',
        'print' => '<path d="M7 17H5.5A1.5 1.5 0 0 1 4 15.5v-6A1.5 1.5 0 0 1 5.5 8H18.5A1.5 1.5 0 0 1 20 9.5v6a1.5 1.5 0 0 1-1.5 1.5H17M7 8V4.5h10V8M7 14h10v6H7z"/>',
        'edit' => '<path d="M4 20h4.5L19 9.5 14.5 5 4 15.5zM12.5 7.5l4 4"/>',
        'trash' => '<path d="M5 8h14M9.5 8V5.5h5V8M7 8l1 12h8l1-12"/>',
        'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'switch' => '<path d="M7 8h10l-3-3M17 16H7l3 3"/>',
        'spark' => '<path d="M12 3.5 13.6 9H19l-4.4 3.3L16.2 18 12 14.8 7.8 18l1.6-5.7L5 9h5.4z"/>',
        'receipt' => '<path d="M7 4.5h10v15l-2-1.3-2 1.3-2-1.3-2 1.3-2-1.3zM9.5 8h5M9.5 11.5h5M9.5 15h3"/>',
        'food' => '<path d="M6 4v8a2 2 0 0 0 2 2h0V4M12 4v16M16.5 4c2 2.5 2 6 0 8.5V20"/>',
        'alert' => '<path d="M12 4 3.8 19h16.4zM12 9.5v5M12 16.8h.01"/>',
        'info' => '<circle cx="12" cy="12" r="8"/><path d="M12 11v5M12 8h.01"/>',
        'chevron' => '<path d="m8 10 4 4 4-4"/>',
        'x' => '<path d="M7 7l10 10M17 7 7 17"/>',
        'image' => '<rect x="4" y="5.5" width="16" height="13" rx="2"/><circle cx="9" cy="10.5" r="1.5"/><path d="m8 16 3-3 3 2 3-4 3 5"/>',
        'download' => '<path d="M12 4v10M8 10l4 4 4-4M5 19h14"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="M12 4.5v2M12 17.5v2M4.5 12h2M17.5 12h2M6.4 6.4l1.4 1.4M16.2 16.2l1.4 1.4M17.6 6.4l-1.4 1.4M7.8 16.2l-1.4 1.4"/>',
        'share' => '<circle cx="6.5" cy="12" r="2"/><circle cx="16.5" cy="6.5" r="2"/><circle cx="16.5" cy="17.5" r="2"/><path d="m8.3 11 6.3-3.5M8.3 13l6.3 3.5"/>',
    ];
@endphp

<svg {{ $attributes->merge([
    'class' => ($sizes[$size] ?? $sizes['md']).' shrink-0',
    'viewBox' => '0 0 24 24',
    'fill' => 'none',
    'stroke' => 'currentColor',
    'stroke-width' => '1.8',
    'stroke-linecap' => 'round',
    'stroke-linejoin' => 'round',
    'aria-hidden' => 'true',
]) }}>
    {!! $paths[$name] ?? $paths['info'] !!}
</svg>
