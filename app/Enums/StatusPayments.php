<?php

namespace App\Enums;

enum StatusPayments : string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    case EXPIRED = 'expired';
    case REFUNDED = 'refunded';
}
