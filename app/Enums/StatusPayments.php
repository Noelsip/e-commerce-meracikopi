<?php

namespace App\Enums;

enum StatusDelivery: string
{
    case REQUESTED = 'requested';
    case ASSIGNED = 'assigned';
    case ON_DELIVERY = 'on_delivery';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
}
