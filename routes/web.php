<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Admin Controller
use App\Http\Controllers\Admin\AuthAdminController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\AdminMenuController;

// Customer Controllers
use App\Http\Controllers\Customers\MenuController as CustomerMenuController;
use App\Http\Controllers\Customers\OrderController as CustomerOrderController;
use App\Http\Controllers\Customers\CartController;
use App\Http\Controllers\Customers\CartItemController;

// Guest Routes
Route::get('/', function () {
    return view('welcome');
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
// Route::prefix('customer')
//     ->middleware('guest.token')
//     ->group(function() {
//         // Catalogs
//         Route::get('/catalogs', [MenuController::class, 'index'])->name('catalogs.index');
//         Route::get('/catalogs/{id}', [MenuController::class, 'show'])->name('catalogs.show');     
        
//         // Cart
//         Route::get('/cart', [CartController::class, 'show']);
//         Route::post('/cart/items', [CartItemController::class, 'store']);
//         Route::put('/cart/items/{id}', [CartItemController::class, 'update']);
//         Route::delete('/cart/items/{id}', [CartItemController::class, 'destroy']);

//         // Order
//         Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
//         Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
//     });

/**
 * Admin Routes
 */
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Auth Routes (Guest)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthAdminController::class, 'showLoginForm'])
            ->name('login');
        Route::post('/login', [AuthAdminController::class, 'login']);
    });

    // Protected Routes (Logged In Admin)
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])
            ->name('dashboard');

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

        // Order Management
        Route::get('/orders', [AdminOrderController::class, 'index']);
        Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
        Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);
    });
});

require __DIR__.'/auth.php';