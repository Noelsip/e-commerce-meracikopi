<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Admin Controller
use App\Http\Controllers\Admin\AuthAdminController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\MenuAdminController;
use App\Http\Controllers\Admin\TableAdminController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Customers\MenuController;
use App\Http\Controllers\Customers\OrderController;
use App\Http\Controllers\Customers\CatalogController;

// Guest Routes
Route::get('/', function () {
    return view('pages.guest.welcome');
})->name('home');

// Customer Routes
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

/**
 * Customer Routes
 */

// Catalogs (Web Views - Public)
Route::get('/customer/catalogs', [CatalogController::class, 'index'])->name('catalogs.index');
Route::get('/customer/catalogs/{id}', [CatalogController::class, 'show'])->name('catalogs.show');

// Cart
Route::get('/customer/cart', function () {
    return view('pages.customer.cart');
})->name('cart.index');

// Checkout
Route::get('/customer/checkout', function () {
    return view('pages.customer.checkout');
})->name('checkout.index');

// Orders
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

// Order History
Route::get('/customer/order-history', function () {
    return view('pages.customer.order-history');
})->name('order-history.index');

/**
 * Admin Routes
 */
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth Routes (Guest)
    Route::get('/login', [AuthAdminController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [AuthAdminController::class, 'login']);

    // Protected Routes (Logged In)
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])
            ->name('dashboard');

        Route::resource('menus', MenuAdminController::class);
        Route::resource('tables', TableAdminController::class);
        Route::patch('/menus/{menu}/toggle-visibility', [MenuAdminController::class, 'toggleVisibility'])
            ->name('menus.toggleVisibility');
        Route::patch('/tables/{table}/status', [TableAdminController::class, 'updateStatus'])
            ->name('tables.updateStatus');

        Route::resource('orders', OrderAdminController::class)->except(['create', 'store']);
        Route::patch('/orders/{order}/status', [OrderAdminController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        Route::resource('users', UserAdminController::class);

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::post('/logout', [AuthAdminController::class, 'logout'])
            ->name('logout');

        // Katalog Views
        Route::get('/katalog', function () {
            return view('admin.katalog.index');
        })->name('katalog.index');

        Route::get('/katalog/create', function () {
            return view('admin.katalog.add-katalog');
        })->name('katalog.create');

        Route::get('/katalog/{id}/edit', function ($id) {
            return view('admin.katalog.edit-katalog', compact('id'));
        })->name('katalog.edit');
    });
});

require __DIR__ . '/auth.php';
