<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Menus;
use Illuminate\Support\Facades\Cache;

class CartItemController extends Controller
{
    /**
     * Menambahkan item ke Cart
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        // Mengambil guest token
        $guestToken = $request->attributes->get('guest_token');

        // Memastikan menu tersedia
        $menu = Menus::where('id', $request->menu_id)
            ->where('is_available', true)
            ->first();

        if (!$menu) {
            return response()->json([
                'message' => 'Menu not available'
            ], 404);
        }

        // Mengambil atau buat cart
        $cart = Cart::firstOrCreate([
            'guest_token' => $guestToken
        ]);

        // pengecekan apakah item sudah ada atau belum (menu + note yang sama = item yang sama)
        $item = CartItem::where('cart_id', $cart->id)
            ->where('menu_id', $menu->id)
            ->where('note', $request->note ?? '')
            ->first();

        if ($item) {
            // menambah qty untuk item dengan menu dan note yang sama
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            // Membuat item baru (menu beda, atau menu sama tapi note/variant beda)
            CartItem::create([
                'cart_id' => $cart->id,
                'menu_id' => $menu->id,
                'quantity' => $request->quantity,
                'note' => $request->note ?? ''
            ]);
        }

        // Clear cache untuk cart ini agar data fresh
        Cache::forget('cart_' . $guestToken);

        return response()->json([
            'message' => 'Item added to cart'
        ], 201);
    }

    /**
     * Mengupdate qty item cart
     */
    public function update(Request $request, $id)
    {
        // Melakukan validasi data
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $guestToken = $request->attributes->get('guest_token');

        // Mengambil cart
        $cart = Cart::where('guest_token', $guestToken)->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart Not Found'
            ], 404);
        }

        // Mengambil item milik cart
        $item = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$item) {
            return response()->json([
                'message' => 'Item not found'
            ], 404);
        }

        // Update qty
        $item->quantity = $request->quantity;
        $item->save();

        // Clear cache untuk cart ini agar data fresh
        Cache::forget('cart_' . $guestToken);

        return response()->json([
            'message' => 'Cart item updated'
        ], 200);
    }

    /**
     * Menghapus item dari cart
     */
    public function destroy(Request $request, $id)
    {
        $guestToken = $request->attributes->get('guest_token');

        $cart = Cart::where('guest_token', $guestToken)->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart not found'
            ], 404);
        }

        $cartItem = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'message' => 'Cart item not found'
            ], 404);
        }

        $cartItem->delete();

        // Clear cache untuk cart ini agar data fresh
        Cache::forget('cart_' . $guestToken);

        return response()->json([
            'message' => 'Item removed from cart'
        ], 200);
    }
}
