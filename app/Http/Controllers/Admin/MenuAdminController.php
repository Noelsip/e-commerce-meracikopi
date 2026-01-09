<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuAdminController extends Controller
{
    public function index()
    {
        $menus = Menus::latest()->paginate(10);
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->only(['name', 'description', 'price']);
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $data['image_path'] = 'storage/' . $path; // Adjust based on your storage link setup
        }

        Menus::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menus $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menus $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->only(['name', 'description', 'price']);
        $data['is_available'] = $request->has('is_available');

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

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menus $menu)
    {
        if ($menu->image_path) {
            $oldPath = str_replace('storage/', '', $menu->image_path);
            Storage::disk('public')->delete($oldPath);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }
}
