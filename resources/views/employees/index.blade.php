@extends('layouts.app')

@section('title', 'Employees')
@section('subtitle', 'Payroll for '.$monthLabel)
@section('actions')
    <button type="button" class="btn btn-primary" data-modal-open="employee-modal">
        <x-icon name="plus" size="sm" /> Add employee
    </button>
@endsection

@section('content')
    @php
        $payStateLabel = ['paid' => 'Paid', 'partial' => 'Partial', 'unpaid' => 'Unpaid'];
        $filterQuery = array_filter(['month' => $month, 'q' => $search]);
        $openForm = old('_form');
    @endphp

    <div class="card hero-band mb-6 overflow-hidden p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-crimson">Payroll</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight">{{ $monthLabel }}</h2>
                <p class="mt-1 text-sm text-muted">{{ $activeCount }} active · {{ $inactiveCount }} inactive · salary also posts to expenses</p>
            </div>
            <div class="rounded-2xl bg-white/80 px-5 py-4 text-right">
                <div class="text-xs font-semibold uppercase tracking-wider text-muted">Still due</div>
                <div class="stat-num mt-1 text-3xl font-semibold">@money($dueThisMonth)</div>
            </div>
        </div>

        <div class="mt-6 grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl bg-white/80 p-4">
                <div class="text-xs font-medium text-muted">Monthly payroll</div>
                <div class="stat-num mt-2 text-xl font-semibold">@money($monthlyPayroll)</div>
            </div>
            <div class="rounded-2xl bg-white/80 p-4">
                <div class="text-xs font-medium text-muted">Paid this month</div>
                <div class="stat-num mt-2 text-xl font-semibold">@money($paidThisMonth)</div>
            </div>
            <div class="rounded-2xl bg-white/80 p-4">
                <div class="flex items-center justify-between gap-2 text-xs font-medium text-muted">
                    <span>Progress</span>
                    <span>{{ $paidPercent }}%</span>
                </div>
                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-stone-200">
                    <span class="block h-full bg-emerald-600" style="width: {{ $paidPercent }}%"></span>
                </div>
                <div class="stat-num mt-2 text-xl font-semibold">{{ $activeCount }} on roster</div>
            </div>
        </div>
    </div>

    <form method="GET" class="card mb-6 grid gap-3 p-4 sm:grid-cols-12 sm:items-end">
        <input type="hidden" name="status" value="{{ $status }}">
        <div class="sm:col-span-4">
            <label class="field-label"><x-icon name="calendar" size="sm" /> Payroll month</label>
            <select class="input" name="month">
                @foreach($months as $value => $label)
                    <option value="{{ $value }}" @selected($month === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-5">
            <label class="field-label"><x-icon name="search" size="sm" /> Search</label>
            <input class="input" name="q" value="{{ $search }}" placeholder="Name, role, phone">
        </div>
        <div class="sm:col-span-3">
            <button class="btn btn-ghost w-full"><x-icon name="search" size="sm" /> Apply</button>
        </div>
    </form>

    <div class="mb-5 flex flex-wrap gap-2">
        @foreach(['active' => 'Active', 'due' => 'Due this month', 'inactive' => 'Inactive', 'all' => 'All'] as $key => $label)
            <a class="btn {{ $status === $key ? 'btn-primary' : 'btn-ghost' }}" href="{{ route('employees.index', array_merge($filterQuery, ['status' => $key])) }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="space-y-3">
        @forelse($employees as $employee)
            @php
                $paidRatio = (float) $employee->salary > 0
                    ? min(100, ((float) $employee->paid_this_month / (float) $employee->salary) * 100)
                    : 100;
                $desk = [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'phone' => $employee->phone,
                    'position' => $employee->position,
                    'salary' => (float) $employee->salary,
                    'hire_date' => optional($employee->hire_date)->toDateString(),
                    'status' => $employee->status,
                    'notes' => $employee->notes,
                    'update_url' => route('employees.update', $employee),
                    'pay_url' => route('employees.pay', $employee),
                    'paid' => $employee->payments
                        ->groupBy('for_month')
                        ->map(fn ($rows) => round((float) $rows->sum('amount'), 2))
                        ->all(),
                    'payments' => $employee->payments->map(function ($payment) use ($employee) {
                        $monthKey = (string) $payment->for_month;
                        $monthLabel = preg_match('/^\d{4}-\d{2}$/', $monthKey)
                            ? \Illuminate\Support\Carbon::createFromFormat('!Y-m', $monthKey)->format('M Y')
                            : ($monthKey ?: '—');

                        return [
                            'amount_label' => 'Rs '.number_format((float) $payment->amount, 0),
                            'paid_on' => $payment->paid_on->format('d M Y'),
                            'for_month_label' => $monthLabel,
                            'notes' => $payment->notes,
                            'void_url' => route('employees.payments.destroy', [$employee, $payment]),
                        ];
                    })->values(),
                ];
            @endphp
            <div class="card p-4 sm:p-5">
                <div class="flex flex-wrap items-start gap-4">
                    <div class="flex min-w-0 flex-1 items-start gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-sm font-semibold text-crimson">
                            {{ $employee->initials() }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-lg font-semibold">{{ $employee->name }}</h2>
                                @if($employee->isActive())
                                    <x-status-badge :status="$employee->pay_state === 'paid' ? 'active' : ($employee->pay_state === 'partial' ? 'reserved' : 'occupied')">
                                        {{ $payStateLabel[$employee->pay_state] }}
                                    </x-status-badge>
                                @else
                                    <x-status-badge status="inactive">Inactive</x-status-badge>
                                @endif
                            </div>
                            <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-muted">
                                <span>{{ $employee->position ?: 'Staff' }}</span>
                                @if($employee->phone)
                                    <span>{{ $employee->phone }}</span>
                                @endif
                                @if($employee->hire_date)
                                    <span>Hired {{ $employee->hire_date->format('d M Y') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="w-full sm:ml-auto sm:w-56">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="text-xs text-muted">{{ $monthLabel }}</span>
                            <span class="stat-num font-semibold">@money($employee->salary)/mo</span>
                        </div>
                        <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-stone-200">
                            <span class="block h-full {{ $employee->pay_state === 'paid' ? 'bg-emerald-600' : ($employee->pay_state === 'partial' ? 'bg-amber-500' : 'bg-crimson') }}" style="width: {{ $paidRatio }}%"></span>
                        </div>
                        <div class="mt-2 flex justify-between text-xs text-muted">
                            <span>Paid @money($employee->paid_this_month)</span>
                            <span>Due @money($employee->due_this_month)</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary" data-employee-action="pay" data-employee='@json($desk)'>
                        <x-icon name="money" size="sm" /> Pay salary
                    </button>
                    <button type="button" class="btn btn-ghost" data-employee-action="history" data-employee='@json($desk)'>
                        <x-icon name="receipt" size="sm" /> History
                    </button>
                    <button type="button" class="btn btn-ghost" data-employee-action="edit" data-employee='@json($desk)'>
                        <x-icon name="edit" size="sm" /> Edit
                    </button>
                    @if($employee->payments->isEmpty())
                        <form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Remove this employee?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger"><x-icon name="trash" size="sm" /> Remove</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="card p-10 text-center">
                <x-icon-box class="mx-auto" tone="amber"><x-icon name="briefcase" /></x-icon-box>
                <p class="mt-4 font-semibold">No employees in this view</p>
                <p class="mt-1 text-sm text-muted">Add hotel staff here. Login users stay under Staff.</p>
                <button type="button" class="btn btn-primary mt-5" data-modal-open="employee-modal">
                    <x-icon name="plus" size="sm" /> Add employee
                </button>
            </div>
        @endforelse
    </div>

    <datalist id="employee-positions">
        @foreach(\App\Models\Employee::POSITIONS as $position)
            <option value="{{ $position }}"></option>
        @endforeach
    </datalist>

    <div id="employee-modal" class="modal-overlay" data-open-on-load="{{ $openForm === 'create' ? '1' : '0' }}" role="dialog" aria-modal="true" aria-labelledby="employee-modal-title">
        <div class="modal-panel p-5 sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-3">
                <x-section-title icon="briefcase" hint="Payroll people only. Desk logins are on Staff.">
                    <span id="employee-modal-title">Add employee</span>
                </x-section-title>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-stone-100" data-modal-close aria-label="Close">
                    <x-icon name="x" />
                </button>
            </div>
            <form method="POST" action="{{ route('employees.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="_form" value="create">
                @include('employees._fields', ['prefix' => 'create'])
                <div class="flex gap-2 pt-2">
                    <button type="button" class="btn btn-ghost flex-1" data-modal-close>Cancel</button>
                    <button class="btn btn-primary flex-1"><x-icon name="check" size="sm" /> Save employee</button>
                </div>
            </form>
        </div>
    </div>

    <div id="employee-edit-modal" class="modal-overlay" data-open-on-load="{{ $openForm === 'edit' ? '1' : '0' }}" role="dialog" aria-modal="true" aria-labelledby="employee-edit-title">
        <div class="modal-panel p-5 sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-3">
                <x-section-title icon="edit" hint="Mark inactive to keep salary history.">
                    <span id="employee-edit-title">Edit employee</span>
                </x-section-title>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-stone-100" data-modal-close aria-label="Close">
                    <x-icon name="x" />
                </button>
            </div>
            <form id="employee-edit-form" method="POST" action="{{ old('update_url', '#') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="_form" value="edit">
                <input type="hidden" name="update_url" id="employee-edit-action" value="{{ old('update_url') }}">
                @include('employees._fields', ['prefix' => 'edit'])
                <div class="flex gap-2 pt-2">
                    <button type="button" class="btn btn-ghost flex-1" data-modal-close>Cancel</button>
                    <button class="btn btn-primary flex-1"><x-icon name="check" size="sm" /> Save changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="employee-pay-modal" class="modal-overlay" data-open-on-load="{{ $openForm === 'pay' ? '1' : '0' }}" role="dialog" aria-modal="true" aria-labelledby="employee-pay-title">
        <div class="modal-panel p-5 sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-3">
                <x-section-title icon="money" hint="This also records a salary expense.">
                    <span id="employee-pay-title">Pay salary</span>
                </x-section-title>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-stone-100" data-modal-close aria-label="Close">
                    <x-icon name="x" />
                </button>
            </div>
            <form id="employee-pay-form" method="POST" action="{{ old('pay_url', '#') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="_form" value="pay">
                <input type="hidden" name="pay_url" id="pay-url" value="{{ old('pay_url') }}">
                <div class="rounded-2xl bg-panel-2 p-4">
                    <div class="font-semibold" data-pay-name>Staff</div>
                    <div class="mt-1 text-sm text-muted" data-pay-due>Due Rs 0</div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="field-label"><x-icon name="calendar" size="sm" /> For month</label>
                        <select class="input" name="for_month" id="pay-month" required>
                            @foreach($months as $value => $label)
                                <option value="{{ $value }}" @selected(old('for_month', $month) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label"><x-icon name="calendar" size="sm" /> Paid on</label>
                        <input class="input" type="date" name="paid_on" value="{{ old('paid_on', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                    </div>
                </div>
                <div>
                    <label class="field-label"><x-icon name="money" size="sm" /> Amount</label>
                    <input class="input text-lg font-semibold" type="number" min="0.01" step="0.01" name="amount" id="pay-amount" value="{{ old('amount') }}" required>
                </div>
                <div>
                    <label class="field-label"><x-icon name="edit" size="sm" /> Notes</label>
                    <input class="input" name="notes" value="{{ old('notes') }}" placeholder="Advance, bonus, remaining balance…">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" class="btn btn-ghost flex-1" data-modal-close>Cancel</button>
                    <button class="btn btn-primary flex-1"><x-icon name="check" size="sm" /> Record payment</button>
                </div>
            </form>
        </div>
    </div>

    <div id="employee-history-modal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="employee-history-title">
        <div class="modal-panel p-5 sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-3">
                <x-section-title icon="receipt" hint="Voiding a payment also removes the salary expense.">
                    <span id="employee-history-title">Salary history</span>
                </x-section-title>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-stone-100" data-modal-close aria-label="Close">
                    <x-icon name="x" />
                </button>
            </div>
            <div id="employee-history-list" class="space-y-3"></div>
        </div>
    </div>
@endsection
