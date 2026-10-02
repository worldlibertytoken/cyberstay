<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'tenant_id',
    'booking_id',
    'booking_item_id',
    'rent_date',
    'received_by',
    'type',
    'amount',
    'method',
    'note',
    'paid_at',
])]
class BookingPayment extends Model
{
    use BelongsToTenant;

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'rent_date' => 'date',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(BookingItem::class, 'booking_item_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function typeLabel(): string
    {
        if ($this->type === 'extras' && $this->item) {
            return $this->item->name;
        }

        return match ($this->type) {
            'room' => $this->rent_date
                ? 'Rent · '.$this->rent_date->format('d M Y')
                : 'Rent',
            'extras' => 'Other charges',
            default => ucfirst((string) $this->type),
        };
    }

    public function methodLabel(): string
    {
        return match ($this->method) {
            'cash' => 'Cash',
            'online' => 'Online',
            default => ucfirst((string) $this->method),
        };
    }
}
