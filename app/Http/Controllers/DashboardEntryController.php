<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Support\TenantContext;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardEntryController extends Controller
{
    public function index(Request $request, string $scope)
    {
        $definition = $this->scopeDefinition($scope);
        $baseQuery = $this->scopeQuery($scope);

        $bookings = (clone $baseQuery)
            ->paginate(20)
            ->withQueryString();

        $totals = $this->totals((clone $baseQuery)->get());

        return view('dashboard-entries.index', [
            'scope' => $scope,
            'definition' => $definition,
            'bookings' => $bookings,
            'totals' => $totals,
        ]);
    }

    public function show(string $scope, Booking $booking)
    {
        $definition = $this->scopeDefinition($scope);
        abort_unless($this->isInScope($scope, $booking), 404);

        $booking->loadMissing(['room', 'customer', 'items', 'creator', 'companions', 'tenant']);

        return view('dashboard-entries.show', [
            'scope' => $scope,
            'definition' => $definition,
            'booking' => $booking,
        ]);
    }

    public function download(string $scope): Response
    {
        $definition = $this->scopeDefinition($scope);
        $bookings = $this->scopeQuery($scope)->get();
        $totals = $this->totals($bookings);
        $fileName = sprintf('%s-%s.pdf', $definition['slug'], now()->format('Ymd-Hi'));

        return Pdf::loadView('dashboard-entries.list-pdf', [
            'scope' => $scope,
            'definition' => $definition,
            'bookings' => $bookings,
            'totals' => $totals,
            'tenant' => TenantContext::tenant(),
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape')->download($fileName);
    }

    public function downloadSingle(string $scope, Booking $booking): Response
    {
        $definition = $this->scopeDefinition($scope);
        abort_unless($this->isInScope($scope, $booking), 404);

        $booking->loadMissing(['room', 'customer', 'items', 'creator', 'companions', 'tenant']);
        $fileName = sprintf('%s-booking-%d.pdf', $definition['slug'], $booking->id);

        return Pdf::loadView('dashboard-entries.single-pdf', [
            'scope' => $scope,
            'definition' => $definition,
            'booking' => $booking,
            'tenant' => TenantContext::tenant(),
            'generatedAt' => now(),
        ])->setPaper('a4')->download($fileName);
    }

    private function scopeQuery(string $scope): Builder
    {
        $today = now()->toDateString();

        return Booking::query()
            ->with(['room', 'customer'])
            ->when($scope === 'arrivals', function (Builder $query) use ($today) {
                $query
                    ->whereDate('check_in', $today)
                    ->whereIn('status', ['reserved', 'checked_in'])
                    ->orderBy('check_in')
                    ->orderBy('id');
            })
            ->when($scope === 'in-house', function (Builder $query) {
                $query
                    ->where('status', 'checked_in')
                    ->orderBy('check_in')
                    ->orderBy('id');
            })
            ->when($scope === 'departures', function (Builder $query) use ($today) {
                $query
                    ->whereDate('check_out', $today)
                    ->whereIn('status', ['reserved', 'checked_in'])
                    ->orderBy('check_out')
                    ->orderBy('id');
            });
    }

    private function isInScope(string $scope, Booking $booking): bool
    {
        $today = now()->toDateString();

        return match ($scope) {
            'arrivals' => $booking->check_in?->toDateString() === $today
                && in_array($booking->status, ['reserved', 'checked_in'], true),
            'in-house' => $booking->status === 'checked_in',
            'departures' => $booking->check_out?->toDateString() === $today
                && in_array($booking->status, ['reserved', 'checked_in'], true),
            default => false,
        };
    }

    private function scopeDefinition(string $scope): array
    {
        return match ($scope) {
            'arrivals' => [
                'title' => 'Arrivals',
                'subtitle' => 'Guests expected to check in today',
                'slug' => 'arrivals',
                'icon' => 'door',
            ],
            'in-house' => [
                'title' => 'In-house',
                'subtitle' => 'Guests currently staying',
                'slug' => 'in-house',
                'icon' => 'users',
            ],
            'departures' => [
                'title' => 'Departures',
                'subtitle' => 'Guests scheduled to check out today',
                'slug' => 'departures',
                'icon' => 'logout',
            ],
            default => abort(404),
        };
    }

    private function totals($bookings): array
    {
        $paid = $bookings->sum(function (Booking $booking) {
            return (float) $booking->room_paid_amount + (float) $booking->extras_paid_amount;
        });

        return [
            'count' => $bookings->count(),
            'grand_total' => (float) $bookings->sum('grand_total'),
            'paid' => (float) $paid,
            'balance' => (float) $bookings->sum('balance_due'),
        ];
    }
}
