<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['tenant_id', 'name', 'phone', 'position', 'salary', 'hire_date', 'status', 'notes'])]
class Employee extends Model
{
    use BelongsToTenant;

    public const POSITIONS = [
        'Receptionist',
        'Housekeeping',
        'Chef',
        'Waiter',
        'Security',
        'Manager',
        'Accountant',
        'Driver',
        'Gardener',
        'Maintenance',
    ];

    protected function casts(): array
    {
        return [
            'salary' => 'decimal:2',
            'hire_date' => 'date',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalaryPayment::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name)) ?: [];
        $first = mb_substr($parts[0] ?? 'E', 0, 1);
        $last = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';

        return mb_strtoupper($first.$last);
    }
}
