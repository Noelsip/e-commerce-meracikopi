<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'label'];

    /**
     * Get a setting value by key. Returns $default if not found.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = Cache::remember("setting_{$key}", 300, function () use ($key) {
            return static::where('key', $key)->first();
        });

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
    }

    /**
     * Check if an order type is enabled (value === '1').
     */
    public static function orderTypeEnabled(string $type): bool
    {
        return static::get("order_type_{$type}", '1') === '1';
    }

    /**
     * Get all enabled order types as array.
     */
    public static function enabledOrderTypes(): array
    {
        return array_filter([
            'takeaway' => static::orderTypeEnabled('takeaway'),
            'dine_in'  => static::orderTypeEnabled('dine_in'),
            'delivery' => static::orderTypeEnabled('delivery'),
        ]);
    }
}
