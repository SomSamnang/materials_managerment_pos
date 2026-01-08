<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockMovementItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Language Switcher
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'kh'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Users
    Route::delete('/users/{user}/remove-photo', [UserController::class, 'removePhoto'])->name('users.remove_photo');
    Route::resource('users', UserController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Stock Items
    Route::prefix('stock-items')->name('stock_items.')->group(function () {
        Route::get('/', [StockItemController::class, 'index'])->name('index');
        Route::get('/create', [StockItemController::class, 'create'])->name('create');
        Route::post('/', [StockItemController::class, 'store'])->name('store');

        Route::get('/{stockItem}/stock', [StockMovementItemController::class, 'show'])->name('stock.show');
        Route::post('/{stockItem}/stock', [StockMovementItemController::class, 'store'])->name('stock.store');
    });

    // Materials
    Route::prefix('materials')->name('materials.')->group(function () {
        Route::get('/', [MaterialController::class, 'index'])->name('index');
        Route::get('/create', [MaterialController::class, 'create'])->name('create');
        Route::post('/', [MaterialController::class, 'store'])->name('store');
        Route::get('/{material}/edit', [MaterialController::class, 'edit'])->name('edit');
        Route::put('/{material}', [MaterialController::class, 'update'])->name('update');
        Route::delete('/{material}', [MaterialController::class, 'destroy'])->name('destroy');
        Route::post('/{material}/adjust-stock', [MaterialController::class, 'adjustStock'])->name('stock.adjust');
        Route::get('/stock/create-bulk', [MaterialController::class, 'createBulkStock'])->name('stock.create_bulk');
        Route::post('/stock/store-bulk', [MaterialController::class, 'storeBulkStock'])->name('stock.store_bulk');
        Route::post('/bulk-status', [MaterialController::class, 'bulkUpdateStatus'])->name('bulk_status');
        Route::get('/{material}', [MaterialController::class, 'show'])->name('show');
    });

    // Purchases
    Route::resource('purchases', PurchaseController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Settings
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/currency/switch/{currency}', [SettingController::class, 'switchCurrency'])->name('currency.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
  // Orders
    // Quick Create Route FIRST to avoid resource conflicts
    Route::get('orders/quick-create', [OrderController::class, 'quickCreate'])->name('orders.quick_create');

    Route::resource('orders', OrderController::class);

    // Invoices
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');

        // Custom routes
        Route::get('/{invoice}/print', [InvoiceController::class, 'print'])->name('print');
        Route::post('/{invoice}/accept', [InvoiceController::class, 'accept'])->name('accept');
    });

});
