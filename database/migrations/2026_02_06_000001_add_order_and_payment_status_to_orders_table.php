<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderProcessStatus;
use App\Enums\StatusPayments;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Status Pesanan - diinput manual oleh admin
            $table->string('order_status')->default(OrderProcessStatus::PENDING->value)->after('status');
            // Status Pembayaran - otomatis dari payment gateway
            $table->string('payment_status')->default(StatusPayments::PENDING->value)->after('order_status');
        });

        // Migrate existing data: map old status to new columns
        \DB::statement("
            UPDATE orders SET 
                payment_status = CASE 
                    WHEN status IN ('pending_payment', 'created') THEN 'pending'
                    WHEN status = 'paid' THEN 'paid'
                    WHEN status = 'cancelled' THEN 'cancelled'
                    ELSE 'pending'
                END,
                order_status = CASE 
                    WHEN status = 'processing' THEN 'processing'
                    WHEN status = 'ready' THEN 'ready'
                    WHEN status = 'on_delivery' THEN 'on_delivery'
                    WHEN status = 'completed' THEN 'completed'
                    WHEN status = 'cancelled' THEN 'cancelled'
                    ELSE 'pending'
                END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_status', 'payment_status']);
        });
    }
};
