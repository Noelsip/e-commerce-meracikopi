<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivery_provider')) {
                $table->string('delivery_provider')->nullable()->after('delivery_fee');
            }

            if (!Schema::hasColumn('orders', 'delivery_service')) {
                $table->string('delivery_service')->nullable()->after('delivery_provider');
            }

            if (!Schema::hasColumn('orders', 'delivery_meta')) {
                $table->json('delivery_meta')->nullable()->after('delivery_service');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $drop = [];

            foreach (['delivery_meta', 'delivery_service', 'delivery_provider'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $drop[] = $column;
                }
            }

            if ($drop !== []) {
                $table->dropColumn($drop);
            }
        });
    }
};

