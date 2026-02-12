<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if category column already exists
        if (Schema::hasColumn('menus', 'category')) {
            // Change ENUM to VARCHAR(50) to support all categories
            DB::statement("ALTER TABLE menus MODIFY COLUMN category VARCHAR(50) DEFAULT 'drink'");
        } else {
            // Add category column as VARCHAR(50)
            Schema::table('menus', function (Blueprint $table) {
                $table->string('category', 50)->default('drink')->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
