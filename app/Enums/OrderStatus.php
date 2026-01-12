<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING_PAYMENT = 'pending_payment';
    case PAID = 'paid';
    case PROCESSING = 'processing';
    case READY = 'ready';
    case ON_DELIVERY = 'on_delivery';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
