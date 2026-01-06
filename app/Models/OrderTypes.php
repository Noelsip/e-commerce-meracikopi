<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTypes extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
