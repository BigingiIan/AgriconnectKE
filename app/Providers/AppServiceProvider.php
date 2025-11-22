<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use App\Http\Middleware\CheckRole;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('role', function ($app) {
            return new CheckRole();
        });
    }

    public function boot()
    {
        Paginator::useBootstrap();

        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });
    }
}