<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

#[Fillable([
    'tenant_id',
    'room_id',
    'customer_id',
    'created_by',
    'check_in',
    'check_out',
    'nights',
    'price_per_night',
    'room_amount',
    'extras_amount',
    'grand_total',
    'status',
    'notes',
    'checkout_note',
    'room_paid_amount',
    'extras_paid_amount',
    'balance_due',
    'payment_status',
    'checked_in_at',
    'checked_out_at',
    'vehicle_number',
    'males',
    'females',
    'children',
    'id_card_front',
    'id_card_back',
])]
class Booking extends Model
{
    use BelongsToTenant;

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'price_per_night' => 'decimal:2',
            'room_amount' => 'decimal:2',
            'extras_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'room_paid_amount' => 'decimal:2',
            'extras_paid_amount' => 'decimal:2',
            'balance_due' => 'decimal:2',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
            'males' => 'integer',
            'females' => 'integer',
            'children' => 'integer',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function companions(): HasMany
    {
        return $this->hasMany(BookingCompanion::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BookingPayment::class)->latest('paid_at')->latest('id');
    }

    public function roomHistories(): HasMany
    {
        return $this->hasMany(BookingRoomHistory::class)->orderBy('started_at');
    }

    public function guestCount(): int
    {
        return (int) $this->males + (int) $this->females + (int) $this->children;
    }

    public function frontUrl(): ?string
    {
        return $this->id_card_front ? asset('storage/'.ltrim($this->id_card_front, '/')) : null;
    }

    public function backUrl(): ?string
    {
        return $this->id_card_back ? asset('storage/'.ltrim($this->id_card_back, '/')) : null;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['reserved', 'checked_in']);
    }

    public function scopeOverlapping(Builder $query, string $checkIn, string $checkOut, ?int $ignoreId = null): Builder
    {
        return $query
            ->whereIn('status', ['reserved', 'checked_in'])
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId));
    }

    public function recalculateTotals(): void
    {
        $extras = (float) $this->items()->sum('total');
        $this->extras_amount = $extras;
        $this->grand_total = (float) $this->room_amount + $extras;
        $this->applyPaymentTotals();
        $this->save();
    }

    public function applyPaymentTotals(): void
    {
        $roomPaid = round((float) $this->payments()->where('type', 'room')->sum('amount'), 2);
        $extrasPaid = round((float) $this->payments()->where('type', 'extras')->sum('amount'), 2);
        $roomBilled = $this->accruedRoomAmount();

        $this->room_paid_amount = min($roomBilled, $roomPaid);
        $this->extras_paid_amount = min((float) $this->extras_amount, $extrasPaid);
        $this->balance_due = max(0, round($roomBilled + (float) $this->extras_amount - $this->amountPaid(), 2));
        $this->payment_status = $this->balance_due <= 0 ? 'paid' : ($this->amountPaid() > 0 ? 'partial' : 'unpaid');
    }

    public function recordPayment(string $type, float $amount, string $method, ?int $receivedBy, ?string $note = null, ?int $bookingItemId = null, ?string $rentDate = null): BookingPayment
    {
        if ($this->status === 'checked_in') {
            $this->syncAccruedStayCharges();
        }

        $this->loadMissing(['items', 'payments']);

        if ($type === 'room') {
            $remaining = $rentDate
                ? $this->lineBalance('room', null, $rentDate)
                : $this->roomBalance();
        } elseif ($bookingItemId) {
            $remaining = $this->lineBalance('extras', $bookingItemId);
        } else {
            $remaining = $this->extrasBalance();
        }

        $amount = round($amount, 2);

        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'This charge is already paid.',
            ]);
        }

        if ($amount > $remaining) {
            throw ValidationException::withMessages([
                'amount' => 'Amount cannot exceed remaining '.($type === 'room' ? 'rent' : 'other charges').' of '.$remaining.'.',
            ]);
        }

        $payment = $this->payments()->create([
            'type' => $type,
            'booking_item_id' => $bookingItemId,
            'rent_date' => $type === 'room' ? $rentDate : null,
            'amount' => $amount,
            'method' => $method,
            'note' => $note,
            'received_by' => $receivedBy,
            'paid_at' => now(),
        ]);

        $this->unsetRelation('payments');
        $this->applyPaymentTotals();
        $this->save();

        return $payment;
    }

    public function plannedNights(): int
    {
        $start = $this->check_in->copy()->startOfDay();
        $end = $this->check_out->copy()->startOfDay();

        return max(1, (int) round($start->diffInDays($end, false)));
    }

    public function billedNights(?Carbon $asOf = null): int
    {
        $asOf ??= $this->checked_out_at ?? now();
        $planned = $this->plannedNights();

        if ($this->status === 'reserved') {
            return max($planned, (int) $this->nights);
        }

        $elapsed = max(0, (int) round(
            $this->check_in->copy()->startOfDay()->diffInDays($asOf->copy()->startOfDay(), false)
        ));

        $deadline = $this->check_out->copy()->setTime(12, 0);
        $lateDays = $asOf->greaterThan($deadline)
            ? (int) ceil($deadline->diffInSeconds($asOf) / 86400)
            : 0;

        return max($planned, $elapsed, $planned + $lateDays, (int) $this->nights);
    }

    public function rentNightDates(?Carbon $asOf = null): array
    {
        $dates = [];

        for ($i = 0; $i < $this->billedNights($asOf); $i++) {
            $dates[] = $this->check_in->copy()->startOfDay()->addDays($i);
        }

        return $dates;
    }

    public function accruedRoomAmount(?Carbon $asOf = null): float
    {
        return round($this->billedNights($asOf) * (float) $this->price_per_night, 2);
    }

    public function syncAccruedStayCharges(): void
    {
        if ($this->status !== 'checked_in') {
            return;
        }

        $nights = $this->billedNights();
        $roomAmount = $this->accruedRoomAmount();

        if ((int) $this->nights === $nights && abs((float) $this->room_amount - $roomAmount) < 0.01) {
            return;
        }

        $this->nights = $nights;
        $this->room_amount = $roomAmount;
        $this->grand_total = round($roomAmount + (float) $this->extras_amount, 2);
        $this->applyPaymentTotals();
        $this->save();
    }

    public function payableLines(): array
    {
        $this->loadMissing(['items', 'payments', 'room']);

        $price = round((float) $this->price_per_night, 2);
        $roomPayments = $this->payments->where('type', 'room')->values();
        $assigned = $roomPayments->filter(fn (BookingPayment $payment) => $payment->rent_date !== null);
        $unassignedPool = round((float) $roomPayments->filter(fn (BookingPayment $payment) => $payment->rent_date === null)->sum('amount'), 2);

        $lines = [];

        foreach ($this->rentNightDates() as $index => $date) {
            $dateStr = $date->toDateString();
            $direct = $assigned->filter(fn (BookingPayment $payment) => $payment->rent_date?->toDateString() === $dateStr);
            $paid = round((float) $direct->sum('amount'), 2);
            $need = max(0, round($price - $paid, 2));
            $fromPool = min($need, $unassignedPool);
            $paid = round($paid + $fromPool, 2);
            $unassignedPool = round($unassignedPool - $fromPool, 2);
            $remaining = max(0, round($price - $paid, 2));
            $methodSource = $remaining <= 0 && $fromPool > 0
                ? $direct->pluck('method')->concat(
                    $roomPayments->filter(fn (BookingPayment $payment) => $payment->rent_date === null)->pluck('method')
                )
                : $direct;

            $lines[] = [
                'type' => 'room',
                'booking_item_id' => null,
                'rent_date' => $dateStr,
                'label' => 'Room rent',
                'detail' => $date->format('d M Y').' · Day '.($index + 1).' · Room '.($this->room?->number ?? ''),
                'amount' => $price,
                'paid' => min($price, $paid),
                'remaining' => $remaining,
                'paid_method' => $this->methodsForPayments($methodSource),
            ];
        }

        $itemPayments = $this->payments
            ->where('type', 'extras')
            ->whereNotNull('booking_item_id')
            ->groupBy('booking_item_id');
        $unallocated = round((float) $this->payments->where('type', 'extras')->whereNull('booking_item_id')->sum('amount'), 2);

        foreach ($this->items as $item) {
            $direct = $itemPayments->get($item->id) ?? collect();
            $paid = round((float) $direct->sum('amount'), 2);
            $need = max(0, round((float) $item->total - $paid, 2));
            $fromPool = min($need, $unallocated);
            $paid = round($paid + $fromPool, 2);
            $unallocated = round($unallocated - $fromPool, 2);
            $remaining = max(0, round((float) $item->total - $paid, 2));
            $methodSource = $remaining <= 0 && $fromPool > 0
                ? $direct->pluck('method')->concat(
                    $this->payments->where('type', 'extras')->whereNull('booking_item_id')->pluck('method')
                )
                : $direct;

            $lines[] = [
                'type' => 'extras',
                'booking_item_id' => $item->id,
                'rent_date' => null,
                'label' => $item->name,
                'detail' => 'Qty '.$item->qty,
                'amount' => round((float) $item->total, 2),
                'paid' => min((float) $item->total, $paid),
                'remaining' => $remaining,
                'paid_method' => $this->methodsForPayments($methodSource),
            ];
        }

        return $lines;
    }

    public function lineBalance(string $type, ?int $bookingItemId = null, ?string $rentDate = null): float
    {
        foreach ($this->payableLines() as $line) {
            $sameType = $line['type'] === $type;
            $sameItem = (int) ($line['booking_item_id'] ?? 0) === (int) ($bookingItemId ?? 0);
            $sameDate = ($line['rent_date'] ?? null) === $rentDate;

            if ($sameType && $sameItem && $sameDate) {
                return (float) $line['remaining'];
            }
        }

        return 0.0;
    }

    public function roomBalance(): float
    {
        return max(0, round($this->accruedRoomAmount() - (float) $this->room_paid_amount, 2));
    }

    protected function methodsForPayments($payments): ?string
    {
        $methods = collect($payments)
            ->map(fn ($payment) => is_string($payment) ? $payment : ($payment->method ?? null))
            ->filter()
            ->unique()
            ->values();

        if ($methods->isEmpty()) {
            return null;
        }

        return $methods->count() === 1 ? $methods->first() : 'mixed';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'reserved' => 'Reserved',
            'checked_in' => 'Checked in',
            'checked_out' => 'Checked out',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['reserved', 'checked_in'], true);
    }

    public function amountPaid(): float
    {
        return round((float) $this->room_paid_amount + (float) $this->extras_paid_amount, 2);
    }

    public function extrasBalance(): float
    {
        return max(0, round((float) $this->extras_amount - (float) $this->extras_paid_amount, 2));
    }

    public function paymentState(): string
    {
        if ((float) $this->balance_due <= 0) {
            return 'paid';
        }

        return $this->amountPaid() > 0 ? 'partial' : 'unpaid';
    }

    public function paymentStatusLabel(): string
    {
        return match ($this->paymentState()) {
            'paid' => 'Paid',
            'partial' => 'Partial',
            default => 'Pending',
        };
    }

    public function checkoutSummary(): array
    {
        return [
            'room' => [
                'billed' => (float) $this->room_amount,
                'paid' => (float) $this->room_paid_amount,
                'due' => $this->roomBalance(),
            ],
            'extras' => [
                'billed' => (float) $this->extras_amount,
                'paid' => (float) $this->extras_paid_amount,
                'due' => $this->extrasBalance(),
            ],
            'total' => [
                'billed' => (float) $this->grand_total,
                'paid' => $this->amountPaid(),
                'due' => (float) $this->balance_due,
            ],
        ];
    }
}
