<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\RoleMiddleware;

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
            $router->aliasMiddleware('role', RoleMiddleware::class);
    }
    
    // public function boot()
    // {
    //     if (isset($_SERVER['HTTP_HOST'])) {
    //         \URL::forceRootUrl((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);
    //     }
    // }

}
