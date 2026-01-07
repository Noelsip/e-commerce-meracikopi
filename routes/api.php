<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminMenuController;

// Public API v1 (Untuk Customer - Tanpa Auth)
Route::prefix('v1')->group(function () {
    Route::get('/menus', [AdminMenuController::class, 'index']);
    Route::get('/menus/{id}', [AdminMenuController::class, 'show']);
});

// Admin API (Untuk Admin Panel)
Route::prefix('admin')->group(function () {
    Route::get('/menus', [AdminMenuController::class, 'index']);
    Route::get('/menus/{id}', [AdminMenuController::class, 'show']);
    Route::post('/menus', [AdminMenuController::class, 'store']);
    Route::put('/menus/{id}', [AdminMenuController::class, 'update']);
    Route::patch('/menus/{id}/availability', [AdminMenuController::class, 'updateAvailability']);
    Route::delete('/menus/{id}', [AdminMenuController::class, 'destroy']);
});