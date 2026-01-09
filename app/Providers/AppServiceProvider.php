<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set default string length for older MySQL versions
        Schema::defaultStringLength(191);

        // Enable query logging in development
        if (config('app.debug')) {
            DB::listen(function ($query) {
                Log::info('SQL Query', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time
                ]);
            });
        }

        // Share page titles with all views
        View::composer('*', function ($view) {
            // Default title
            $pageTitle = __('Dashboard') . ' - ' . __('Material Management System');

            // Get current route name if exists
            $routeName = optional(Route::current())->getName();

            // Map route names to Khmer titles
            $titles = [
                // Dashboard
                'dashboard' => __('Dashboard'),

                // Materials
                'materials.index' => __('Material List'),
                'materials.create' => __('Create New Material'),
                'materials.show' => __('Material Details'),
                'materials.edit' => __('Edit Material'),

                // Users
                'users.index' => __('User List'),
                'users.create' => __('Create New User'),
                'users.show' => __('User Details'),
                'users.edit' => __('Edit User'),

                // Orders
                'orders.index' => __('Order List'),
                'orders.create' => __('Create New Order'),
                'orders.show' => __('Order Details'),
                'orders.edit' => __('Edit Order'),
                'orders.quick_create' => __('Quick Order'),

                // Stock
                'materials.stock.create_bulk' => __('Add Stock'),
                //add_stock
                'create_bulk_stock' => __('Bulk Order'),
                
                // Invoices
                'invoices.index' => __('Invoice List'),
                'invoices.create' => __('Create New Invoice'),
                'invoices.show' => __('Invoice Details'),
                'invoices.edit' => __('Edit Invoice'),
                'invoices.print' => __('Print Invoice'),

                // Purchases
                'purchases.index' => __('Purchase List'),
                'purchases.create' => __('Record Purchase'),

                // Suppliers
                'suppliers.index' => __('Supplier List'),
                'suppliers.create' => __('Create New Supplier'),
                'suppliers.show' => __('Supplier Details'),
                'suppliers.edit' => __('Edit Supplier'),

                // Settings
                'settings.edit' => __('System Settings'),

                // Authentication
                'login' => __('Login'),
                'register' => __('Register'),
                'password.request' => __('Request New Password'),
                'password.reset' => __('Reset Password'),
                'password.confirm' => __('Confirm Password'),

                // Home/Welcome
                'home' => __('Dashboard'),
                'welcome' => __('Welcome'),
            ];

            // Set page title if route matches
            if ($routeName && array_key_exists($routeName, $titles)) {
                $pageTitle = $titles[$routeName] . ' - ' . __('Material Management System');
            }

            // Share with all views
            $view->with('pageTitle', $pageTitle);
        });

        // Set application locale
        setlocale(LC_TIME, 'km_KH.UTF-8', 'km_KH', 'kh');

        // Configure pagination to use Bootstrap 5
        // \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Set default timezone if needed
        // date_default_timezone_set('Asia/Phnom_Penh');
    }
}
