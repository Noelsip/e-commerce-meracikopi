<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Customers\MenuController;
use App\Http\Controllers\Customers\CartController;
use App\Http\Controllers\Customers\CartItemController;
use App\Http\Controllers\Customers\OrderController;

// Admin API (Untuk Admin Panel)
Route::prefix('admin')->group(function () {
    Route::get('/menus', [AdminMenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/{id}', [AdminMenuController::class, 'show'])->name('menus.show');
    Route::post('/menus', [AdminMenuController::class, 'store'])->name('menus.store');
    Route::put('/menus/{id}', [AdminMenuController::class, 'update'])->name('menus.update');
    Route::patch('/menus/{id}/availability', [AdminMenuController::class, 'updateAvailability'])->name('menus.availability');
    Route::delete('/menus/{id}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');
});

// Customer API - Catalogs (Public)
Route::prefix('customer')->group(function () {
    // GET /api/customer/catalogs - Get All Catalogs
    // GET /api/customer/catalogs?is_available=true - Get Available Catalogs Only
    // GET /api/customer/catalogs?search=kopi - Search Catalog by Name
    Route::get('/catalogs', [MenuController::class, 'index'])->name('customer.catalogs.index');

    // GET /api/customer/catalogs/{id} - Get Catalog Detail by ID
    Route::get('/catalogs/{id}', [MenuController::class, 'show'])->name('customer.catalogs.show');
});

// Customer API - Cart & Orders (Guest Token Required)
Route::prefix('customer')->middleware('guest.token')->group(function () {

    // Cart Management
    // GET /api/customer/cart - View Cart
    Route::get('/cart', [CartController::class, 'show'])->name('customer.cart.show');

    // POST /api/customer/cart/items - Add Item to Cart
    Route::post('/cart/items', [CartItemController::class, 'store'])->name('customer.cart.items.store');

    // PUT /api/customer/cart/items/{id} - Update Cart Item Quantity
    Route::put('/cart/items/{id}', [CartItemController::class, 'update'])->name('customer.cart.items.update');

    // DELETE /api/customer/cart/items/{id} - Delete Cart Item
    Route::delete('/cart/items/{id}', [CartItemController::class, 'destroy'])->name('customer.cart.items.destroy');

    // Orders
    // GET /api/customer/orders - Get All Orders
    // GET /api/customer/orders?status=pending - Get Orders by Status
    Route::get('/orders', [OrderController::class, 'index'])->name('customer.orders.index');

    // POST /api/customer/orders - Create Order (Take Away, Dine In, Delivery)
    Route::post('/orders', [OrderController::class, 'store'])->name('customer.orders.store');

    // GET /api/customer/orders/{id} - Get Order Detail
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('customer.orders.show');
});