<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Room;
use App\Support\TenantContext;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();

        $rooms = Room::query()->orderBy('number')->with(['bookings' => function ($q) use ($today) {
            $q->where(function ($query) use ($today) {
                $query->where(function ($checkedIn) {
                    $checkedIn->where('status', 'checked_in')
                        ->whereNotNull('checked_in_at');
                })->orWhere(function ($reserved) use ($today) {
                    $reserved->where('status', 'reserved')
                        ->where('check_in', '<=', $today)
                        ->where('check_out', '>', $today);
                });
            })
                ->orderByRaw("CASE WHEN status = 'checked_in' THEN 0 ELSE 1 END")
                ->orderBy('check_in')
                ->with('customer');
        }])->get();

        $occupied = $rooms->filter(fn (Room $room) => $room->occupancyStatus($today) === 'occupied')->count();
        $reservedToday = $rooms->filter(fn (Room $room) => $room->occupancyStatus($today) === 'reserved')->count();
        $available = $rooms->filter(fn (Room $room) => $room->occupancyStatus($today) === 'available')->count();
        $maintenance = $rooms->filter(fn (Room $room) => $room->occupancyStatus($today) === 'maintenance')->count();
        $availableRooms = $rooms
            ->filter(fn (Room $room) => $room->occupancyStatus($today) === 'available')
            ->sortBy(fn (Room $room) => (string) $room->number, SORT_NATURAL)
            ->values();
        $roomCount = $rooms->count();
        $occupancyPercent = $roomCount ? (int) round(($occupied / $roomCount) * 100) : 0;

        $todayCheckIns = Booking::with(['room', 'customer'])
            ->whereDate('check_in', $today)
            ->whereIn('status', ['reserved', 'checked_in'])
            ->orderBy('id')
            ->get();

        $todayCheckOuts = Booking::with(['room', 'customer'])
            ->whereDate('check_out', $today)
            ->whereIn('status', ['reserved', 'checked_in'])
            ->orderBy('id')
            ->get();

        $checkedIn = Booking::with(['room', 'customer'])
            ->where('status', 'checked_in')
            ->orderBy('check_in')
            ->get();

        $todayBookings = Booking::query()
            ->where('status', '!=', 'cancelled')
            ->whereDate('created_at', $today)
            ->get(['grand_total', 'room_paid_amount', 'extras_paid_amount', 'balance_due']);

        $todayRevenue = (float) $todayBookings->sum('grand_total');
        $todayReceived = (float) BookingPayment::query()
            ->whereDate('paid_at', $today)
            ->sum('amount');
        $todayPending = (float) $todayBookings->sum('balance_due');

        return view('dashboard', [
            'hotel' => TenantContext::tenant(),
            'rooms' => $rooms,
            'availableRooms' => $availableRooms,
            'occupied' => $occupied,
            'reservedToday' => $reservedToday,
            'available' => $available,
            'maintenance' => $maintenance,
            'roomCount' => $roomCount,
            'occupancyPercent' => $occupancyPercent,
            'todayCheckIns' => $todayCheckIns,
            'todayCheckOuts' => $todayCheckOuts,
            'checkedIn' => $checkedIn,
            'todayRevenue' => $todayRevenue,
            'todayReceived' => $todayReceived,
            'todayPending' => $todayPending,
        ]);
    }
}
