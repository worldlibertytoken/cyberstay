<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalaryPayment;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function __construct(private PayrollService $payroll) {}

    public function index(Request $request)
    {
        $month = $this->resolvedMonth($request->string('month')->toString());
        $status = $request->get('status', 'active');
        $search = trim((string) $request->get('q', ''));

        $employees = Employee::query()
            ->with(['payments' => fn ($query) => $query->latest('paid_on')->latest('id')])
            ->withSum('payments as paid_total', 'amount')
            ->withSum(['payments as paid_this_month' => fn ($query) => $query->where('for_month', $month)], 'amount')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%'.$search.'%')
                        ->orWhere('position', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                });
            })
            ->when($status === 'active', fn ($query) => $query->where('status', 'active'))
            ->when($status === 'inactive', fn ($query) => $query->where('status', 'inactive'))
            ->when($status === 'due', fn ($query) => $query->where('status', 'active'))
            ->orderBy('name')
            ->get()
            ->map(function (Employee $employee) use ($month) {
                $paidThisMonth = round((float) ($employee->paid_this_month ?? 0), 2);
                $dueThisMonth = $this->payroll->remainingForMonth($employee, $month, $paidThisMonth);
                $employee->paid_this_month = $paidThisMonth;
                $employee->due_this_month = $dueThisMonth;
                $employee->pay_state = $dueThisMonth <= 0
                    ? 'paid'
                    : ($paidThisMonth > 0 ? 'partial' : 'unpaid');

                return $employee;
            });

        if ($status === 'due') {
            $employees = $employees
                ->filter(fn (Employee $employee) => $employee->isActive() && $employee->due_this_month > 0)
                ->values();
        }

        $activeStaff = Employee::query()->where('status', 'active')->get(['id', 'salary']);
        $monthlyPayroll = round((float) $activeStaff->sum('salary'), 2);
        $paidThisMonth = round((float) SalaryPayment::query()->where('for_month', $month)->sum('amount'), 2);
        $dueThisMonth = max(0, round($monthlyPayroll - round((float) SalaryPayment::query()
            ->where('for_month', $month)
            ->whereIn('employee_id', $activeStaff->pluck('id'))
            ->sum('amount'), 2), 2));

        $months = collect(range(0, 11))->mapWithKeys(function (int $i) {
            $date = now()->startOfMonth()->subMonths($i);

            return [$date->format('Y-m') => $date->format('M Y')];
        });

        if (! $months->has($month)) {
            $months = collect([$month => $this->payroll->monthLabel($month)])->merge($months);
        }

        return view('employees.index', [
            'employees' => $employees,
            'month' => $month,
            'monthLabel' => $this->payroll->monthLabel($month),
            'status' => $status,
            'search' => $search,
            'months' => $months,
            'activeCount' => $activeStaff->count(),
            'inactiveCount' => Employee::query()->where('status', 'inactive')->count(),
            'monthlyPayroll' => $monthlyPayroll,
            'paidThisMonth' => $paidThisMonth,
            'dueThisMonth' => $dueThisMonth,
            'paidPercent' => $monthlyPayroll > 0 ? (int) round(min(100, ($paidThisMonth / $monthlyPayroll) * 100)) : 0,
        ]);
    }

    public function store(Request $request)
    {
        Employee::create($this->validatedEmployee($request));

        return redirect()->route('employees.index')->with('success', 'Employee added.');
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->update($this->validatedEmployee($request));

        return back()->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->payments()->exists()) {
            return back()->with('error', 'This person has salary history. Mark them inactive instead of deleting.');
        }

        $employee->delete();

        return back()->with('success', 'Employee removed.');
    }

    public function pay(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paid_on' => ['required', 'date', 'before_or_equal:today'],
            'for_month' => ['required', 'date_format:Y-m'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $this->payroll->pay($employee, $data, $request->user()->id);

        return back()->with('success', 'Salary recorded and added to expenses.');
    }

    public function voidPayment(Employee $employee, SalaryPayment $payment)
    {
        abort_unless($payment->employee_id === $employee->id, 404);

        $this->payroll->void($payment);

        return back()->with('success', 'Salary payment removed from payroll and expenses.');
    }

    private function validatedEmployee(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'position' => ['nullable', 'string', 'max:80'],
            'salary' => ['required', 'numeric', 'min:0'],
            'hire_date' => ['nullable', 'date', 'before_or_equal:today'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function resolvedMonth(string $month): string
    {
        if (preg_match('/^\d{4}-\d{2}$/', $month) !== 1) {
            return $this->payroll->monthKey();
        }

        try {
            Carbon::createFromFormat('!Y-m', $month);

            return $month;
        } catch (\Throwable) {
            return $this->payroll->monthKey();
        }
    }
}
