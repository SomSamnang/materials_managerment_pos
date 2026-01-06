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
            $pageTitle = 'ទំព័រដេប៊ូ - ប្រព័ន្ធគ្រប់គ្រងសម្ភារៈ';

            // Get current route name if exists
            $routeName = optional(Route::current())->getName();

            // Map route names to Khmer titles
            $titles = [
                // Dashboard
                'dashboard' => 'ទំព័រដេប៊ូ',

                // Materials
                'materials.index' => 'បញ្ជីសម្ភារៈ',
                'materials.create' => 'បង្កើតសម្ភារៈថ្មី',
                'materials.show' => 'ព័ត៌មានសម្ភារៈ',
                'materials.edit' => 'កែសម្ភារៈ',

                // Users
                'users.index' => 'បញ្ជីអ្នកប្រើប្រាស់',
                'users.create' => 'បង្កើតអ្នកប្រើប្រាស់ថ្មី',
                'users.show' => 'ព័ត៌មានអ្នកប្រើប្រាស់',
                'users.edit' => 'កែអ្នកប្រើប្រាស់',

                // Orders
                'orders.index' => 'បញ្ជីការបញ្ជាទិញ',
                'orders.create' => 'បង្កើតការបញ្ជាទិញថ្មី',
                'orders.show' => 'ព័ត៌មានការបញ្ជាទិញ',
                'orders.edit' => 'កែការបញ្ជាទិញ',
                'orders.quick_create' => 'បញ្ជាទិញលឿន',

                // Stock
                'materials.stock.create_bulk' => 'បញ្ចូលស្តុក',
                //add_stock
                'create_bulk_stock' => 'បញ្ជាទិញចំនួន',
                
                // Invoices
                'invoices.index' => 'បញ្ជីវិក្កយបត្រ',
                'invoices.create' => 'បង្កើតវិក្កយបត្រថ្មី',
                'invoices.show' => 'ព័ត៌មានវិក្កយបត្រ',
                'invoices.edit' => 'កែវិក្កយបត្រ',
                'invoices.print' => 'បោះពុម្ពវិក្កយបត្រ',

                // Purchases
                'purchases.index' => 'បញ្ជីការទិញចូល',
                'purchases.create' => 'កត់ត្រាការទិញចូល',

                // Suppliers
                'suppliers.index' => 'បញ្ជីអ្នកផ្គត់ផ្គង់',
                'suppliers.create' => 'បង្កើតអ្នកផ្គត់ផ្គង់ថ្មី',
                'suppliers.show' => 'ព័ត៌មានអ្នកផ្គត់ផ្គង់',
                'suppliers.edit' => 'កែប្រែអ្នកផ្គត់ផ្គង់',

                // Authentication
                'login' => 'ចូលប្រព័ន្ធ',
                'register' => 'ចុះឈ្មោះ',
                'password.request' => 'ស្នើសុំពាក្យសម្ងាត់ថ្មី',
                'password.reset' => 'កំណត់ពាក្យសម្ងាត់ថ្មី',
                'password.confirm' => 'បញ្ជាក់ពាក្យសម្ងាត់',

                // Home/Welcome
                'home' => 'ទំព័រដេប៊ូ',
                'welcome' => 'ស្វាគមន៍',
            ];

            // Set page title if route matches
            if ($routeName && array_key_exists($routeName, $titles)) {
                $pageTitle = $titles[$routeName] . ' - ប្រព័ន្ធគ្រប់គ្រងសម្ភារៈ';
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
