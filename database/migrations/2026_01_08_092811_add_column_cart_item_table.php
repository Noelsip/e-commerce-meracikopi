<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // 1. Hapus foreign key yang lama (biasanya formatnya: tabel_kolom_foreign)
            $table->dropForeign(['cart_id']);
            $table->dropForeign(['menu_id']);

            // 2. Definisi ulang dengan nama tabel eksplisit
            $table->foreignId('cart_id')->change()->constrained('carts')->cascadeOnDelete();
            $table->foreignId('menu_id')->change()->constrained('menus')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
            $table->dropForeign(['menu_id']);
            
            // Kembalikan ke kondisi semula tanpa nama tabel eksplisit
            $table->foreignId('cart_id')->change()->constrained()->cascadeOnDelete();
            $table->foreignId('menu_id')->change()->constrained()->cascadeOnDelete();
        });
    }
};