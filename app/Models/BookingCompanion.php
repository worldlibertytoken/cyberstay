<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tenant_id', 'booking_id', 'name', 'father_name', 'cnic', 'address', 'id_card_front', 'id_card_back'])]
class BookingCompanion extends Model
{
    use BelongsToTenant;

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function frontUrl(): ?string
    {
        return $this->id_card_front ? asset('storage/'.ltrim($this->id_card_front, '/')) : null;
    }

    public function backUrl(): ?string
    {
        return $this->id_card_back ? asset('storage/'.ltrim($this->id_card_back, '/')) : null;
    }
}
