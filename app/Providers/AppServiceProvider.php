<?php

namespace App\Providers;

use App\Models\BookingItem;
use App\Models\PosItem;
use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('hotel', fn ($value) => Tenant::findOrFail($value));
        Route::bind('staff', function ($value) {
            return User::query()
                ->whereKey($value)
                ->where('tenant_id', TenantContext::id())
                ->firstOrFail();
        });
        Route::bind('item', function ($value, $route) {
            if ($route->hasParameter('booking')) {
                return BookingItem::query()->whereKey($value)->firstOrFail();
            }

            return PosItem::query()->whereKey($value)->firstOrFail();
        });

        Blade::directive('money', function ($expression) {
            return "<?php echo 'Rs '.number_format((float) ({$expression}), 0); ?>";
        });
    }
}
