<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Orders;
use Illuminate\Database;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'note',
    ];

    protected $casts = [
        'status' => OrderStatus::class
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
