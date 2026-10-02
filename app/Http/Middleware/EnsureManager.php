<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->canManageHotel()) {
            abort(403, 'Only managers can access this section.');
        }

        return $next($request);
    }
}
