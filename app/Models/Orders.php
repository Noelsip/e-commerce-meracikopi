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
        'notes',
        'delivery_fee',
        'service_fee',
        'delivery_provider',
        'delivery_service',
        'delivery_meta',
        'discount_amount',
        'final_price',
    ];

    protected $casts = [
        'order_type' => OrderType::class,
        'status' => OrderStatus::class,
        'total_price' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'delivery_meta' => 'array',
    ];

    /**
     * Menghitung dan update final_price
     * 
     * Formula: final_price = total_price + delivery_fee + service_fee - discount_amount
     * 
     * @return void
     */
    public function calculateFinalPrice()
    {
        $this->final_price = $this->total_price + $this->delivery_fee + $this->service_fee - $this->discount_amount;
        $this->final_price = max(0, $this->final_price); // Tidak boleh negatif
    }

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
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    public function order_logs()
    {
        return $this->hasMany(OrderLogs::class, 'order_id');
    }

    public function order_addresses()
    {
        return $this->hasMany(OrderAddresses::class, 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'order_id');
    }

    public function deliveries()
    {
        return $this->hasMany(Deliveries::class, 'order_id');
    }
}
