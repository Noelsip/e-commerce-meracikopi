<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tables;
use Illuminate\Http\Request;
use App\Services\QRCodeService;
use Illuminate\Support\Str;

class TableAdminController extends Controller
{
    public function index()
    {
        $tables = Tables::latest()->paginate(12);
        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        $statuses = Tables::getStatuses();
        return view('admin.tables.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|max:50|unique:tables,table_number',
            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'required|in:available,occupied,reserved',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['table_number', 'capacity', 'status']);
        $data['is_active'] = $request->has('is_active');
        $data['qr_token'] = Str::random(32);

        $table = Tables::create($data);

        // Generate QR Code
        $qrService = new QRCodeService();
        $qrCodePath = $qrService->generateTableQRCode($table->qr_token, $table->table_number);
        $table->update(['qr_code_path' => $qrCodePath]);

        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(Tables $table)
    {
        $statuses = Tables::getStatuses();
        return view('admin.tables.edit', compact('table', 'statuses'));
    }

    public function update(Request $request, Tables $table)
    {
        $request->validate([
            'table_number' => 'required|string|max:50|unique:tables,table_number,' . $table->id,
            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'required|in:available,occupied,reserved',
            'is_active' => 'boolean',
        ]);

        $oldTableNumber = $table->table_number;
        $table->update($data);

        // Regenerate QR if table number changed (to update the label)
        if ($oldTableNumber !== $data['table_number'] && $table->qr_token) {
            $qrService = new QRCodeService();
            // Delete old QR if exists
            if ($table->qr_code_path) {
                $qrService->deleteQRCode($table->qr_code_path);
            }
            $qrCodePath = $qrService->generateTableQRCode($table->qr_token, $table->table_number);
            $table->update(['qr_code_path' => $qrCodePath]);
        }

        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Tables $table)
    {
        $table->delete();
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil dihapus.');
    }

    // Quick status update via AJAX
    public function updateStatus(Request $request, Tables $table)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,reserved',
        ]);

        $table->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status meja berhasil diubah.']);
    }
}
