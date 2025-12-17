<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
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
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Blade directive to check if user is head of dispatch
        Blade::if('headDispatch', function () {
            return auth()->check() && auth()->user()->isHeadDispatch();
        });

        // Blade directive to check if user is dispatch officer
        Blade::if('dispatch', function () {
            return auth()->check() && auth()->user()->isDispatch();
        });

        // Blade directive to check if user is admin (head of dispatch)
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        // Blade directive to check specific role
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });
    }
}
