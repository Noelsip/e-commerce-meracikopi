<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AuthAdminController;
use App\Http\Controllers\Api\Admin\TableApiController;
use App\Http\Controllers\Api\Customer\TableController;
use App\Http\Controllers\Customers\MenuController;
use App\Http\Controllers\Customers\CartController;
use App\Http\Controllers\Customers\CartItemController;
use App\Http\Controllers\Customers\OrderController;
use App\Http\Controllers\Customers\PaymentController;

// Admin Login (no auth required)
Route::post('/admin/login', [AuthAdminController::class, 'login']);

// Admin API - /api/admin/*
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    // Menus
    Route::get('/menus', [AdminMenuController::class, 'index'])->name('menus.index');
    Route::post('/menus', [AdminMenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{id}', [AdminMenuController::class, 'show'])->name('menus.show');
    Route::put('/menus/{id}', [AdminMenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{id}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');
    Route::patch('/menus/{id}/availability', [AdminMenuController::class, 'updateAvailability'])->name('menus.availability');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);

    // Tables
    Route::get('/tables', [TableApiController::class, 'index']);
    Route::post('/tables', [TableApiController::class, 'store']);
    Route::get('/tables/{id}', [TableApiController::class, 'show']);
    Route::put('/tables/{id}', [TableApiController::class, 'update']);
    Route::delete('/tables/{id}', [TableApiController::class, 'destroy']);
    Route::patch('/tables/{id}/status', [TableApiController::class, 'updateStatus']);
});

// Customer API - Catalogs (Public)
Route::prefix('customer')->group(function () {
    // GET /api/customer/catalogs - Get All Catalogs
    // GET /api/customer/catalogs?is_available=true - Get Available Catalogs Only
    // GET /api/customer/catalogs?search=kopi - Search Catalog by Name
    Route::get('/catalogs', [MenuController::class, 'index'])->name('customer.catalogs.index');

    // GET /api/customer/catalogs/{id} - Get Catalog Detail by ID
    Route::get('/catalogs/{id}', [MenuController::class, 'show'])->name('customer.catalogs.show');

    // Tables (public - available tables only)
    Route::get('/tables', [TableController::class, 'index'])->name('customer.tables.index');
    Route::get('/tables/{id}', [TableController::class, 'show'])->name('customer.tables.show');
});

// Customer API - Cart & Orders (Guest Token Required)
Route::prefix('customer')->middleware('guest.token')->group(function () {
    // Cart Management
    Route::get('/cart', [CartController::class, 'show'])->name('customer.cart.show');
    Route::post('/cart/items', [CartItemController::class, 'store'])->name('customer.cart.items.store');
    Route::put('/cart/items/{id}', [CartItemController::class, 'update'])->name('customer.cart.items.update');
    Route::delete('/cart/items/{id}', [CartItemController::class, 'destroy'])->name('customer.cart.items.destroy');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('customer.orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('customer.orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('customer.orders.show');

    // Payment
    Route::post('/orders/{orderId}/pay', [PaymentController::class, 'pay']);
});

// Midtrans webhook
Route::post('/webhooks/midtrans', [PaymentController::class, 'midtransWebhook']);

