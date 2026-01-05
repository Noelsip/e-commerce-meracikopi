<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\StatusDelivery;

class Deliveries extends Model
{
    protected $fillable = [
        'order_id',
        'courier_name',
        'courier_order_id',
        'status',
        'price',
        'eta',
        'raw_response',
    ];

    protected $casts = [
        'status' => StatusDelivery::class,
        'price' => 'decimal:2',
        'raw_response' => 'array',
    ];
}
