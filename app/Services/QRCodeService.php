<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR Code untuk table
     *
     * @param string $token - Token unik untuk table
     * @param string $tableNumber - Nomor meja
     * @return string - Path ke file QR code
     */
    public function generateTableQRCode(string $token, string $tableNumber): string
    {
        // Generate URL untuk scan
        $scanUrl = route('qr.scan', ['table' => $token]);

        // Create QR Code (endroid/qr-code v6.x)
        $qrCode = new QrCode(
            data: $scanUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 20
        );

        // Write to SVG (tidak memerlukan GD extension)
        $writer = new SvgWriter();
        $result = $writer->write($qrCode);

        // Path untuk menyimpan QR code
        $fileName = "table-qr-{$token}.svg";
        $filePath = "qrcodes/tables/{$fileName}";

        // Simpan ke storage (public disk)
        Storage::disk('public')->put($filePath, $result->getString());

        return $filePath;
    }

    /**
     * Hapus QR Code file
     *
     * @param string|null $path - Path ke file QR code
     * @return bool
     */
    public function deleteQRCode(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }
}
