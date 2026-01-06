<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\OrderAddresses;
use App\Models\OrderItems;
use App\Models\OrderLogs;
use App\Models\Payments;
use App\Models\Deliveries;
use App\Models\User;
use App\Models\Tables;

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

    public function user()
    {
        return $this->belongTo(User::class);
    }

    public function tables()
    {
        return $this->belongTo(Tables::class);
    }

    public function order_items()
    {
        return $this->hashMany(OrderItems::class);
    }

    public function order_logs()
    {
        return $this->hashMany(OrderLogs::class);
    }

    public function order_addresses()
    {
        return $this->hashMany(OrderAddresses::class);
    }

    public function payments()
    {
        return $this->hashMany(Payments::class);
    }

    public function deliveries()
    {
        return $this->hashMany(Deliveries::class);
    }
}
