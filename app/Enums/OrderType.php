<?php

namespace App\Enums;

enum OrderType : string
{
    case DINE_IN = 'dine_in';
    case TAKE_AWAY = 'take_away';
}
