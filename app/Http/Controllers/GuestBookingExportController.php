<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Support\TenantContext;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestBookingExportController extends Controller
{
    public function __invoke(Request $request, Booking $booking): Response
    {
        abort_if($booking->tenant_id !== TenantContext::id(), 403);

        $booking->loadMissing([
            'tenant',
            'room',
            'customer',
            'companions',
            'creator',
        ]);

        $pdf = Pdf::loadView('bookings.guest-booking-no-prices', [
            'booking' => $booking,
            'generatedAt' => now(),
        ])->setPaper('A4');

        return $pdf->download('guest-booking-'.$booking->id.'.pdf');
    }
}
