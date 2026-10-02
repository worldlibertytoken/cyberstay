<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Room;
use App\Models\SalaryPayment;
use App\Support\TenantContext;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$from, $to, $activePreset] = $this->resolveDateRange($request);

        return view('reports.index', $this->buildReportPayload($from, $to, $activePreset));
    }

    public function bookingsReport(Request $request): View|Response
    {
        $from = ($request->date('from') ?? now()->startOfMonth())->startOfDay();
        $to = ($request->date('to') ?? now()->endOfMonth())->endOfDay();

        if ($from->greaterThan($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        $bookings = Booking::query()
            ->with(['room', 'customer', 'items', 'creator', 'tenant'])
            ->where('tenant_id', TenantContext::id())
            ->where(function ($query) use ($from, $to) {
                $query
                    ->whereBetween('check_in', [$from->toDateString(), $to->toDateString()])
                    ->orWhereBetween('check_out', [$from->toDateString(), $to->toDateString()])
                    ->orWhereBetween('created_at', [$from, $to]);
            })
            ->orderByDesc('check_in')
            ->get();

        if ($request->get('export') === 'pdf') {
            $fileName = sprintf('bookings-%s-to-%s.pdf', $from->format('Ymd'), $to->format('Ymd'));

            return Pdf::loadView('reports.bookings-pdf', [
                'bookings' => $bookings,
                'tenant' => TenantContext::tenant(),
                'from' => $from,
                'to' => $to,
                'generatedAt' => now(),
            ])
                ->setPaper('a4', 'landscape')
                ->download($fileName);
        }

        return view('reports.bookings-report', [
            'bookings' => $bookings,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function export(Request $request, string $report): Response
    {
        [$from, $to, $activePreset] = $this->resolveDateRange($request);
        $reports = $this->reportDefinitions();

        abort_unless(array_key_exists($report, $reports), 404);

        $payload = $this->buildReportPayload($from, $to, $activePreset);
        $paper = in_array($report, ['dashboard', 'bookings', 'audit'], true) ? 'landscape' : 'portrait';
        $fileName = sprintf('%s-%s-%s.pdf', $report, $from->format('Ymd'), $to->format('Ymd'));

        return Pdf::loadView('reports.export', array_merge($payload, [
            'generatedAt' => now(),
            'reportKey' => $report,
            'reportMeta' => $reports[$report],
        ]))
            ->setPaper('a4', $paper)
            ->download($fileName);
    }

    private function resolveDateRange(Request $request): array
    {
        $presets = collect($this->presetDefinitions())->pluck('key')->all();
        $requestedPreset = $request->string('preset')->toString();
        $activePreset = in_array($requestedPreset, $presets, true) ? $requestedPreset : null;

        if ($activePreset && $activePreset !== 'custom') {
            [$from, $to] = $this->presetBounds($activePreset);
        } else {
            $from = ($request->date('from') ?? now()->startOfMonth())->startOfDay();
            $to = ($request->date('to') ?? now()->endOfMonth())->endOfDay();
            $activePreset = $activePreset ?? ($request->filled('from') || $request->filled('to') ? 'custom' : 'month');
        }

        if ($from->greaterThan($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [$from, $to, $activePreset];
    }

    private function buildReportPayload(Carbon $from, Carbon $to, string $activePreset): array
    {
        $tenant = TenantContext::tenant();
        $checkedOutBookings = Booking::query()
            ->with(['room', 'customer', 'creator'])
            ->whereBetween('checked_out_at', [$from, $to])
            ->orderByDesc('checked_out_at')
            ->get();

        $bookingsInRange = Booking::query()
            ->with(['room', 'customer', 'creator'])
            ->where(function ($query) use ($from, $to) {
                $query
                    ->whereBetween('created_at', [$from, $to])
                    ->orWhereBetween('checked_out_at', [$from, $to])
                    ->orWhereBetween('check_in', [$from->toDateString(), $to->toDateString()])
                    ->orWhereBetween('check_out', [$from->toDateString(), $to->toDateString()]);
            })
            ->orderByDesc('created_at')
            ->get();

        $allExpenses = Expense::query()
            ->with('creator')
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->orderByDesc('expense_date')
            ->get();

        $salaryPayments = SalaryPayment::query()
            ->with(['employee', 'expense'])
            ->whereBetween('paid_on', [$from->toDateString(), $to->toDateString()])
            ->orderByDesc('paid_on')
            ->get();

        $operatingExpenses = $allExpenses
            ->reject(fn (Expense $expense) => $expense->category === 'salary')
            ->values();

        $grossRevenue = round((float) $checkedOutBookings->sum('grand_total'), 2);
        $roomRevenue = round((float) $checkedOutBookings->sum('room_amount'), 2);
        $extrasRevenue = round((float) $checkedOutBookings->sum('extras_amount'), 2);
        $cashCollected = round($checkedOutBookings->sum(fn (Booking $booking) => $booking->amountPaid()), 2);
        $balanceDue = round((float) Booking::query()->where('balance_due', '>', 0)->sum('balance_due'), 2);
        $expenseTotal = round((float) $allExpenses->sum('amount'), 2);
        $operatingExpenseTotal = round((float) $operatingExpenses->sum('amount'), 2);
        $salaryExpenseTotal = round((float) $allExpenses->where('category', 'salary')->sum('amount'), 2);
        $salaryTotal = round((float) $salaryPayments->sum('amount'), 2);
        $profit = round($cashCollected - $expenseTotal, 2);

        $currentRooms = Room::query()->orderBy('number')->get();
        $occupied = $currentRooms->filter(fn (Room $room) => $room->occupancyStatus() === 'occupied')->count();
        $reserved = $currentRooms->filter(fn (Room $room) => $room->occupancyStatus() === 'reserved')->count();
        $available = $currentRooms->filter(fn (Room $room) => $room->occupancyStatus() === 'available')->count();
        $maintenance = $currentRooms->filter(fn (Room $room) => $room->occupancyStatus() === 'maintenance')->count();
        $roomCount = $currentRooms->count();
        $occupancyPercent = $roomCount ? (int) round(($occupied / $roomCount) * 100) : 0;

        $pendingBookings = Booking::query()
            ->with(['room', 'customer'])
            ->where('balance_due', '>', 0)
            ->whereIn('status', ['reserved', 'checked_in', 'checked_out'])
            ->orderByDesc('balance_due')
            ->limit(12)
            ->get();

        $paymentBreakdown = $checkedOutBookings
            ->groupBy(fn (Booking $booking) => $booking->paymentState())
            ->map(function (Collection $rows, string $status): array {
                return [
                    'payment_status' => $status,
                    'label' => match ($status) {
                        'paid' => 'Paid',
                        'partial' => 'Partial',
                        default => 'Pending',
                    },
                    'total' => $rows->count(),
                    'balance_due' => round((float) $rows->sum('balance_due'), 2),
                ];
            })
            ->sortByDesc('total')
            ->values();

        $roomPerformance = $checkedOutBookings
            ->groupBy('room_id')
            ->map(function (Collection $rows): array {
                /** @var Booking $first */
                $first = $rows->first();

                return [
                    'room_number' => $first?->room?->number ?? 'Unassigned',
                    'stays' => $rows->count(),
                    'revenue' => round((float) $rows->sum('grand_total'), 2),
                    'balance_due' => round((float) $rows->sum('balance_due'), 2),
                ];
            })
            ->sortByDesc('revenue')
            ->take(8)
            ->values();

        $expenseByCategory = $operatingExpenses
            ->groupBy('category')
            ->map(function (Collection $rows, string $category): array {
                return [
                    'category' => $category,
                    'total' => $rows->count(),
                    'amount' => round((float) $rows->sum('amount'), 2),
                ];
            })
            ->sortByDesc('amount')
            ->values();

        $monthlyTrend = collect(range(0, 5))->map(function (int $offset) use ($from): array {
            $start = $from->copy()->startOfMonth()->subMonths(5 - $offset)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            $revenue = (float) Booking::query()->whereBetween('checked_out_at', [$start, $end])->sum('grand_total');
            $expense = (float) Expense::query()->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])->sum('amount');
            $collections = (float) Booking::query()->whereBetween('checked_out_at', [$start, $end])->sum('room_paid_amount')
                + (float) Booking::query()->whereBetween('checked_out_at', [$start, $end])->sum('extras_paid_amount');

            return [
                'label' => $start->format('M Y'),
                'bookings' => Booking::query()->whereBetween('checked_out_at', [$start, $end])->count(),
                'revenue' => round($revenue, 2),
                'collections' => round($collections, 2),
                'expenses' => round($expense, 2),
                'profit' => round($collections - $expense, 2),
            ];
        });

        $employees = Employee::query()
            ->with(['payments' => function ($query) use ($from, $to) {
                $query
                    ->whereBetween('paid_on', [$from->toDateString(), $to->toDateString()])
                    ->orderByDesc('paid_on');
            }])
            ->orderBy('name')
            ->get();

        $activeEmployees = $employees->where('status', 'active')->count();
        $inactiveEmployees = $employees->where('status', 'inactive')->count();
        $payrollPaid = $salaryTotal;
        $payrollDue = max(0, round((float) $employees->where('status', 'active')->sum('salary') - $payrollPaid, 2));

        $bookingRows = $bookingsInRange->map(function (Booking $booking): array {
            return [
                'reference' => 'BK-'.str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT),
                'guest' => $booking->customer?->name ?? 'Walk-in guest',
                'room' => $booking->room?->number ?? '-',
                'status' => $booking->statusLabel(),
                'check_in' => $booking->check_in?->format('d M Y') ?? '-',
                'check_out' => $booking->check_out?->format('d M Y') ?? '-',
                'checked_in_at' => $booking->checked_in_at?->format('d M Y, h:i A') ?? 'Pending',
                'checked_out_at' => $booking->checked_out_at?->format('d M Y, h:i A') ?? 'Pending',
                'nights' => (int) $booking->nights,
                'guests' => $booking->guestCount(),
                'billed' => round((float) $booking->grand_total, 2),
                'paid' => $booking->amountPaid(),
                'due' => round((float) $booking->balance_due, 2),
                'payment' => $booking->paymentStatusLabel(),
                'created_by' => $booking->creator?->name ?? 'System',
            ];
        })->values();

        $expenseRows = $operatingExpenses->map(function (Expense $expense): array {
            return [
                'reference' => 'EXP-'.str_pad((string) $expense->id, 5, '0', STR_PAD_LEFT),
                'date' => $expense->expense_date?->format('d M Y') ?? '-',
                'title' => $expense->title,
                'category' => ucfirst($expense->category),
                'amount' => round((float) $expense->amount, 2),
                'notes' => $expense->notes ?: '-',
                'recorded_by' => $expense->creator?->name ?? 'Manager',
            ];
        })->values();

        $salaryRows = $employees
            ->map(function (Employee $employee): array {
                $periodPayments = $employee->payments;
                $paidTotal = round((float) $periodPayments->sum('amount'), 2);

                return [
                    'employee' => $employee->name,
                    'position' => $employee->position,
                    'status' => ucfirst($employee->status),
                    'monthly_salary' => round((float) $employee->salary, 2),
                    'paid_total' => $paidTotal,
                    'cycles' => $periodPayments->count(),
                    'last_paid_on' => $periodPayments->first()?->paid_on?->format('d M Y') ?? '-',
                ];
            })
            ->filter(fn (array $row) => $row['status'] === 'Active' || $row['paid_total'] > 0)
            ->values();

        $salaryPaymentRows = $salaryPayments->map(function (SalaryPayment $payment): array {
            return [
                'reference' => 'PAY-'.str_pad((string) $payment->id, 5, '0', STR_PAD_LEFT),
                'paid_on' => $payment->paid_on?->format('d M Y') ?? '-',
                'employee' => $payment->employee?->name ?? 'Former employee',
                'for_month' => $payment->for_month ?: ($payment->paid_on?->format('F Y') ?? '-'),
                'amount' => round((float) $payment->amount, 2),
                'notes' => $payment->notes ?: '-',
            ];
        })->values();

        $auditExpenseIds = $salaryPayments->pluck('expense_id')->filter()->all();
        $auditTrail = collect()
            ->merge($bookingsInRange->map(function (Booking $booking): array {
                return [
                    'occurred_at' => $booking->created_at,
                    'label' => 'Booking created',
                    'reference' => 'BK-'.str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT),
                    'subject' => $booking->customer?->name ?? 'Walk-in guest',
                    'actor' => $booking->creator?->name ?? 'System',
                    'detail' => 'Room '.($booking->room?->number ?? '-').' for '.$booking->nights.' night(s)',
                    'amount' => round((float) $booking->grand_total, 2),
                    'direction' => 'neutral',
                ];
            }))
            ->merge($checkedOutBookings->map(function (Booking $booking): array {
                return [
                    'occurred_at' => $booking->checked_out_at,
                    'label' => 'Checkout settled',
                    'reference' => 'BK-'.str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT),
                    'subject' => $booking->customer?->name ?? 'Guest',
                    'actor' => 'Front desk',
                    'detail' => $booking->paymentStatusLabel().' checkout with room '.($booking->room?->number ?? '-'),
                    'amount' => $booking->amountPaid(),
                    'direction' => 'positive',
                ];
            }))
            ->merge($operatingExpenses
                ->reject(fn (Expense $expense) => in_array($expense->id, $auditExpenseIds, true))
                ->map(function (Expense $expense): array {
                    return [
                        'occurred_at' => $expense->created_at ?? $expense->expense_date?->copy()->startOfDay(),
                        'label' => 'Expense logged',
                        'reference' => 'EXP-'.str_pad((string) $expense->id, 5, '0', STR_PAD_LEFT),
                        'subject' => $expense->title,
                        'actor' => $expense->creator?->name ?? 'Manager',
                        'detail' => ucfirst($expense->category).' expense',
                        'amount' => round((float) $expense->amount, 2),
                        'direction' => 'negative',
                    ];
                }))
            ->merge($salaryPayments->map(function (SalaryPayment $payment): array {
                return [
                    'occurred_at' => $payment->created_at ?? $payment->paid_on?->copy()->startOfDay(),
                    'label' => 'Salary paid',
                    'reference' => 'PAY-'.str_pad((string) $payment->id, 5, '0', STR_PAD_LEFT),
                    'subject' => $payment->employee?->name ?? 'Former employee',
                    'actor' => 'Payroll desk',
                    'detail' => $payment->for_month ?: ($payment->paid_on?->format('F Y') ?? 'Salary cycle'),
                    'amount' => round((float) $payment->amount, 2),
                    'direction' => 'negative',
                ];
            }))
            ->sortByDesc(fn (array $entry) => $entry['occurred_at']?->timestamp ?? 0)
            ->values();

        $bookingSummary = [
            'stays' => $bookingsInRange->count(),
            'checked_out' => $checkedOutBookings->count(),
            'guest_nights' => $bookingsInRange->sum(fn (Booking $booking) => max(1, (int) $booking->nights) * max(1, $booking->guestCount())),
            'average_ticket' => round((float) ($checkedOutBookings->avg('grand_total') ?? 0), 2),
        ];

        $topExpense = $expenseByCategory->first();
        $expenseSummary = [
            'transactions' => $expenseRows->count(),
            'operating_total' => $operatingExpenseTotal,
            'average_ticket' => $expenseRows->count() ? round($operatingExpenseTotal / $expenseRows->count(), 2) : 0.0,
            'top_category' => $topExpense['category'] ?? '-',
        ];

        $salarySummary = [
            'active' => $activeEmployees,
            'inactive' => $inactiveEmployees,
            'paid' => $payrollPaid,
            'due' => $payrollDue,
            'cycles' => $salaryPaymentRows->count(),
        ];

        $auditSummary = [
            'events' => $auditTrail->count(),
            'collections' => $cashCollected,
            'disbursements' => round($operatingExpenseTotal + $salaryTotal, 2),
            'open_balance' => $balanceDue,
        ];

        return [
            'tenant' => $tenant,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'activePreset' => $activePreset,
            'presetDefinitions' => $this->presetDefinitions(),
            'rangeLabel' => $from->format('d M Y').' to '.$to->format('d M Y'),
            'reportSections' => $this->reportDefinitions(),
            'grossRevenue' => $grossRevenue,
            'roomRevenue' => $roomRevenue,
            'extrasRevenue' => $extrasRevenue,
            'cashCollected' => $cashCollected,
            'balanceDue' => $balanceDue,
            'expenseTotal' => $expenseTotal,
            'operatingExpenseTotal' => $operatingExpenseTotal,
            'salaryExpenseTotal' => $salaryExpenseTotal,
            'salaryTotal' => $salaryTotal,
            'profit' => $profit,
            'bookingsCreated' => Booking::query()->whereBetween('created_at', [$from, $to])->count(),
            'checkedOutCount' => $checkedOutBookings->count(),
            'paymentBreakdown' => $paymentBreakdown,
            'pendingBookings' => $pendingBookings,
            'roomPerformance' => $roomPerformance,
            'expenseByCategory' => $expenseByCategory,
            'monthlyTrend' => $monthlyTrend,
            'occupied' => $occupied,
            'reserved' => $reserved,
            'available' => $available,
            'maintenance' => $maintenance,
            'occupancyPercent' => $occupancyPercent,
            'activeEmployees' => $activeEmployees,
            'inactiveEmployees' => $inactiveEmployees,
            'payrollPaid' => $payrollPaid,
            'payrollDue' => $payrollDue,
            'bookingRows' => $bookingRows,
            'bookingSummary' => $bookingSummary,
            'expenseRows' => $expenseRows,
            'expenseSummary' => $expenseSummary,
            'salaryRows' => $salaryRows,
            'salaryPaymentRows' => $salaryPaymentRows,
            'salarySummary' => $salarySummary,
            'auditTrail' => $auditTrail,
            'auditSummary' => $auditSummary,
        ];
    }

    private function presetDefinitions(): array
    {
        return [
            ['key' => 'today', 'label' => 'Today'],
            ['key' => 'week', 'label' => 'This Week'],
            ['key' => 'month', 'label' => 'This Month'],
            ['key' => 'last-month', 'label' => 'Last Month'],
            ['key' => 'quarter', 'label' => 'This Quarter'],
            ['key' => 'custom', 'label' => 'Custom'],
        ];
    }

    private function presetBounds(string $preset): array
    {
        $now = now();

        return match ($preset) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'week' => [$now->copy()->startOfWeek()->startOfDay(), $now->copy()->endOfWeek()->endOfDay()],
            'last-month' => [
                $now->copy()->subMonthNoOverflow()->startOfMonth()->startOfDay(),
                $now->copy()->subMonthNoOverflow()->endOfMonth()->endOfDay(),
            ],
            'quarter' => [$now->copy()->startOfQuarter()->startOfDay(), $now->copy()->endOfQuarter()->endOfDay()],
            default => [$now->copy()->startOfMonth()->startOfDay(), $now->copy()->endOfMonth()->endOfDay()],
        };
    }

    private function reportDefinitions(): array
    {
        return [
            'dashboard' => [
                'title' => 'Executive Dashboard',
                'subtitle' => 'High-level performance, settlement, payroll, and room intelligence.',
            ],
            'bookings' => [
                'title' => 'Booking Report',
                'subtitle' => 'Guest stays, billed values, settlements, and outstanding balances.',
            ],
            'expenses' => [
                'title' => 'Expense Report',
                'subtitle' => 'Operating costs outside payroll, grouped by category and transaction.',
            ],
            'salaries' => [
                'title' => 'Salary Report',
                'subtitle' => 'Staff pay cycles, payroll totals, and employee compensation coverage.',
            ],
            'profit-loss' => [
                'title' => 'P/L Report',
                'subtitle' => 'Revenue, collections, receivables, and total spend across the period.',
            ],
            'audit' => [
                'title' => 'Audit Report',
                'subtitle' => 'Chronological trail of bookings, checkout settlements, expenses, and payroll.',
            ],
        ];
    }
}
