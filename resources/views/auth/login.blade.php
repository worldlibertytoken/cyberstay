@extends('layouts.guest')

@section('title', 'Sign in')

@section('content')
    <div class="mb-8 lg:hidden">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-crimson text-white">
            <x-icon name="spark" size="lg" />
        </div>
        <h1 class="mt-4 text-3xl font-semibold tracking-tight">CyberStay</h1>
    </div>
    <h1 class="hidden text-3xl font-semibold tracking-tight lg:block">Welcome back</h1>
    <p class="mt-2 text-sm text-muted">Sign in to manage rooms, bookings, and bills.</p>

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
        @csrf
        <div>
            <label class="field-label"><x-icon name="user" size="sm" /> Email</label>
            <input class="input" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div>
            <label class="field-label"><x-icon name="id" size="sm" /> Password</label>
            <input class="input" type="password" name="password" required>
        </div>
        <label class="flex items-center gap-2 text-sm text-muted">
            <input type="checkbox" name="remember" class="rounded border-line bg-panel">
            Remember me
        </label>
        @error('email')
            <p class="text-sm text-rose-700">{{ $message }}</p>
        @enderror
        <button class="btn btn-primary w-full">
            Sign in <x-icon name="arrow-right" size="sm" />
        </button>
    </form>

    <div class="mt-6 rounded-xl border border-line bg-panel p-4" data-pwa-install-card>
        <p class="text-xs font-semibold uppercase tracking-wide text-muted">Install app</p>
        <p class="mt-1 text-xs text-muted">Use CyberStay like a phone or tablet app. No Play Store needed.</p>
        <button type="button" class="btn btn-ghost mt-3 w-full" data-pwa-install>
            <x-icon name="download" size="sm" /> Download App
        </button>
        <p class="mt-3 hidden text-xs text-muted" data-pwa-ios>
            On iPhone/iPad: tap Share, then Add to Home Screen.
        </p>
        <p class="mt-3 hidden text-xs text-muted" data-pwa-fallback>
            If no install popup appears, open your browser menu and choose Install app or Add to Home Screen.
        </p>
        <p class="mt-3 hidden text-xs text-emerald-700" data-pwa-installed>
            CyberStay is already installed on this device.
        </p>
    </div>

    @if (app()->isLocal())
        <div class="mt-6 rounded-xl border border-line bg-panel-2 p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-muted">Quick Login (Local Only)</p>
            <p class="mt-1 text-xs text-muted">Use one click sign-in for demo accounts.</p>

            @error('quick_login')
                <p class="mt-3 text-sm text-rose-700">{{ $message }}</p>
            @enderror

            <div class="mt-3 grid gap-2 sm:grid-cols-3">
                <form method="POST" action="{{ route('login.quick') }}">
                    @csrf
                    <input type="hidden" name="role" value="super_admin">
                    <button class="btn btn-ghost w-full" type="submit">Super Admin</button>
                </form>

                <form method="POST" action="{{ route('login.quick') }}">
                    @csrf
                    <input type="hidden" name="role" value="admin">
                    <button class="btn btn-ghost w-full" type="submit">Admin</button>
                </form>

                <form method="POST" action="{{ route('login.quick') }}">
                    @csrf
                    <input type="hidden" name="role" value="receptionist">
                    <button class="btn btn-ghost w-full" type="submit">Receptionist</button>
                </form>
            </div>
        </div>
    @endif
@endsection
