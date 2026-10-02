@extends('layouts.app')

@section('title', 'Expenses')
@section('subtitle', 'Purchases and daily costs')
@section('actions')
    <button type="button" class="btn btn-primary" data-modal-open="expense-modal">
        <x-icon name="plus" size="sm" /> Add expense
    </button>
@endsection

@section('content')
    @php
        $maxCategory = max(1, (float) $byCategory->max('total'));
        $categoryColors = [
            'utilities' => 'bg-sky-500',
            'supplies' => 'bg-amber-500',
            'food' => 'bg-emerald-600',
            'maintenance' => 'bg-stone-500',
            'salary' => 'bg-violet-500',
            'purchase' => 'bg-crimson',
            'general' => 'bg-rose-400',
        ];
        $categoryTiles = [
            'utilities' => 'bg-sky-50 text-sky-800',
            'supplies' => 'bg-amber-50 text-amber-800',
            'food' => 'bg-emerald-50 text-emerald-800',
            'maintenance' => 'bg-stone-100 text-stone-700',
            'salary' => 'bg-violet-50 text-violet-800',
            'purchase' => 'bg-rose-50 text-crimson',
            'general' => 'bg-orange-50 text-orange-800',
        ];
    @endphp

    <div class="card hero-band mb-6 overflow-hidden p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-crimson">Cash out</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight">Expenses</h2>
                <p class="mt-1 text-sm text-muted">{{ \Illuminate\Support\Carbon::parse($from)->format('d M') }} → {{ \Illuminate\Support\Carbon::parse($to)->format('d M Y') }} · {{ $expenses->total() }} records</p>
            </div>
            <div class="rounded-2xl bg-white/80 px-5 py-4 text-right">
                <div class="text-xs font-semibold uppercase tracking-wider text-muted">Range total</div>
                <div class="stat-num mt-1 text-3xl font-semibold">@money($total)</div>
            </div>
        </div>

        @if($byCategory->isNotEmpty())
            <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($byCategory as $row)
                    <div class="rounded-2xl bg-white/80 p-4">
                        <div class="flex items-center justify-between gap-2 text-xs font-medium capitalize text-muted">
                            <span>{{ $row->category }}</span>
                            <span>{{ $row->count }}</span>
                        </div>
                        <div class="stat-num mt-2 text-xl font-semibold">@money($row->total)</div>
                        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-stone-200">
                            <span class="block h-full {{ $categoryColors[$row->category] ?? 'bg-crimson' }}" style="width: {{ ((float) $row->total / $maxCategory) * 100 }}%"></span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <form method="GET" class="card mb-6 grid gap-3 p-4 sm:grid-cols-12 sm:items-end">
        <div class="sm:col-span-4">
            <label class="field-label"><x-icon name="calendar" size="sm" /> From</label>
            <input class="input" type="date" name="from" value="{{ $from }}">
        </div>
        <div class="sm:col-span-4">
            <label class="field-label"><x-icon name="calendar" size="sm" /> To</label>
            <input class="input" type="date" name="to" value="{{ $to }}">
        </div>
        <div class="sm:col-span-4">
            <button class="btn btn-ghost w-full"><x-icon name="search" size="sm" /> Filter</button>
        </div>
    </form>

    <div class="space-y-3">
        @forelse($expenses as $expense)
            <div class="card flex flex-wrap items-center justify-between gap-4 p-4 sm:p-5">
                <div class="flex min-w-0 items-start gap-3">
                    <x-icon-box size="sm" tone="crimson"><x-icon name="wallet" size="sm" /></x-icon-box>
                    <div class="min-w-0">
                        <div class="font-semibold">{{ $expense->title }}</div>
                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-muted">
                            <span>{{ $expense->expense_date->format('d M Y') }}</span>
                            <span class="badge {{ $categoryTiles[$expense->category] ?? 'bg-stone-100 text-muted' }}">{{ ucfirst($expense->category) }}</span>
                            @if($expense->notes)
                                <span class="truncate">{{ $expense->notes }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 sm:ml-auto">
                    <div class="stat-num text-lg font-semibold">@money($expense->amount)</div>
                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('Remove this expense?')">
                        @csrf
                        @method('DELETE')
                        <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-crimson hover:bg-rose-50" aria-label="Remove">
                            <x-icon name="trash" size="sm" />
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="card p-10 text-center">
                <x-icon-box class="mx-auto" tone="amber"><x-icon name="wallet" /></x-icon-box>
                <p class="mt-4 font-semibold">No expenses in this range</p>
                <p class="mt-1 text-sm text-muted">Record a purchase with the add expense button.</p>
                <button type="button" class="btn btn-primary mt-5" data-modal-open="expense-modal">
                    <x-icon name="plus" size="sm" /> Add expense
                </button>
            </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $expenses->links() }}</div>

    <div id="expense-modal" class="modal-overlay" data-open-on-load="{{ $errors->any() ? '1' : '0' }}" role="dialog" aria-modal="true" aria-labelledby="expense-modal-title">
        <div class="modal-panel p-5 sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-3">
                <x-section-title icon="wallet" hint="Saved against this hotel for the date you pick.">
                    <span id="expense-modal-title">Add expense</span>
                </x-section-title>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-stone-100" data-modal-close aria-label="Close">
                    <x-icon name="x" />
                </button>
            </div>

            <form method="POST" action="{{ route('expenses.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="field-label"><x-icon name="edit" size="sm" /> What was purchased?</label>
                    <input class="input" name="title" value="{{ old('title') }}" placeholder="Electric bill, tea supplies…" required>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="field-label"><x-icon name="receipt" size="sm" /> Category</label>
                        <select class="input" name="category" required>
                            @foreach(\App\Models\Expense::CATEGORIES as $category)
                                <option value="{{ $category }}" @selected(old('category') === $category)>{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label"><x-icon name="money" size="sm" /> Amount</label>
                        <input class="input text-lg font-semibold" type="number" min="0" step="0.01" name="amount" value="{{ old('amount') }}" placeholder="0" required>
                    </div>
                </div>
                <div>
                    <label class="field-label"><x-icon name="calendar" size="sm" /> Date</label>
                    <input class="input" type="date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}" required>
                </div>
                <div>
                    <label class="field-label"><x-icon name="edit" size="sm" /> Notes</label>
                    <textarea class="input" name="notes" rows="2" placeholder="Optional">{{ old('notes') }}</textarea>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" class="btn btn-ghost flex-1" data-modal-close>Cancel</button>
                    <button class="btn btn-primary flex-1"><x-icon name="check" size="sm" /> Save expense</button>
                </div>
            </form>
        </div>
    </div>
@endsection
