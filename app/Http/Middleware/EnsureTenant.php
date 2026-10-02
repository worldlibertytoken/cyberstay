<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->isSuperAdmin() && ! TenantContext::has()) {
            return redirect()
                ->route('hotels.index')
                ->with('info', 'Select a hotel to continue.');
        }

        if (! $user?->isSuperAdmin() && ! $user?->tenant_id) {
            abort(403, 'This account is not assigned to a hotel.');
        }

        return $next($request);
    }
}
