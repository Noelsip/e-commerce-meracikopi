<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang (guest)
     */
    public function show(Request $request)
    {
        // 1. Ambil guest token dari middleware
        $guestToken = $request->attributes->get('guest_token');

        // 2. Skip cache to ensure notes are always fresh
        $cacheKey = 'cart_' . $guestToken;
        Cache::forget($cacheKey); // Always get fresh data

        $result = (function () use ($guestToken) {
            // Query cart data directly (Eager Loading)
            $cart = Cart::with([
                'items' => function ($query) {
                    // Select only necessary columns from cart_items
                    $query->select('id', 'cart_id', 'menu_id', 'quantity', 'note');
                },
                'items.menu' => function ($query) {
                    // Select only necessary columns from menus
                    $query->select('id', 'name', 'price', 'image_path');
                }
            ])
                ->select('id', 'guest_token')
                ->where('guest_token', $guestToken)
                ->first();

            // Jika cart belum ada
            if (!$cart) {
                return [
                    'items' => [],
                    'total_price' => 0
                ];
            }

            // Format item & hitung total
            $items = [];
            $totalPrice = 0;

            foreach ($cart->items as $item) {
                // Check if menu still exists (soft delete handling)
                if (!$item->menu)
                    continue;

                $subtotal = $item->menu->price * $item->quantity;
                $totalPrice += $subtotal;

                $items[] = [
                    'id' => $item->id,
                    'menu_id' => $item->menu_id,
                    'menu_name' => $item->menu->name,
                    'menu_image' => $item->menu->image_path ? asset($item->menu->image_path) : null,
                    'price' => $item->menu->price,
                    'quantity' => $item->quantity,
                    'note' => $item->note,
                    'subtotal' => $subtotal
                ];
            }

            return [
                'items' => $items,
                'total_price' => $totalPrice
            ];
        })();

        return response()->json([
            'data' => [
                'items' => $result['items'],
                'total' => $result['total_price'], // Legacy support
                'total_price' => $result['total_price']
            ]
        ]);
    }
}
