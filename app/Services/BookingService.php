<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function nights(string $checkIn, string $checkOut): int
    {
        $start = Carbon::parse($checkIn)->startOfDay();
        $end = Carbon::parse($checkOut)->startOfDay();

        return max(0, (int) round($start->diffInDays($end, false)));
    }

    public function assertRoomAvailable(int $roomId, string $checkIn, string $checkOut, ?int $ignoreBookingId = null): void
    {
        $room = Room::findOrFail($roomId);

        if ($room->status === 'maintenance') {
            throw ValidationException::withMessages([
                'room_id' => 'This room is under maintenance.',
            ]);
        }

        $overlap = Booking::query()
            ->where('room_id', $roomId)
            ->overlapping($checkIn, $checkOut, $ignoreBookingId)
            ->first();

        if ($overlap) {
            throw ValidationException::withMessages([
                'room_id' => 'Room '.$room->number.' is already booked from '.$overlap->check_in->format('d M').' to '.$overlap->check_out->format('d M').'.',
            ]);
        }
    }

    public function totals(int $nights, float $pricePerNight, float $extras = 0): array
    {
        $roomAmount = round($nights * $pricePerNight, 2);

        return [
            'nights' => $nights,
            'room_amount' => $roomAmount,
            'extras_amount' => $extras,
            'grand_total' => round($roomAmount + $extras, 2),
        ];
    }
}
