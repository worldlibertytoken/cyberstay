<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $q = $request->get('q', '');

        $staff = User::query()
            ->where('tenant_id', TenantContext::id())
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($q, fn ($query) => $query->where(function ($inner) use ($q) {
                $inner->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('role', 'like', "%$q%");
            }))
            ->orderBy('name')
            ->get();

        return view('staff.index', compact('staff'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['owner', 'manager', 'receptionist'])],
        ]);

        User::create([
            ...$data,
            'tenant_id' => TenantContext::id(),
            'is_active' => true,
        ]);

        return back()->with('success', 'Staff member added.');
    }

    public function update(Request $request, User $staff)
    {
        $this->assertSameHotel($staff);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', Rule::unique('users', 'email')->ignore($staff->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(['owner', 'manager', 'receptionist'])],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $staff->update([
            ...$data,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Staff member updated.');
    }

    public function destroy(Request $request, User $staff)
    {
        $this->assertSameHotel($staff);

        if ($staff->id === $request->user()->id) {
            return back()->with('error', 'You cannot remove your own account.');
        }

        $staff->delete();

        return back()->with('success', 'Staff member removed.');
    }

    private function assertSameHotel(User $staff): void
    {
        if ((int) $staff->tenant_id !== (int) TenantContext::id()) {
            abort(404);
        }
    }
}
