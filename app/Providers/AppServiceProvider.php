<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\AuthLayout;
use App\View\Components\Layout;
use Illuminate\Routing\Router;

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
    public function boot(Router $router): void
    {
        Blade::component('layout', Layout::class);
        Blade::component('auth-layout', AuthLayout::class);

        // Daftarkan middleware secara eksplisit
        $router->aliasMiddleware('check.role', \App\Http\Middleware\RoleMiddleware::class);
    }
}
