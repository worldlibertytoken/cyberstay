@extends('layouts.app')

@section('title', 'Staff Logins')
@section('subtitle', 'People who can use this hotel system')
@section('actions')
    <button type="button" class="btn btn-primary" data-modal-open="staff-modal">
        <x-icon name="plus" size="sm" /> Add staff
    </button>
@endsection

@section('content')
    @php
        $activeCount = $staff->where('is_active', true)->count();
        $inactiveCount = $staff->where('is_active', false)->count();
        $openForm = old('_form');
        $roleColors = [
            'owner' => 'bg-blue-50 text-blue-700',
            'manager' => 'bg-purple-50 text-purple-700',
            'receptionist' => 'bg-amber-50 text-amber-700',
        ];
    @endphp

    <div class="card hero-band mb-6 overflow-hidden p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-crimson">Team Access</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight">Staff Directory</h2>
                <p class="mt-1 text-sm text-muted">{{ $activeCount }} active · {{ $inactiveCount }} inactive · receptionists, managers, and owners</p>
            </div>
            <div class="rounded-2xl bg-white/80 px-5 py-4 text-right">
                <div class="text-xs font-semibold uppercase tracking-wider text-muted">Total staff</div>
                <div class="stat-num mt-1 text-3xl font-semibold">{{ $staff->count() }}</div>
            </div>
        </div>

        <div class="mt-6 grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl bg-white/80 p-4">
                <div class="text-xs font-medium text-muted">Active accounts</div>
                <div class="stat-num mt-2 text-xl font-semibold">{{ $activeCount }}</div>
            </div>
            <div class="rounded-2xl bg-white/80 p-4">
                <div class="text-xs font-medium text-muted">Disabled accounts</div>
                <div class="stat-num mt-2 text-xl font-semibold">{{ $inactiveCount }}</div>
            </div>
            <div class="rounded-2xl bg-white/80 p-4">
                <div class="text-xs font-medium text-muted">Roles assigned</div>
                <div class="stat-num mt-2 text-xl font-semibold">
                    {{ $staff->unique('role')->count() !== 0 ? $staff->unique('role')->count() : '–' }}
                </div>
            </div>
        </div>
    </div>

    <form method="GET" class="card mb-6 grid gap-3 p-4 sm:grid-cols-12 sm:items-end">
        <div class="sm:col-span-7">
            <label class="field-label"><x-icon name="search" size="sm" /> Search</label>
            <input class="input" name="q" value="{{ request('q') }}" placeholder="Name, email, phone, role">
        </div>
        <div class="sm:col-span-5">
            <button class="btn btn-ghost w-full"><x-icon name="search" size="sm" /> Search staff</button>
        </div>
    </form>

    <div class="mb-5 flex flex-wrap gap-2">
        <a class="btn {{ !request('status') ? 'btn-primary' : 'btn-ghost' }}" href="{{ route('staff.index') }}">All</a>
        <a class="btn {{ request('status') === 'active' ? 'btn-primary' : 'btn-ghost' }}" href="{{ route('staff.index', ['status' => 'active']) }}">
            Active
        </a>
        <a class="btn {{ request('status') === 'inactive' ? 'btn-primary' : 'btn-ghost' }}" href="{{ route('staff.index', ['status' => 'inactive']) }}">
            Inactive
        </a>
    </div>

    <div class="space-y-3">
        @forelse($staff as $member)
            <div class="card p-4 sm:p-5">
                <div class="flex flex-wrap items-start gap-4">
                    <div class="flex min-w-0 flex-1 items-start gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-sm font-semibold text-crimson">
                            {{ $member->initials() }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-lg font-semibold">{{ $member->name }}</h2>
                                <x-status-badge :status="$member->is_active ? 'active' : 'inactive'">
                                    {{ $member->is_active ? 'Active' : 'Inactive' }}
                                </x-status-badge>
                            </div>
                            <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-muted">
                                <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-xs font-medium {{ $roleColors[$member->role] ?? 'bg-stone-100 text-stone-600' }}">
                                    {{ $member->roleLabel() }}
                                </span>
                                <span>{{ $member->email }}</span>
                                @if($member->phone)
                                    <span>{{ $member->phone }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="button" class="btn btn-ghost" data-modal-open="staff-edit-modal" data-staff="{{ base64_encode(json_encode(['id' => $member->id, 'name' => $member->name, 'email' => $member->email, 'phone' => $member->phone, 'role' => $member->role, 'is_active' => $member->is_active])) }}">
                        <x-icon name="edit" size="sm" /> Edit
                    </button>
                    @if($member->id !== auth()->user()->id || auth()->user()->canManageHotel())
                        <form method="POST" action="{{ route('staff.destroy', $member) }}" onsubmit="return confirm('Remove this staff member? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger"><x-icon name="trash" size="sm" /> Remove</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="card p-10 text-center">
                <x-icon-box class="mx-auto" tone="amber"><x-icon name="users" /></x-icon-box>
                <p class="mt-4 font-semibold">No staff accounts yet</p>
                <p class="mt-1 text-sm text-muted">Add team members who will use this hotel system to manage bookings and operations.</p>
                <button type="button" class="btn btn-primary mt-5" data-modal-open="staff-modal">
                    <x-icon name="plus" size="sm" /> Add first staff member
                </button>
            </div>
        @endforelse
    </div>

    <!-- Add Staff Modal -->
    <div id="staff-modal" class="modal-overlay" data-open-on-load="{{ $openForm === 'create' ? '1' : '0' }}" role="dialog" aria-modal="true" aria-labelledby="staff-modal-title">
        <div class="modal-panel p-5 sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-3">
                <x-section-title icon="users" hint="Receptionists book rooms. Managers see expenses too.">
                    <span id="staff-modal-title">Add staff member</span>
                </x-section-title>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-stone-100" data-modal-close aria-label="Close">
                    <x-icon name="x" />
                </button>
            </div>

            <form method="POST" action="{{ route('staff.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="_form" value="create">
                <div>
                    <label class="field-label"><x-icon name="user" size="sm" /> Full name</label>
                    <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                    @error('name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="field-label"><x-icon name="mail" size="sm" /> Email</label>
                    <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="john@hotel.test" required>
                    @error('email')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="field-label"><x-icon name="phone" size="sm" /> Phone (optional)</label>
                    <input class="input" type="tel" name="phone" value="{{ old('phone') }}" placeholder="0300-1234567">
                </div>
                <div>
                    <label class="field-label"><x-icon name="shield" size="sm" /> Role</label>
                    <select class="input" name="role" required>
                        <option value="">Select a role</option>
                        <option value="receptionist" {{ old('role') === 'receptionist' ? 'selected' : '' }}>
                            Receptionist – Books rooms and guests
                        </option>
                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>
                            Manager – Handles expenses and staff
                        </option>
                        <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>
                            Owner – Full access
                        </option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="field-label"><x-icon name="lock" size="sm" /> Password</label>
                    <input class="input" type="password" name="password" placeholder="••••••••" required>
                    @error('password')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" class="btn btn-ghost flex-1" data-modal-close>Cancel</button>
                    <button class="btn btn-primary flex-1"><x-icon name="check" size="sm" /> Create account</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Staff Modal -->
    <div id="staff-edit-modal" class="modal-overlay" data-open-on-load="{{ $openForm === 'edit' ? '1' : '0' }}" role="dialog" aria-modal="true" aria-labelledby="staff-edit-title">
        <div class="modal-panel p-5 sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-3">
                <x-section-title icon="edit" hint="Update staff details or toggle account status.">
                    <span id="staff-edit-title">Edit staff member</span>
                </x-section-title>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-stone-100" data-modal-close aria-label="Close">
                    <x-icon name="x" />
                </button>
            </div>

            <form id="staff-edit-form" method="POST" action="{{ old('update_url', '#') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="_form" value="edit">
                <div>
                    <label class="field-label"><x-icon name="user" size="sm" /> Full name</label>
                    <input class="input" type="text" name="name" id="staff-name" value="{{ old('name') }}" placeholder="John Doe" required>
                </div>
                <div>
                    <label class="field-label"><x-icon name="mail" size="sm" /> Email</label>
                    <input class="input" type="email" name="email" id="staff-email" value="{{ old('email') }}" placeholder="john@hotel.test" required>
                </div>
                <div>
                    <label class="field-label"><x-icon name="phone" size="sm" /> Phone (optional)</label>
                    <input class="input" type="tel" name="phone" id="staff-phone" value="{{ old('phone') }}" placeholder="0300-1234567">
                </div>
                <div>
                    <label class="field-label"><x-icon name="shield" size="sm" /> Role</label>
                    <select class="input" name="role" id="staff-role" required>
                        <option value="receptionist">Receptionist – Books rooms and guests</option>
                        <option value="manager">Manager – Handles expenses and staff</option>
                        <option value="owner">Owner – Full access</option>
                    </select>
                </div>
                <div>
                    <label class="field-label"><x-icon name="lock" size="sm" /> Password (leave empty to keep current)</label>
                    <input class="input" type="password" name="password" placeholder="••••••••">
                </div>
                <label class="flex items-center gap-2 text-sm text-muted">
                    <input type="checkbox" name="is_active" id="staff-active" class="rounded border-line bg-panel" value="1">
                    <span>Active account</span>
                </label>
                <div class="flex gap-2 pt-2">
                    <button type="button" class="btn btn-ghost flex-1" data-modal-close>Cancel</button>
                    <button class="btn btn-primary flex-1"><x-icon name="check" size="sm" /> Save changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-modal-open="staff-edit-modal"]').forEach(button => {
            button.addEventListener('click', function () {
                const encoded = this.getAttribute('data-staff');
                const data = JSON.parse(atob(encoded));
                document.getElementById('staff-name').value = data.name;
                document.getElementById('staff-email').value = data.email;
                document.getElementById('staff-phone').value = data.phone || '';
                document.getElementById('staff-role').value = data.role;
                document.getElementById('staff-active').checked = data.is_active;
                document.getElementById('staff-edit-form').action = '{{ route("staff.update", ":id") }}'.replace(':id', data.id);
            });
        });
    </script>
@endsection
