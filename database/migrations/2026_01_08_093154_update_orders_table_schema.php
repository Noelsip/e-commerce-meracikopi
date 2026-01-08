<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Cek apakah kolom user_id belum ada, baru tambahkan
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->after('id')->nullable()->constrained('users')->nullOnDelete();
            }
            
            // Cek apakah kolom guest_token belum ada
            if (!Schema::hasColumn('orders', 'guest_token')) {
                $table->uuid('guest_token')->after('user_id')->nullable();
            }

            // Hapus foreign key lama (bungkus dalam try-catch jika perlu)
            try {
                $table->dropForeign(['table_id']);
            } catch (\Exception $e) {
                // Abaikan jika foreign key sudah dihapus sebelumnya
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('table_id')->nullable()->change()->constrained('tables')->nullOnDelete();
            $table->string('customer_name')->nullable()->change();
            $table->string('customer_phone')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Balikkan perubahan (opsional tapi disarankan)
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'guest_token']);
            
            $table->dropForeign(['table_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('table_id')->nullable(false)->change()->constrained('tables')->cascadeOnDelete();
            $table->string('customer_name')->nullable(false)->change();
            $table->string('customer_phone')->nullable(false)->change();
        });
    }
};