<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang (guest)
     */
    public function show(Request $request)
    {
        // 1. Ambil guest token dari middleware
        $guestToken = $request->attributes->get('guest_token');

        // 2. Ambil cart berdasarkan guest token
        $cart = Cart::with('items.menu')
            ->where('guest_token', $guestToken)
            ->first();

        // 3. Jika cart belum ada
        if (!$cart) {
            return response()->json([
                'data' => [
                    'items' => [],
                    'total_price' => 0
                ]
            ]);
        }

        // 4. Format item & hitung total
        $items = [];
        $totalPrice = 0;

        foreach ($cart->items as $item) {
            $subtotal = $item->menu->price * $item->quantity;
            $totalPrice += $subtotal;

            $items[] = [
                'id' => $item->id,
                'menu_id' => $item->menu_id,
                'menu_name' => $item->menu->name,
                'price' => $item->menu->price,
                'quantity' => $item->quantity,
                'subtotal' => $subtotal
            ];
        }

        return response()->json([
            'data' => [
                'items' => $items,                'total' => $totalPrice,                'total_price' => $totalPrice
            ]
        ]);
    }
}
