<?php

namespace App\Http\Controllers\Admin;

// use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menus;

class AdminMenuController extends Controller
{
    /**
     * GET /admin/menus
     */
    public function index(Request $request)
    {
        $query = Menus::query();

        // Filter Available
        if ($request->has('is_available')) {
            $query->where('is_available', $request->is_available);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menus = $query->paginate(10);

        return response()->json([
            'data' => $menus->map(fn($menu) => [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'price' => (int) $menu->price,
                'image_path' => $menu->image_path,
                'is_available' => (bool) $menu->is_available,
                'created_at' => $menu->created_at->toIso8601String(),
            ])
        ], 200);
    }

    /**
     * GET /admin/menu/{id}
     */
    public function show($id)
    {
        $menu = Menus::find($id);

        // Jika menu tidak ditemukan
        if(!$menu){
            return response()->json([
                'message' => 'Menu not Found'
            ], 404);
        }

        // Jika ditemukan
        return response()->json([
            'data' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'price' => (int) $menu->price,
                'image_path' => $menu->image_path,
                'is_available' => (bool) $menu->is_available,
                'created_at' => $menu->created_at->toIso8601String(),
                'updated_at' => $menu->updated_at->toIso8601String(),
            ]
        ], 200);
    }

    /**
     * POST /admin/menus
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_path' => 'string|max:255',
            'is_available' => 'boolean'
        ]);

        $validated['is_available'] = $validated['is_available'] ?? true;

        $menu = Menus::create($validated);

        // Clear Cache ketika menu dibuat
        // Cache::tags(['menus'])->flush();

        return response()->json([
            'message' => 'Menu Created',
            'data' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'price' => $menu->price,
                'image_path' => $menu->image_path,
                'is_available' => $menu->is_available,
            ]
        ], 201);
    }

    /**
     * PUT /admin/menus/{id}
     */
    public function update(Request $request, $id)
    {
        $menu = Menus::find($id);

        if (!$menu) {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'image_path' => 'sometimes|string|max:255',
            'is_available' => 'sometimes|boolean'
        ]);

        $menu->update($validated);

        return response()->json([
            'message' => 'Menu Updated',
            'data' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'price' => (int) $menu->price,
                'image_path' => $menu->image_path,
                'is_available' => $menu->is_available,
            ]
        ], 200);
    }

    /**
     * PATCH /admin/menus/{id}/availability
     */
    public function updateAvailability(Request $request, $id)
    {
        $menu = Menus::find($id);

        if (!$menu) {
            return response()->json([
                'message' => 'Menu Not Found',
            ], 404);
        }

        $validated = $request->validate([
            'is_available' => 'required|boolean',
        ]);

        $menu->update([
            'is_available' => $validated['is_available']
        ]);

        return response()->json([
            'data' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'is_available' => $menu->is_available,
            ]
        ], 200);
    }

    /**
     * DELETE /admin/menus/{id}
     */
    public function destroy($id)
    {
        $menu = Menus::find($id);

        if(!$menu){
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }

        $menu->delete();

        // Clear Cache ketika menu dihapus
        // Cache::tags(['menus'])->flush();

        return response()->json([
            'message' => 'Menu Deleted Successfully'
        ], 200);
    }
}
