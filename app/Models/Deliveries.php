<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\StatusDelivery;
use App\Models\Orders;

class Deliveries extends Model
{
    use HasFactory;

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

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
}
