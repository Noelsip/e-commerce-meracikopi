<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\Admin\AuthAdminController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Customers\MenuController;
use App\Http\Controllers\Customers\OrderController;

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

// Catalogs
Route::get('/catalogs', [MenuController::class, 'index'])->name('catalogs.index');
Route::get('/catalogs/{id}', [MenuController::class, 'show'])->name('catalogs.show');

// Orders
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

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

require __DIR__.'/auth.php';