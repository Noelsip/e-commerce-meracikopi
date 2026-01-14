<?php

namespace App\Enums;

enum StatusPayments: string
{
    case REQUESTED = 'requested';
    case ASSIGNED = 'assigned';
    case ON_DELIVERY = 'on_delivery';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
}
