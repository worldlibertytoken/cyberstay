<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid email or password.']);
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();

            return back()->withErrors(['email' => 'This account is disabled.']);
        }

        if ($user->isSuperAdmin()) {
            return redirect()->route('hotels.index');
        }

        return redirect()->route('dashboard');
    }

    public function quickLogin(Request $request): RedirectResponse
    {
        if (! app()->isLocal()) {
            abort(404);
        }

        $validated = $request->validate([
            'role' => ['required', 'string', Rule::in(['super_admin', 'admin', 'receptionist'])],
        ]);

        $user = match ($validated['role']) {
            'super_admin' => User::where('role', 'super_admin')->where('is_active', true)->orderBy('id')->first(),
            'admin' => User::where('role', 'owner')->where('is_active', true)->orderBy('id')->first()
                ?? User::where('role', 'manager')->where('is_active', true)->orderBy('id')->first(),
            default => User::where('role', 'receptionist')->where('is_active', true)->orderBy('id')->first(),
        };

        if (! $user) {
            return back()->withErrors([
                'quick_login' => 'No active account is available for this role.',
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($user->isSuperAdmin()) {
            return redirect()->route('hotels.index');
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
