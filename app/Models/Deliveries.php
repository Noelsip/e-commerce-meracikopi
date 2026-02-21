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
        'tracking_number',
        'courier_waybill_id',
        'courier_company',
        'courier_type',
        'biteship_order_id',
        'status',
        'price',
        'eta',
        'tracking_url',
        'picked_up_at',
        'delivered_at',
        'raw_response',
    ];

    protected $casts = [
        'status' => StatusDelivery::class,
        'price' => 'decimal:2',
        'raw_response' => 'array',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
}
