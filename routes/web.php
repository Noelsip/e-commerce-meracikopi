<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\Admin\AuthAdminController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\MenuAdminController;
use App\Http\Controllers\Admin\TableAdminController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Customers\MenuController;
use App\Http\Controllers\Customers\OrderController;

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

// Customer API Routes
Route::prefix('api')->group(function () {
    // Public routes
    Route::get('/menus', [MenuController::class, 'index']);
    Route::get('/menus/{id}', [MenuController::class, 'show']);

    // Protected routes (requires Bearer token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthAdminController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthAdminController::class, 'login']);

    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])
            ->name('dashboard');

        Route::resource('menus', MenuAdminController::class);
        Route::resource('tables', TableAdminController::class);
        Route::patch('/tables/{table}/status', [TableAdminController::class, 'updateStatus'])
            ->name('tables.updateStatus');

        Route::resource('orders', OrderAdminController::class)->except(['create', 'store']);
        Route::patch('/orders/{order}/status', [OrderAdminController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        Route::post('/logout', [AuthAdminController::class, 'logout'])
            ->name('logout');
    });
});