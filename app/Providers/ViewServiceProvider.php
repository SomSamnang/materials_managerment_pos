<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Apply to all views
        View::composer('*', function ($view) {
            $route = Route::currentRouteName();

            $routeToLangKey = [
                'dashboard' => 'pages.dashboard',
                'materials.index' => 'pages.materials_index',
                'users.index' => 'pages.users_index',
                // Add more route mappings here
            ];

            $langKey = $routeToLangKey[$route] ?? 'pages.dashboard';

            $pageTitle = __($langKey);

            $view->with('pageTitle', $pageTitle);
        });
    }
}
