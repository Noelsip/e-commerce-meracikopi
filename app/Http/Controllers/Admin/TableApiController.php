<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tables;
use Illuminate\Http\Request;
use App\Services\QRCodeService;
use Illuminate\Support\Str;

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

        // Generate unique token untuk QR code
        $qrToken = Str::random(32);

        $table = Tables::create([
            'table_number' => $validated['table_number'],
            'capacity' => $validated['capacity'],
            'status' => $validated['status'] ?? 'available',
            'is_active' => $validated['is_active'] ?? true,
            'qr_token' => $qrToken,
        ]);

        // Generate QR Code
        $qrService = new QRCodeService();
        $qrCodePath = $qrService->generateTableQRCode($qrToken, $table->table_number);
        $table->update(['qr_code_path' => $qrCodePath]);

        return response()->json([
            'message' => 'Table created successfully',
            'data' => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity,
                'status' => $table->status,
                'is_active' => $table->is_active,
                'qr_token' => $table->qr_token,
                'qr_code_url' => $qrCodePath ? asset('storage/' . $qrCodePath) : null,
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

    /**
     * POST /api/admin/tables/{id}/generate-qr - Generate QR code untuk meja
     */
    public function generateQRCode($id)
    {
        $table = Tables::find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        // Jika belum ada token, buat token baru
        if (!$table->qr_token) {
            $table->update(['qr_token' => Str::random(32)]);
        }

        // Hapus QR code lama jika ada
        if ($table->qr_code_path) {
            $qrService = new QRCodeService();
            $qrService->deleteQRCode($table->qr_code_path);
        }

        // Generate QR Code baru
        $qrService = new QRCodeService();
        $qrCodePath = $qrService->generateTableQRCode($table->qr_token, $table->table_number);
        $table->update(['qr_code_path' => $qrCodePath]);

        return response()->json([
            'message' => 'QR Code generated successfully',
            'data' => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'qr_code_url' => asset('storage/' . $qrCodePath),
            ]
        ], 200);
    }

    /**
     * POST /api/admin/tables/{id}/regenerate-qr - Regenerate QR code (dengan token baru)
     */
    public function regenerateQRCode($id)
    {
        $table = Tables::find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        // Hapus QR code lama
        if ($table->qr_code_path) {
            $qrService = new QRCodeService();
            $qrService->deleteQRCode($table->qr_code_path);
        }

        // Generate token baru
        $newToken = Str::random(32);
        $table->update(['qr_token' => $newToken]);

        // Generate QR Code dengan token baru
        $qrService = new QRCodeService();
        $qrCodePath = $qrService->generateTableQRCode($newToken, $table->table_number);
        $table->update(['qr_code_path' => $qrCodePath]);

        return response()->json([
            'message' => 'QR Code regenerated successfully',
            'data' => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'qr_code_url' => asset('storage/' . $qrCodePath),
            ]
        ], 200);
    }
}
