<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;
use App\Enums\OrderType;

class Orders extends Model
{
    protected $fillable = [
        'order_type',
        'table_id',
        'customer_name',
        'customer_phone',
        'status',
        'total_price',
    ];

    protected $casts = [
        'order_type' => OrderType::class,
        'status' => OrderStatus::class,
        'total_price' => 'decimal:2',
    ];
}
