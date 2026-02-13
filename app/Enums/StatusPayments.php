<?php

namespace App\Enums;

enum StatusPayments: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case PAID = 'paid';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Pembayaran',
            self::PROCESSING => 'Memproses Pembayaran',
            self::PAID => 'Sudah Dibayar',
            self::FAILED => 'Gagal',
            self::CANCELLED => 'Dibatalkan',
            self::REFUNDED => 'Refund',
            self::EXPIRED => 'Kadaluarsa',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => '#eab308',      // yellow
            self::PROCESSING => '#f97316',   // orange
            self::PAID => '#22c55e',         // green
            self::FAILED => '#ef4444',       // red
            self::CANCELLED => '#ef4444',    // red
            self::REFUNDED => '#8b5cf6',     // purple
            self::EXPIRED => '#6b7280',      // gray
        };
    }
}
