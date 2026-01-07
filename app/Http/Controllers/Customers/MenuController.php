<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Menus;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menus::query();

        if ($request->has('is_available')) {
            $query->where('is_available', $request->boolean('is_available'));
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menus = $query->get();

        return response()->json([
            'data' => $menus->map(fn($menu) => [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'price' => (int) $menu->price,
                'image' => $menu->image_path,
                'is_available' => $menu->is_available,
            ])
        ]);
    }

    public function show($id)
    {
        $menu = Menus::find($id);

        if (!$menu) {
            return response()->json(['message' => 'Menu not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'price' => (int) $menu->price,
                'image' => $menu->image_path,
                'is_available' => $menu->is_available,
            ]
        ]);
    }
}