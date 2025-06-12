<?php

namespace App\View\Composers;

use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view)
    {
        $view->with('mainNavItems', [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'dashboard'],
            ['label' => 'Users', 'route' => 'users.index', 'icon' => 'users'],
            // Bisa ditambahno sak kebutuhanmu
        ]);
    }
}