<?php

namespace App\Http\Controllers;

use App\Support\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HotelSettingsController extends Controller
{
    public function show(): View
    {
        $hotel = TenantContext::tenant();

        return view('hotels.settings', compact('hotel'));
    }

    public function update(Request $request): RedirectResponse
    {
        $hotel = TenantContext::tenant();

        abort_if(! $request->user()->canManageHotel(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'url', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('logo')) {
            if ($hotel->logo) {
                Storage::disk('public')->delete($hotel->logo);
            }
            $validated['logo'] = 'storage/'.ltrim(
                Storage::disk('public')->putFile('logos', $request->file('logo')),
                '/'
            );
        }

        $hotel->update($validated);

        return back()->with('success', 'Hotel settings saved successfully.');
    }
}
