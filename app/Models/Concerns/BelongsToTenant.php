<?php

namespace App\Models\Concerns;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            $tenantId = TenantContext::id();

            if ($tenantId) {
                $query->where($query->getModel()->getTable().'.tenant_id', $tenantId);
            }
        });

        static::creating(function ($model) {
            if (empty($model->tenant_id) && TenantContext::id()) {
                $model->tenant_id = TenantContext::id();
            }
        });
    }
}
