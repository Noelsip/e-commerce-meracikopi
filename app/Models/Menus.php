<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderItems;

class Menus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menus';
    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'image_path',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public const CATEGORIES = [
        'food' => 'Food',
        'drink' => 'Drink',
        'coffee_beans' => 'Coffee Beans',
        'kopi_botolan' => 'Kopi Botolan',
        'sachet-drip' => 'Sachet Drip',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }
}
