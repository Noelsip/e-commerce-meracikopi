<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Menus;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Halaman katalog - menampilkan semua menu
     */
    public function index(Request $request)
    {
        $query = Menus::whereNull('deleted_at')->where('is_available', true);

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $menus = $query->orderBy('name')->get();

        // Get table info from session
        $tableInfo = null;
        if (session()->has('table_id')) {
            $tableInfo = [
                'id' => session('table_id'),
                'number' => session('table_number'),
            ];
        }

        return view('pages.guest.catalogs.index', compact('menus', 'tableInfo'));
    }

    /**
     * Halaman detail menu
     */
    public function show($id)
    {
        $menu = Menus::whereNull('deleted_at')->find($id);

        if (!$menu) {
            abort(404);
        }

        // Ambil menu lain untuk rekomendasi
        $relatedMenus = Menus::whereNull('deleted_at')
            ->where('is_available', true)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('pages.guest.catalogs.show', compact('menu', 'relatedMenus'));
    }
}
