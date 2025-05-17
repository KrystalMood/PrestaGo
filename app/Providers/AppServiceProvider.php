<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(ViewServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');
        
        Blade::componentNamespace('App\\View\\Components\\Admin\\Users', 'admin.users');
        
        Blade::anonymousComponentNamespace('resources/views/admin/users/components', 'admin.users.components');
        
        Blade::anonymousComponentNamespace('resources/views/components/admin/components', 'admin.components');
    }
}
