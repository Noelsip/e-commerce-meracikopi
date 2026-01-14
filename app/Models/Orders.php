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
        'user_id',
        'guest_token',
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
        return $this->belongsTo(User::class);
    }

    public function tables()
    {
        return $this->belongsTo(Tables::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function order_logs()
    {
        return $this->hasMany(OrderLogs::class);
    }

    public function order_addresses()
    {
        return $this->hasMany(OrderAddresses::class);
    }

    public function payments()
    {
        return $this->hasMany(Payments::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Deliveries::class);
    }
}
