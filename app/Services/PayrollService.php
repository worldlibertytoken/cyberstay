<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Expense;
use App\Models\SalaryPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PayrollService
{
    public function monthKey(?Carbon $date = null): string
    {
        return ($date ?? now())->format('Y-m');
    }

    public function monthLabel(string $month): string
    {
        return Carbon::createFromFormat('!Y-m', $month)->format('M Y');
    }

    public function paidForMonth(Employee $employee, string $month): float
    {
        return round((float) $employee->payments()->where('for_month', $month)->sum('amount'), 2);
    }

    public function remainingForMonth(Employee $employee, string $month, ?float $alreadyPaid = null): float
    {
        $paid = $alreadyPaid ?? $this->paidForMonth($employee, $month);

        return max(0, round((float) $employee->salary - $paid, 2));
    }

    public function pay(Employee $employee, array $data, ?int $userId = null): SalaryPayment
    {
        $month = $data['for_month'];
        $amount = round((float) $data['amount'], 2);
        $paidOn = Carbon::parse($data['paid_on'])->startOfDay();

        if ($employee->hire_date && $month < $employee->hire_date->format('Y-m')) {
            throw ValidationException::withMessages([
                'for_month' => 'Cannot pay for a month before the hire date.',
            ]);
        }

        if ($employee->hire_date && $paidOn->lt($employee->hire_date->startOfDay())) {
            throw ValidationException::withMessages([
                'paid_on' => 'Payment date cannot be before the hire date.',
            ]);
        }

        return DB::transaction(function () use ($employee, $data, $userId, $month, $amount, $paidOn) {
            $expense = Expense::create([
                'created_by' => $userId,
                'title' => 'Salary · '.$employee->name.' · '.$this->monthLabel($month),
                'category' => 'salary',
                'amount' => $amount,
                'expense_date' => $paidOn->toDateString(),
                'notes' => $data['notes'] ?? null,
            ]);

            return SalaryPayment::create([
                'employee_id' => $employee->id,
                'expense_id' => $expense->id,
                'amount' => $amount,
                'paid_on' => $paidOn->toDateString(),
                'for_month' => $month,
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    public function void(SalaryPayment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->expense?->delete();
            $payment->delete();
        });
    }
}
