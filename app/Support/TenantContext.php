<?php

namespace App\Support;

use App\Models\Tenant;

class TenantContext
{
    public static function id(): ?int
    {
        return app()->bound('tenant.id') ? app('tenant.id') : null;
    }

    public static function set(?int $id): void
    {
        if ($id) {
            app()->instance('tenant.id', $id);
        } elseif (app()->bound('tenant.id')) {
            app()->forgetInstance('tenant.id');
        }
    }

    public static function tenant(): ?Tenant
    {
        $id = self::id();

        return $id ? Tenant::find($id) : null;
    }

    public static function has(): bool
    {
        return (bool) self::id();
    }
}
