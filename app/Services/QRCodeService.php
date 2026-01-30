<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Writer\PngWriter;
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

        // Build QR Code
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($scanUrl)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(400)
            ->margin(20)
            ->labelText("Meja {$tableNumber}")
            ->labelFont(new OpenSans(20))
            ->build();

        // Path untuk menyimpan QR code
        $fileName = "table-qr-{$token}.png";
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
