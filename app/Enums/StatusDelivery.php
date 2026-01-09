<?php

namespace App\Enums;

enum StatusDelivery : string
{
    case REQUEST = 'request';
    case ASSIGNED = 'assigned';
    case PICKED = 'picked';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
}
