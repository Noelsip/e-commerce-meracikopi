<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Customers\MenuController;

// Admin API (Untuk Admin Panel)
Route::prefix('admin')->group(function () {
    Route::get('/menus', [AdminMenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/{id}', [AdminMenuController::class, 'show'])->name('menus.show');
    Route::post('/menus', [AdminMenuController::class, 'store'])->name('menus.store');
    Route::put('/menus/{id}', [AdminMenuController::class, 'update'])->name('menus.update');
    Route::patch('/menus/{id}/availability', [AdminMenuController::class, 'updateAvailability'])->name('menus.availability');
    Route::delete('/menus/{id}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');
});