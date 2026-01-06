<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESS = 'process';
    case DONE = 'done';
    case CANCELLED = 'cancelled';
}
