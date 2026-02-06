<?php

namespace App\Http\Controllers;

use App\Models\Tables;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    /**
     * Scan QR Code dan redirect ke catalog dengan meja terscan
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function scan(Request $request)
    {
        $token = $request->query('table');

        // Handle clear table session
        if ($token === 'clear') {
            session()->forget(['table_id', 'table_number']);
            return redirect()->route('catalogs.index')
                ->with('success', 'Informasi meja telah dihapus.');
        }

        // Cari meja berdasarkan token
        $table = Tables::where('qr_token', $token)->first();

        if (!$table) {
            return redirect()->route('catalogs.index')
                ->with('error', 'QR Code tidak valid atau meja tidak ditemukan.');
        }

        // Cek apakah meja aktif
        if (!$table->is_active) {
            return redirect()->route('catalogs.index')
                ->with('error', 'Meja ini sedang tidak aktif.');
        }

        // Simpan informasi meja ke session
        session([
            'table_id' => $table->id,
            'table_number' => $table->table_number,
        ]);

        // Redirect ke halaman catalog
        return redirect()->route('catalogs.index')
            ->with('success', "Selamat datang di Meja {$table->table_number}!");
    }
}
