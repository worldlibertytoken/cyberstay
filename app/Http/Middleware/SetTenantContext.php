<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            if ($user->isSuperAdmin()) {
                TenantContext::set($request->session()->get('current_tenant_id'));
            } else {
                TenantContext::set($user->tenant_id);
            }
        }

        return $next($request);
    }
}
