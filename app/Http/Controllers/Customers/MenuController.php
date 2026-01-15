<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Menus;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * GET /catalogs
     */
    public function index(Request $request)
    {
        // Hanya menampilkan menu yang belum dihapus
        $query = Menus::whereNull('deleted_at');

        if ($request->has('is_available')) {
            $isAvailable = filter_var($request->is_available, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($isAvailable !== null) {
                $query->where('is_available', $isAvailable);
            }
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menus = $query->get();

        return response()->json([
            'data' => $menus->map(fn($menu) => [
                'id' => $menu->id,
                'name' => $menu->name,
                'category' => $menu->category,
                'description' => $menu->description,
                'price' => (int) $menu->price,
                'discount_percentage' => (float) $menu->discount_percentage,
                'discount_price' => (int) $menu->discount_price,
                'final_price' => (int) $menu->final_price,
                'has_discount' => $menu->hasDiscount(),
                'image' => $menu->image_path,
                'is_available' => (bool) $menu->is_available,
            ])
        ], 200);
    }

    /**
     * GET /catalogs/{id}
     */
    public function show($id)
    {
        // Menampilkan menu yang belum dihapus
        $menu = Menus::find($id);

        if (!$menu) {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'category' => $menu->category,
                'description' => $menu->description,
                'price' => (int) $menu->price,
                'discount_percentage' => (float) $menu->discount_percentage,
                'discount_price' => (int) $menu->discount_price,
                'final_price' => (int) $menu->final_price,
                'discount_amount' => (int) $menu->discount_amount,
                'has_discount' => $menu->hasDiscount(),
                'image' => $menu->image_path,
                'is_available' => (bool) $menu->is_available,
            ]
        ], 200);
    }
}