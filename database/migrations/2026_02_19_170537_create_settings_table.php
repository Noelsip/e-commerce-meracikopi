<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('settings')->insert([
            ['key' => 'order_type_takeaway',  'value' => '1', 'label' => 'Takeaway',  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'order_type_dine_in',   'value' => '1', 'label' => 'Dine In',   'created_at' => now(), 'updated_at' => now()],
            ['key' => 'order_type_delivery',  'value' => '1', 'label' => 'Delivery',  'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
