<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Change category column from ENUM to VARCHAR(50) to support all categories.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE menus MODIFY COLUMN category VARCHAR(50) DEFAULT 'drink'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE menus MODIFY COLUMN category ENUM('food','drink','coffee_beans') DEFAULT 'drink'");
    }
};
