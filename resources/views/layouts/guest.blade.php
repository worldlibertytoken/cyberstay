<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <x-pwa-head />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') · CyberStay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-night text-ink antialiased app-body">
    <div class="grid min-h-dvh lg:grid-cols-2">
        <div class="relative hidden overflow-hidden bg-panel lg:flex lg:flex-col lg:justify-between p-12">
            <div class="absolute -left-20 -top-20 h-72 w-72 rounded-full bg-crimson/10 blur-3xl"></div>
            <div class="absolute right-0 bottom-0 h-80 w-80 rounded-full bg-amber-200/40 blur-3xl"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-crimson text-white shadow-[0_10px_24px_rgb(159_18_57_/_0.25)]">
                    <x-icon name="spark" size="lg" />
                </div>
                <h2 class="mt-8 text-4xl font-semibold tracking-tight">CyberStay</h2>
                <p class="mt-3 max-w-md text-muted">Fast hotel bookings, flexible nightly rates, and a front desk staff can use without training.</p>
                <div class="mt-10 grid max-w-md gap-3">
                    <div class="flex items-center gap-3 rounded-2xl border border-line bg-night px-4 py-3">
                        <x-icon-box size="sm"><x-icon name="calendar" size="sm" /></x-icon-box>
                        <div>
                            <div class="text-sm font-medium">Book at the desk</div>
                            <div class="text-xs text-muted">Set the rate only when the guest arrives.</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl border border-line bg-night px-4 py-3">
                        <x-icon-box size="sm" tone="green"><x-icon name="bed" size="sm" /></x-icon-box>
                        <div>
                            <div class="text-sm font-medium">See rooms at a glance</div>
                            <div class="text-xs text-muted">Occupied, reserved, free, and maintenance.</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl border border-line bg-night px-4 py-3">
                        <x-icon-box size="sm" tone="amber"><x-icon name="cart" size="sm" /></x-icon-box>
                        <div>
                            <div class="text-sm font-medium">POS, expenses, staff</div>
                            <div class="text-xs text-muted">One workspace for daily operations.</div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="relative text-sm text-muted">Multi-tenant · Rooms · POS · Expenses</p>
        </div>
        <div class="flex items-center justify-center px-5 py-10 sm:px-6 sm:py-12">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
