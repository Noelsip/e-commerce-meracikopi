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
        'discount_percentage',
        'discount_price',
        'image_path',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Menghitung harga final setelah diskon
     * 
     * Priority:
     * 1. discount_price (nominal) - jika ada
     * 2. discount_percentage (persen) - jika discount_price = 0
     * 3. Harga normal - jika tidak ada diskon
     * 
     * @return float
     */
    public function getFinalPriceAttribute()
    {
        // Jika ada diskon nominal, gunakan itu
        if ($this->discount_price > 0) {
            return max(0, $this->price - $this->discount_price);
        }
        
        // Jika ada diskon persen, hitung dari persen
        if ($this->discount_percentage > 0) {
            $discountAmount = ($this->price * $this->discount_percentage) / 100;
            return max(0, $this->price - $discountAmount);
        }
        
        // Tidak ada diskon, return harga normal
        return $this->price;
    }

    /**
     * Menghitung jumlah diskon dalam rupiah
     * 
     * @return float
     */
    public function getDiscountAmountAttribute()
    {
        return $this->price - $this->final_price;
    }

    /**
     * Check apakah menu sedang diskon
     * 
     * @return bool
     */
    public function hasDiscount()
    {
        return $this->discount_price > 0 || $this->discount_percentage > 0;
    }

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
