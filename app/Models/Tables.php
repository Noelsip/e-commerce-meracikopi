<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tables extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'qr_code_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
