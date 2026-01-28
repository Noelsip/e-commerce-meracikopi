<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('order_addresses', 'province')) {
                $table->string('province')->nullable()->after('city');
            }

            if (!Schema::hasColumn('order_addresses', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('postal_code');
            }

            if (!Schema::hasColumn('order_addresses', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }

            if (!Schema::hasColumn('order_addresses', 'rajaongkir_destination_id')) {
                $table->unsignedBigInteger('rajaongkir_destination_id')->nullable()->after('longitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_addresses', function (Blueprint $table) {
            $drop = [];

            foreach (['rajaongkir_destination_id', 'longitude', 'latitude', 'province'] as $column) {
                if (Schema::hasColumn('order_addresses', $column)) {
                    $drop[] = $column;
                }
            }

            if ($drop !== []) {
                $table->dropColumn($drop);
            }
        });
    }
};

