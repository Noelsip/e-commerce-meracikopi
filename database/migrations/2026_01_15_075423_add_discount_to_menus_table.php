<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom diskon ke tabel menus.
     * Diskon bisa berupa:
     * - discount_percentage: Diskon dalam persen (0-100)
     * - discount_price: Diskon dalam nominal rupiah
     * 
     * Jika keduanya diisi, discount_price yang digunakan (prioritas lebih tinggi)
     * Jika tidak ada diskon, kedua kolom bernilai 0 atau null
     * 
     * Contoh:
     * - Menu A: price=50000, discount_percentage=10 → final=45000
     * - Menu B: price=50000, discount_price=5000 → final=45000
     * - Menu C: price=50000, keduanya 0 → final=50000
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Add category column only if it doesn't exist
            if (!Schema::hasColumn('menus', 'category')) {
                $table->string('category')->nullable()->after('name');
            }

            // Diskon dalam persen (0-100) - only if it doesn't exist
            if (!Schema::hasColumn('menus', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->default(0)->after('price');
            }

            // Diskon dalam nominal rupiah - only if it doesn't exist
            if (!Schema::hasColumn('menus', 'discount_price')) {
                $table->decimal('discount_price', 10, 2)->default(0)->after('discount_percentage');
            }

            // Add soft deletes - only if it doesn't exist
            if (!Schema::hasColumn('menus', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Menghapus kolom diskon jika migration di-rollback
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Drop indexes terlebih dahulu
            $table->dropIndex(['discount_percentage']);
            $table->dropIndex(['discount_price']);

            // Kemudian drop kolom
            $table->dropColumn([
                'category',
                'discount_percentage',
                'discount_price',
                'deleted_at'
            ]);
        });
    }
};
