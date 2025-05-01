<?php

namespace App\Providers;

use App\View\Composers\NavigationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Using a class
        View::composer(['components.ui.navbar', 'components.layout.dashboard'], NavigationComposer::class);
        
        // Using a closure
        View::composer('*', function ($view) {
            $view->with('appName', config('app.name'));
        });
    }
}