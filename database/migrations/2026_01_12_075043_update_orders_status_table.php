<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderStatus;
use App\Enums\OrderType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default(OrderStatus::PENDING_PAYMENT->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default(OrderType::STANDARD->value)->change();
        });
    }
};
