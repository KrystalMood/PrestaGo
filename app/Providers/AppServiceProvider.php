<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Services\AHPService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(ViewServiceProvider::class);
        
        $this->app->singleton(AHPService::class, function ($app) {
            return new AHPService();
        });
    }

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
