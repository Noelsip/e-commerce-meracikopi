<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tables;
use Illuminate\Http\Request;

class TableApiController extends Controller
{
    /**
     * GET /api/admin/tables - List semua meja
     */
    public function index(Request $request)
    {
        $query = Tables::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by is_active
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $tables = $query->latest()->get();

        return response()->json([
            'data' => $tables->map(fn($table) => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity,
                'status' => $table->status,
                'is_active' => $table->is_active,
                'created_at' => $table->created_at->toIso8601String(),
                'updated_at' => $table->updated_at->toIso8601String(),
            ])
        ], 200);
    }

    /**
     * POST /api/admin/tables - Tambah meja baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:50|unique:tables,table_number',
            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'sometimes|in:available,occupied,reserved',
            'is_active' => 'sometimes|boolean',
        ]);

        $table = Tables::create([
            'table_number' => $validated['table_number'],
            'capacity' => $validated['capacity'],
            'status' => $validated['status'] ?? 'available',
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'message' => 'Table created successfully',
            'data' => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity,
                'status' => $table->status,
                'is_active' => $table->is_active,
            ]
        ], 201);
    }

    /**
     * GET /api/admin/tables/{id} - Detail meja
     */
    public function show($id)
    {
        $table = Tables::find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity,
                'status' => $table->status,
                'is_active' => $table->is_active,
                'created_at' => $table->created_at->toIso8601String(),
                'updated_at' => $table->updated_at->toIso8601String(),
            ]
        ], 200);
    }

    /**
     * PUT /api/admin/tables/{id} - Update meja
     */
    public function update(Request $request, $id)
    {
        $table = Tables::find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        $validated = $request->validate([
            'table_number' => 'sometimes|string|max:50|unique:tables,table_number,' . $id,
            'capacity' => 'sometimes|integer|min:1|max:20',
            'status' => 'sometimes|in:available,occupied,reserved',
            'is_active' => 'sometimes|boolean',
        ]);

        $table->update($validated);

        return response()->json([
            'message' => 'Table updated successfully',
            'data' => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity,
                'status' => $table->status,
                'is_active' => $table->is_active,
            ]
        ], 200);
    }

    /**
     * DELETE /api/admin/tables/{id} - Hapus meja
     */
    public function destroy($id)
    {
        $table = Tables::find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        $table->delete();

        return response()->json([
            'message' => 'Table deleted successfully'
        ], 200);
    }

    /**
     * PATCH /api/admin/tables/{id}/status - Update status meja
     */
    public function updateStatus(Request $request, $id)
    {
        $table = Tables::find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:available,occupied,reserved',
        ]);

        $table->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Table status updated successfully',
            'data' => [
                'id' => $table->id,
                'status' => $table->status,
            ]
        ], 200);
    }
}
