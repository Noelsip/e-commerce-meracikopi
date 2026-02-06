<?php

namespace App\Enums;

enum OrderProcessStatus: string
{
    case PENDING = 'pending';           // Menunggu diproses
    case PROCESSING = 'processing';     // Sedang diproses
    case READY = 'ready';               // Siap diambil/diantar
    case ON_DELIVERY = 'on_delivery';   // Sedang diantar
    case COMPLETED = 'completed';       // Selesai
    case CANCELLED = 'cancelled';       // Dibatalkan

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu Diproses',
            self::PROCESSING => 'Sedang Diproses',
            self::READY => 'Siap',
            self::ON_DELIVERY => 'Sedang Diantar',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => '#eab308',      // yellow
            self::PROCESSING => '#3b82f6',   // blue
            self::READY => '#22c55e',        // green
            self::ON_DELIVERY => '#f97316',  // orange
            self::COMPLETED => '#22c55e',    // green
            self::CANCELLED => '#ef4444',    // red
        };
    }
}
