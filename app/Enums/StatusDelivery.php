<?php

namespace App\Enums;

enum StatusDelivery: string
{
    case REQUESTED = 'requested';
    case CONFIRMED = 'confirmed';
    case ASSIGNED = 'assigned';
    case PICKING_UP = 'picking_up';
    case PICKED = 'picked';
    case ON_DELIVERY = 'on_delivery';
    case DROPPING_OFF = 'dropping_off';
    case DELIVERED = 'delivered';
    case RETURN_IN_TRANSIT = 'return_in_transit';
    case RETURNED = 'returned';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';

    /**
     * Map Biteship tracking status ke StatusDelivery enum
     */
    public static function fromBiteshipStatus(string $status): self
    {
        return match ($status) {
            'confirmed' => self::CONFIRMED,
            'allocated' => self::ASSIGNED,
            'picking_up' => self::PICKING_UP,
            'picked' => self::PICKED,
            'dropping_off' => self::DROPPING_OFF,
            'delivered' => self::DELIVERED,
            'on_hold' => self::ON_DELIVERY,
            'return_in_transit' => self::RETURN_IN_TRANSIT,
            'returned' => self::RETURNED,
            'cancelled' => self::CANCELLED,
            'rejected' => self::REJECTED,
            'courier_not_found' => self::CANCELLED,
            'disposed' => self::CANCELLED,
            default => self::REQUESTED,
        };
    }
}
