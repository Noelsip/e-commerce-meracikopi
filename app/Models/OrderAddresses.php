<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Orders;

class OrderAddresses extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'receiver_name',
        'phone',
        'full_address',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'rajaongkir_destination_id',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
