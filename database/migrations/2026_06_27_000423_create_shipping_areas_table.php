<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('cost', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('shipping_areas')->insert([
            ['name' => 'Lokal_BengkalisKota', 'cost' => 5000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lokal_AirPutih', 'cost' => 5000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lokal_Kelapapati', 'cost' => 7000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lokal_Wonosari', 'cost' => 8000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lokal_Senggoro', 'cost' => 5000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lokal_Pedekik', 'cost' => 10000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lokal_Bantan', 'cost' => 15000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lokal_Selatbaru', 'cost' => 15000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Luar_Bengkalis', 'cost' => 30000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_areas');
    }
};
