<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Menus::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $menus = $query->paginate(10)->withQueryString();
        $categories = Menus::CATEGORIES;

        return view('admin.menus.index', compact('menus', 'categories'));
    }

    public function create()
    {
        $categories = Menus::CATEGORIES;
        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:food,drink,coffee_beans,bottled_coffee,sachet_drip',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->only(['name', 'category', 'description', 'price', 'discount_percentage', 'discount_price']);
        $data['is_available'] = $request->has('is_available');

        // Set default 0 jika tidak ada diskon
        $data['discount_percentage'] = $request->input('discount_percentage', 0);
        $data['discount_price'] = $request->input('discount_price', 0);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $data['image_path'] = 'storage/' . $path;
        } else {
            $data['image_path'] = '';
        }

        $menu = Menus::create($data);

        return redirect()->route('admin.menus.index')->with('success', "Menu '{$menu->name}' berhasil ditambahkan.");
    }

    public function edit(Menus $menu)
    {
        $categories = Menus::CATEGORIES;
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menus $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:food,drink,coffee_beans,bottled_coffee,sachet_drip',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->only(['name', 'category', 'description', 'price', 'discount_percentage', 'discount_price']);
        $data['is_available'] = $request->has('is_available');

        // Set default 0 jika tidak ada diskon
        $data['discount_percentage'] = $request->input('discount_percentage', 0);
        $data['discount_price'] = $request->input('discount_price', 0);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image_path) {
                $oldPath = str_replace('storage/', '', $menu->image_path);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('menus', 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', "Menu '{$menu->name}' berhasil diperbarui.");
    }

    public function destroy(Menus $menu)
    {
        $menuName = $menu->name; // Store name before deletion

        // Check if menu has been ordered
        $hasOrders = $menu->orderItems()->exists();

        if ($hasOrders) {
            return redirect()->route('admin.menus.index')
                ->with('error', "Menu '{$menuName}' tidak dapat dihapus karena sudah pernah dipesan. Anda bisa menyembunyikan menu ini dengan mengubah status availability.");
        }

        // Check if menu is in any active cart
        $inCart = $menu->cartItems()->exists();

        if ($inCart) {
            return redirect()->route('admin.menus.index')
                ->with('error', "Menu '{$menuName}' tidak dapat dihapus karena masih ada di cart customer. Silakan coba lagi nanti atau sembunyikan menu.");
        }

        try {
            if ($menu->image_path && $menu->image_path !== '') {
                $oldPath = str_replace('storage/', '', $menu->image_path);
                Storage::disk('public')->delete($oldPath);
            }
        } catch (\Exception $e) {
            // Ignore storage errors (file may not exist on server)
        }

        try {
            $menu->delete();
            return redirect()->route('admin.menus.index')->with('success', "Menu '{$menuName}' berhasil dihapus.");
        } catch (\Exception $e) {
            \Log::error('Failed to delete menu: ' . $e->getMessage(), [
                'menu_id' => $menu->id,
                'menu_name' => $menuName,
            ]);

            return redirect()->route('admin.menus.index')
                ->with('error', "Gagal menghapus menu '{$menuName}'. Menu mungkin masih digunakan oleh sistem.");
        }
    }

    public function toggleVisibility(Menus $menu)
    {
        $menu->is_available = !$menu->is_available;
        $menu->save();

        $status = $menu->is_available ? 'ditampilkan' : 'disembunyikan';
        return back()->with('success', "Menu '{$menu->name}' berhasil {$status}.");
    }
}
