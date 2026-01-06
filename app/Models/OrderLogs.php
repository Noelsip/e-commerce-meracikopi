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

    protected $cast = [
        'status' => OrderStatus::class
    ];

    public function orders()
    {
        return $this->belongTo(OrderLogs::class);
    }
}
