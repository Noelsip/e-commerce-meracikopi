<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Customers\MenuController;
use App\Http\Controllers\Customers\OrderController;
use App\Http\Controllers\Customers\CartController;
use App\Http\Controllers\Customers\CartItemController;

// Admin API - /api/admin/*
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/menus', [AdminMenuController::class, 'index']);
    Route::post('/menus', [AdminMenuController::class, 'store']);
    Route::get('/menus/{id}', [AdminMenuController::class, 'show']);
    Route::put('/menus/{id}', [AdminMenuController::class, 'update']);
    Route::delete('/menus/{id}', [AdminMenuController::class, 'destroy']);
    Route::patch('/menus/{id}/availability', [AdminMenuController::class, 'updateAvailability']);
});

// Customer API - /api/customer/*
Route::prefix('customer')->middleware('guest.token')->group(function () {
    Route::get('/catalogs', [MenuController::class, 'index']);
    Route::get('/catalogs/{id}', [MenuController::class, 'show']);
    
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartItemController::class, 'store']);
    Route::put('/cart/items/{id}', [CartItemController::class, 'update']);
    Route::delete('/cart/items/{id}', [CartItemController::class, 'destroy']);
    
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
});