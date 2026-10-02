<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Tenant::query()->withCount(['rooms', 'users', 'bookings'])->latest()->get();

        return view('hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('hotels.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'city' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:120'],
            'owner_email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'owner_password' => ['required', 'string', 'min:6'],
        ]);

        $hotel = Tenant::create([
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name']),
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'city' => $data['city'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => true,
        ]);

        User::create([
            'tenant_id' => $hotel->id,
            'name' => $data['owner_name'],
            'email' => $data['owner_email'],
            'password' => $data['owner_password'],
            'role' => 'owner',
            'is_active' => true,
        ]);

        return redirect()->route('hotels.index')->with('success', 'Hotel created.');
    }

    public function edit(Tenant $hotel)
    {
        return view('hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Tenant $hotel)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'city' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $hotel->update([
            ...$data,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('hotels.index')->with('success', 'Hotel updated.');
    }

    public function enter(Tenant $hotel)
    {
        if (! $hotel->is_active) {
            return back()->with('error', 'This hotel is disabled.');
        }

        session(['current_tenant_id' => $hotel->id]);

        return redirect()->route('dashboard')->with('success', 'Now working in '.$hotel->name.'.');
    }

    public function leave()
    {
        session()->forget('current_tenant_id');

        return redirect()->route('hotels.index');
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name) ?: 'hotel';
        $base = $slug;
        $i = 1;

        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
