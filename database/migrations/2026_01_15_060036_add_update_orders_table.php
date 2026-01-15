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
            $table->text('notes')->nullable()->after('customer_phone');
            $table->string('status')->default(OrderStatus::CREATED->value)->change();
            $table->string('order_type')->default(OrderType::DINE_IN->value)->change();
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('total_price');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('delivery_fee');
            $table->decimal('final_price', 10, 2)->default(0)->after('discount_amount');

            // index
            $table->index('status');
            $table->index('order_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['status']);
            $table->dropIndex(['order_type']);
            $table->dropIndex(['created_at']);
            
            // Drop columns
            $table->dropColumn([
                'notes',
                'delivery_fee',
                'discount_amount',
                'final_price'
            ]);
        });
    }
};
