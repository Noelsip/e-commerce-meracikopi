<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Tables;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * GET /api/customer/tables - List meja yang tersedia
     */
    public function index(Request $request)
    {
        $query = Tables::where('is_active', true);

        // Filter by status (default: available only)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'available');
        }

        // Filter by minimum capacity
        if ($request->has('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }

        $tables = $query->orderBy('table_number')->get();

        return response()->json([
            'data' => $tables->map(fn($table) => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity,
                'status' => $table->status,
            ])
        ], 200);
    }

    /**
     * GET /api/customer/tables/{id} - Detail meja
     */
    public function show($id)
    {
        $table = Tables::where('is_active', true)->find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity,
                'status' => $table->status,
            ]
        ], 200);
    }
}
