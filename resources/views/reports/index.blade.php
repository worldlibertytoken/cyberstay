@extends('layouts.app')

@section('title', 'Reports Hub')
@section('subtitle', 'Booking, operating, payroll, P/L, and audit intelligence')

@section('actions')
    <a href="{{ route('reports.export', ['report' => 'dashboard', 'from' => $from, 'to' => $to]) }}" class="btn btn-ghost text-sm">
        <x-icon name="print" size="sm" /> Dashboard PDF
    </a>
    <a href="{{ route('reports.export', ['report' => 'audit', 'from' => $from, 'to' => $to]) }}" class="btn btn-primary text-sm">
        <x-icon name="receipt" size="sm" /> Audit PDF
    </a>
@endsection

@section('content')
    @php
        $icons = [
            'bookings' => 'calendar',
            'expenses' => 'wallet',
            'salaries' => 'briefcase',
            'profit-loss' => 'trend',
            'audit' => 'receipt',
        ];
    @endphp

    <div class="relative mb-6 overflow-hidden rounded-[2rem] border border-line bg-[radial-gradient(circle_at_top_left,_rgba(190,24,93,0.22),_transparent_34%),linear-gradient(135deg,_#fff8f1,_#fff,_#f8efe6)] p-6 shadow-[0_20px_60px_rgb(28_25_23_/_0.08)] sm:p-7">
        <div class="absolute -top-16 right-0 h-44 w-44 rounded-full bg-crimson/10 blur-3xl"></div>
        <div class="absolute right-16 bottom-0 h-28 w-28 rounded-full bg-amber-400/10 blur-2xl"></div>

        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-crimson/15 bg-white/70 px-3 py-1 text-[0.7rem] font-semibold uppercase tracking-[0.24em] text-crimson">
                    <x-icon name="spark" size="sm" /> Intelligence Center
                </div>
                <h2 class="mt-4 text-3xl font-semibold tracking-tight text-ink sm:text-4xl">{{ $tenant->name ?? 'CyberStay' }} reporting suite</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-muted">
                    Branded reporting for stays, operating costs, payroll, profitability, and audit visibility across {{ $rangeLabel }}.
                </p>
                <div class="mt-4 flex flex-wrap gap-3 text-xs text-muted">
                    <span class="rounded-full border border-line bg-white/85 px-3 py-1.5">{{ $tenant->city ?: 'Hotel operations' }}</span>
                    @if($tenant?->phone)
                        <span class="rounded-full border border-line bg-white/85 px-3 py-1.5">{{ $tenant->phone }}</span>
                    @endif
                    @if($tenant?->email)
                        <span class="rounded-full border border-line bg-white/85 px-3 py-1.5">{{ $tenant->email }}</span>
                    @endif
                </div>
            </div>

            <div class="w-full max-w-xl rounded-[1.5rem] border border-line bg-white/85 p-4 shadow-[0_12px_30px_rgb(28_25_23_/_0.06)] backdrop-blur">
                <div class="mb-4 flex flex-wrap gap-2">
                    @foreach($presetDefinitions as $preset)
                        <form method="GET" class="contents">
                            <button
                                type="submit"
                                name="preset"
                                value="{{ $preset['key'] }}"
                                class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-xs font-semibold uppercase tracking-[0.12em] transition {{ $activePreset === $preset['key'] ? 'border-crimson bg-crimson text-white shadow-[0_8px_18px_rgb(159_18_57_/_0.18)]' : 'border-line bg-white text-muted hover:border-crimson/40 hover:text-crimson' }}"
                            >
                                {{ $preset['label'] }}
                            </button>
                        </form>
                    @endforeach
                </div>

                <form method="GET" class="grid gap-3 md:grid-cols-2">
                    <div>
                        <label class="field-label">From</label>
                        <input class="input" type="date" name="from" value="{{ $from }}">
                    </div>
                    <div>
                        <label class="field-label">To</label>
                        <input class="input" type="date" name="to" value="{{ $to }}">
                    </div>
                    <button class="btn btn-primary md:col-span-2 w-full" type="submit" name="preset" value="custom">
                        <x-icon name="search" size="sm" /> Apply custom range
                    </button>
                </form>
                <p class="mt-3 text-xs text-muted">
                    Active filter: <span class="font-semibold text-ink">{{ collect($presetDefinitions)->firstWhere('key', $activePreset)['label'] ?? 'Custom' }}</span>
                </p>
            </div>
        </div>

        <div class="relative mt-6 flex flex-wrap gap-2">
            @foreach($reportSections as $key => $report)
                @if($key !== 'dashboard')
                    <a href="#{{ $key }}" class="inline-flex items-center gap-2 rounded-full border border-line bg-white/90 px-4 py-2 text-sm font-medium text-ink transition hover:border-crimson/30 hover:text-crimson">
                        <x-icon name="{{ $icons[$key] ?? 'info' }}" size="sm" /> {{ $report['title'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="card overflow-hidden p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.16em] text-muted">Cash collected</div>
            <div class="stat-num mt-3 text-3xl font-semibold">@money($cashCollected)</div>
            <p class="mt-2 text-xs text-muted">Actual receipts captured from checkout settlements.</p>
        </div>
        <div class="card overflow-hidden p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.16em] text-muted">Gross billed</div>
            <div class="stat-num mt-3 text-3xl font-semibold">@money($grossRevenue)</div>
            <p class="mt-2 text-xs text-muted">Room charges and extras billed to guests.</p>
        </div>
        <div class="card overflow-hidden p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.16em] text-muted">Total expenses</div>
            <div class="stat-num mt-3 text-3xl font-semibold">@money($expenseTotal)</div>
            <p class="mt-2 text-xs text-muted">Operating spend plus payroll expense booked in the period.</p>
        </div>
        <div class="card overflow-hidden p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.16em] text-muted">Net profit</div>
            <div class="stat-num mt-3 text-3xl font-semibold {{ $profit >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">@money($profit)</div>
            <p class="mt-2 text-xs text-muted">Collections less total expense for the selected range.</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.5fr_1fr]">
        <div class="card p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-crimson">Performance frame</p>
                    <h3 class="mt-2 text-xl font-semibold tracking-tight">Executive pulse</h3>
                    <p class="mt-1 text-sm text-muted">A quick read on demand, receivables, occupancy, and payroll pressure.</p>
                </div>
                <a href="{{ route('reports.export', ['report' => 'profit-loss', 'from' => $from, 'to' => $to]) }}" class="btn btn-ghost text-sm">
                    <x-icon name="print" size="sm" /> P/L PDF
                </a>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[1.25rem] border border-line bg-panel-2 p-4">
                    <div class="text-xs text-muted">Bookings created</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">{{ $bookingsCreated }}</div>
                </div>
                <div class="rounded-[1.25rem] border border-line bg-panel-2 p-4">
                    <div class="text-xs text-muted">Checked out</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">{{ $checkedOutCount }}</div>
                </div>
                <div class="rounded-[1.25rem] border border-line bg-panel-2 p-4">
                    <div class="text-xs text-muted">Open balance</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">@money($balanceDue)</div>
                </div>
                <div class="rounded-[1.25rem] border border-line bg-panel-2 p-4">
                    <div class="text-xs text-muted">Payroll due</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">@money($payrollDue)</div>
                </div>
            </div>
        </div>

        <div class="card p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3">
                <x-section-title icon="bed" hint="Live room mix across the property.">Occupancy</x-section-title>
                <div class="rounded-full bg-panel-2 px-3 py-1 text-xs font-semibold text-muted">{{ $occupancyPercent }}%</div>
            </div>
            <div class="mt-5 grid gap-3 grid-cols-2">
                <div class="rounded-[1.2rem] bg-emerald-50 p-4">
                    <div class="text-xs text-muted">Available</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">{{ $available }}</div>
                </div>
                <div class="rounded-[1.2rem] bg-rose-50 p-4">
                    <div class="text-xs text-muted">Occupied</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">{{ $occupied }}</div>
                </div>
                <div class="rounded-[1.2rem] bg-amber-50 p-4">
                    <div class="text-xs text-muted">Reserved</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">{{ $reserved }}</div>
                </div>
                <div class="rounded-[1.2rem] bg-stone-100 p-4">
                    <div class="text-xs text-muted">Maintenance</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">{{ $maintenance }}</div>
                </div>
            </div>
        </div>
    </div>

    <section id="bookings" class="mt-6 card p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <x-section-title icon="calendar" hint="Guest stays, settlements, and receivables.">Booking report</x-section-title>
                <p class="mt-2 text-sm text-muted">Detailed booking activity across the selected reporting window.</p>
            </div>
            <a href="{{ route('reports.export', ['report' => 'bookings', 'from' => $from, 'to' => $to]) }}" class="btn btn-primary text-sm">
                <x-icon name="print" size="sm" /> Download PDF
            </a>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[1.25rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Bookings in view</div>
                <div class="stat-num mt-2 text-2xl font-semibold">{{ $bookingSummary['stays'] }}</div>
            </div>
            <div class="rounded-[1.25rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Checked out</div>
                <div class="stat-num mt-2 text-2xl font-semibold">{{ $bookingSummary['checked_out'] }}</div>
            </div>
            <div class="rounded-[1.25rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Guest nights</div>
                <div class="stat-num mt-2 text-2xl font-semibold">{{ $bookingSummary['guest_nights'] }}</div>
            </div>
            <div class="rounded-[1.25rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Average ticket</div>
                <div class="stat-num mt-2 text-2xl font-semibold">@money($bookingSummary['average_ticket'])</div>
            </div>
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-[1.8fr_1fr]">
            <div class="overflow-x-auto rounded-[1.4rem] border border-line">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Booking</th>
                            <th>Stay</th>
                            <th>Guests</th>
                            <th>Billed</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookingRows as $row)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $row['guest'] }}</div>
                                    <div class="text-xs text-muted">{{ $row['reference'] }} · Room {{ $row['room'] }}</div>
                                </td>
                                <td>
                                    <div>{{ $row['check_in'] }}</div>
                                    <div class="text-xs text-muted">to {{ $row['check_out'] }} · {{ $row['nights'] }} night(s)</div>
                                    <div class="text-xs text-muted">In: {{ $row['checked_in_at'] }}</div>
                                    <div class="text-xs text-muted">Out: {{ $row['checked_out_at'] }}</div>
                                </td>
                                <td>{{ $row['guests'] }}</td>
                                <td class="stat-num">@money($row['billed'])</td>
                                <td class="stat-num">@money($row['paid'])</td>
                                <td class="stat-num">@money($row['due'])</td>
                                <td>
                                    <div>{{ $row['status'] }}</div>
                                    <div class="text-xs text-muted">{{ $row['payment'] }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-sm text-muted">No bookings found in this reporting window.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-6">
                <div class="rounded-[1.4rem] border border-line p-4">
                    <h4 class="text-sm font-semibold">Settlement mix</h4>
                    <div class="mt-4 space-y-3">
                        @forelse($paymentBreakdown as $row)
                            <div class="rounded-2xl bg-panel-2 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <x-status-badge :status="$row['payment_status']">{{ $row['label'] }}</x-status-badge>
                                    <span class="text-xs text-muted">{{ $row['total'] }} booking(s)</span>
                                </div>
                                <div class="stat-num mt-3 text-xl font-semibold">@money($row['balance_due'])</div>
                                <div class="mt-1 text-xs text-muted">Outstanding under this settlement status.</div>
                            </div>
                        @empty
                            <p class="text-sm text-muted">No settlement activity recorded.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[1.4rem] border border-line p-4">
                    <h4 class="text-sm font-semibold">Pending bills</h4>
                    <div class="mt-4 space-y-3">
                        @forelse($pendingBookings as $booking)
                            <div class="rounded-2xl bg-panel-2 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-semibold">{{ $booking->customer?->name ?? 'Guest' }}</div>
                                        <div class="mt-1 text-xs text-muted">Room {{ $booking->room?->number ?? '-' }} · {{ $booking->statusLabel() }}</div>
                                        <div class="mt-1 text-xs text-muted">Room due @money($booking->roomBalance()) · Extras due @money($booking->extrasBalance())</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="stat-num text-lg font-semibold">@money($booking->balance_due)</div>
                                        <x-status-badge :status="$booking->paymentState()">{{ $booking->paymentStatusLabel() }}</x-status-badge>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-muted">No pending balances right now.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section id="expenses" class="card p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <x-section-title icon="wallet" hint="Operating costs excluding payroll.">Expense report</x-section-title>
                    <p class="mt-2 text-sm text-muted">Supplies, utilities, maintenance, food, and other operating spend.</p>
                </div>
                <a href="{{ route('reports.export', ['report' => 'expenses', 'from' => $from, 'to' => $to]) }}" class="btn btn-ghost text-sm">
                    <x-icon name="print" size="sm" /> Download PDF
                </a>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[1.2rem] bg-panel-2 p-4">
                    <div class="text-xs text-muted">Transactions</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">{{ $expenseSummary['transactions'] }}</div>
                </div>
                <div class="rounded-[1.2rem] bg-panel-2 p-4">
                    <div class="text-xs text-muted">Operating total</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">@money($expenseSummary['operating_total'])</div>
                </div>
                <div class="rounded-[1.2rem] bg-panel-2 p-4">
                    <div class="text-xs text-muted">Average ticket</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">@money($expenseSummary['average_ticket'])</div>
                </div>
                <div class="rounded-[1.2rem] bg-panel-2 p-4">
                    <div class="text-xs text-muted">Top category</div>
                    <div class="mt-2 text-lg font-semibold capitalize">{{ $expenseSummary['top_category'] }}</div>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($expenseByCategory as $row)
                    <div>
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <span class="font-medium capitalize">{{ $row['category'] }}</span>
                            <span class="stat-num">@money($row['amount'])</span>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-stone-200">
                            <span class="block h-full bg-crimson" style="width: {{ max(10, min(100, ($operatingExpenseTotal > 0 ? ($row['amount'] / $operatingExpenseTotal) * 100 : 0))) }}%"></span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-muted">No operating expenses recorded for this period.</p>
                @endforelse
            </div>

            <div class="mt-6 overflow-x-auto rounded-[1.4rem] border border-line">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Expense</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Recorded by</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenseRows as $row)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $row['title'] }}</div>
                                    <div class="text-xs text-muted">{{ $row['reference'] }} · {{ $row['notes'] }}</div>
                                </td>
                                <td>{{ $row['date'] }}</td>
                                <td>{{ $row['category'] }}</td>
                                <td>{{ $row['recorded_by'] }}</td>
                                <td class="stat-num">@money($row['amount'])</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-sm text-muted">No operating expenses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section id="salaries" class="card p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <x-section-title icon="briefcase" hint="Employee pay coverage and salary activity.">Salary report</x-section-title>
                    <p class="mt-2 text-sm text-muted">Payroll visibility by employee and by payout cycle.</p>
                </div>
                <a href="{{ route('reports.export', ['report' => 'salaries', 'from' => $from, 'to' => $to]) }}" class="btn btn-ghost text-sm">
                    <x-icon name="print" size="sm" /> Download PDF
                </a>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[1.2rem] bg-panel-2 p-4">
                    <div class="text-xs text-muted">Active employees</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">{{ $salarySummary['active'] }}</div>
                </div>
                <div class="rounded-[1.2rem] bg-panel-2 p-4">
                    <div class="text-xs text-muted">Salary paid</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">@money($salarySummary['paid'])</div>
                </div>
                <div class="rounded-[1.2rem] bg-panel-2 p-4">
                    <div class="text-xs text-muted">Salary due</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">@money($salarySummary['due'])</div>
                </div>
                <div class="rounded-[1.2rem] bg-panel-2 p-4">
                    <div class="text-xs text-muted">Payment cycles</div>
                    <div class="stat-num mt-2 text-2xl font-semibold">{{ $salarySummary['cycles'] }}</div>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto rounded-[1.4rem] border border-line">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Status</th>
                            <th>Monthly salary</th>
                            <th>Paid in range</th>
                            <th>Cycles</th>
                            <th>Last paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaryRows as $row)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $row['employee'] }}</div>
                                    <div class="text-xs text-muted">{{ $row['position'] }}</div>
                                </td>
                                <td>{{ $row['status'] }}</td>
                                <td class="stat-num">@money($row['monthly_salary'])</td>
                                <td class="stat-num">@money($row['paid_total'])</td>
                                <td>{{ $row['cycles'] }}</td>
                                <td>{{ $row['last_paid_on'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-sm text-muted">No salary ledger rows found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 overflow-x-auto rounded-[1.4rem] border border-line">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Month</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaryPaymentRows as $row)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $row['reference'] }}</div>
                                    <div class="text-xs text-muted">{{ $row['notes'] }}</div>
                                </td>
                                <td>{{ $row['paid_on'] }}</td>
                                <td>{{ $row['employee'] }}</td>
                                <td>{{ $row['for_month'] }}</td>
                                <td class="stat-num">@money($row['amount'])</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-sm text-muted">No salary payments found in this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <section id="profit-loss" class="mt-6 card p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <x-section-title icon="trend" hint="Revenue, receivables, collections, and total spend.">P/L report</x-section-title>
                <p class="mt-2 text-sm text-muted">This view separates room and extra revenue from operating and payroll costs.</p>
            </div>
            <a href="{{ route('reports.export', ['report' => 'profit-loss', 'from' => $from, 'to' => $to]) }}" class="btn btn-primary text-sm">
                <x-icon name="print" size="sm" /> Download PDF
            </a>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-6">
            <div class="rounded-[1.2rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Room revenue</div>
                <div class="stat-num mt-2 text-2xl font-semibold">@money($roomRevenue)</div>
            </div>
            <div class="rounded-[1.2rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Extras revenue</div>
                <div class="stat-num mt-2 text-2xl font-semibold">@money($extrasRevenue)</div>
            </div>
            <div class="rounded-[1.2rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Operating expense</div>
                <div class="stat-num mt-2 text-2xl font-semibold">@money($operatingExpenseTotal)</div>
            </div>
            <div class="rounded-[1.2rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Payroll expense</div>
                <div class="stat-num mt-2 text-2xl font-semibold">@money($salaryExpenseTotal)</div>
            </div>
            <div class="rounded-[1.2rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Receivables</div>
                <div class="stat-num mt-2 text-2xl font-semibold">@money($balanceDue)</div>
            </div>
            <div class="rounded-[1.2rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Net profit</div>
                <div class="stat-num mt-2 text-2xl font-semibold {{ $profit >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">@money($profit)</div>
            </div>
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-[1.6fr_1fr]">
            <div class="overflow-x-auto rounded-[1.4rem] border border-line">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Bookings</th>
                            <th>Revenue</th>
                            <th>Collections</th>
                            <th>Expenses</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyTrend as $row)
                            <tr>
                                <td>{{ $row['label'] }}</td>
                                <td>{{ $row['bookings'] }}</td>
                                <td class="stat-num">@money($row['revenue'])</td>
                                <td class="stat-num">@money($row['collections'])</td>
                                <td class="stat-num">@money($row['expenses'])</td>
                                <td class="stat-num {{ $row['profit'] >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">@money($row['profit'])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="space-y-4">
                <div class="rounded-[1.4rem] border border-line p-4">
                    <h4 class="text-sm font-semibold">Top rooms by revenue</h4>
                    <div class="mt-4 space-y-3">
                        @forelse($roomPerformance as $row)
                            <div class="rounded-2xl bg-panel-2 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <div class="font-semibold">Room {{ $row['room_number'] }}</div>
                                        <div class="text-xs text-muted">{{ $row['stays'] }} stay(s)</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="stat-num font-semibold">@money($row['revenue'])</div>
                                        <div class="text-xs text-muted">Due @money($row['balance_due'])</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-muted">No completed stays in this range.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="audit" class="mt-6 card p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <x-section-title icon="receipt" hint="Chronological visibility into key business events.">Audit report</x-section-title>
                <p class="mt-2 text-sm text-muted">Bookings, settlements, expenses, and salary movements arranged as a readable trail.</p>
            </div>
            <a href="{{ route('reports.export', ['report' => 'audit', 'from' => $from, 'to' => $to]) }}" class="btn btn-primary text-sm">
                <x-icon name="print" size="sm" /> Download PDF
            </a>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[1.2rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Events</div>
                <div class="stat-num mt-2 text-2xl font-semibold">{{ $auditSummary['events'] }}</div>
            </div>
            <div class="rounded-[1.2rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Collections</div>
                <div class="stat-num mt-2 text-2xl font-semibold">@money($auditSummary['collections'])</div>
            </div>
            <div class="rounded-[1.2rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Disbursements</div>
                <div class="stat-num mt-2 text-2xl font-semibold">@money($auditSummary['disbursements'])</div>
            </div>
            <div class="rounded-[1.2rem] bg-panel-2 p-4">
                <div class="text-xs text-muted">Open balance</div>
                <div class="stat-num mt-2 text-2xl font-semibold">@money($auditSummary['open_balance'])</div>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto rounded-[1.4rem] border border-line">
            <table class="table">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Event</th>
                        <th>Reference</th>
                        <th>Actor</th>
                        <th>Detail</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditTrail->take(40) as $entry)
                        <tr>
                            <td>{{ $entry['occurred_at']?->format('d M Y h:i A') ?? '-' }}</td>
                            <td>
                                <div class="font-semibold">{{ $entry['label'] }}</div>
                                <div class="text-xs text-muted">{{ $entry['subject'] }}</div>
                            </td>
                            <td>{{ $entry['reference'] }}</td>
                            <td>{{ $entry['actor'] }}</td>
                            <td>{{ $entry['detail'] }}</td>
                            <td class="stat-num {{ $entry['direction'] === 'positive' ? 'text-emerald-700' : ($entry['direction'] === 'negative' ? 'text-rose-700' : 'text-ink') }}">
                                {{ $entry['direction'] === 'negative' ? '-' : ($entry['direction'] === 'positive' ? '+' : '') }}@money($entry['amount'])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-sm text-muted">No audit events found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
