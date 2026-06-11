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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel produk
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Nama varian (contoh: "Sunmist - FS", "2 Pcs Hemat")
            $table->string('name');
            
            // Penyesuaian harga jika varian lebih mahal/murah dari produk utama
            $table->decimal('price_modifier', 15, 2)->default(0);
            
            // Stok khusus untuk varian ini
            $table->integer('stock')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};