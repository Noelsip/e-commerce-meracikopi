<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->after('courier_order_id');
            $table->string('courier_waybill_id')->nullable()->after('tracking_number');
            $table->string('courier_company')->nullable()->after('courier_waybill_id');
            $table->string('courier_type')->nullable()->after('courier_company');
            $table->string('biteship_order_id')->nullable()->after('courier_type');
            $table->text('tracking_url')->nullable()->after('eta');
            $table->timestamp('picked_up_at')->nullable()->after('tracking_url');
            $table->timestamp('delivered_at')->nullable()->after('picked_up_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_number',
                'courier_waybill_id',
                'courier_company',
                'courier_type',
                'biteship_order_id',
                'tracking_url',
                'picked_up_at',
                'delivered_at',
            ]);
        });
    }
};
