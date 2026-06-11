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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            
            // Kolom Informasi Detail
            $table->text('description')->nullable();  // Deskripsi produk utama
            $table->text('how_to_use')->nullable();   // Instruksi penggunaan
            $table->text('ingredients')->nullable();  // Komposisi / Kandungan
            
            // Kolom Harga
            // Menggunakan decimal(12,0) agar mendukung angka hingga milyaran tanpa desimal
            $table->decimal('price', 12, 0);          // Harga setelah diskon
            $table->decimal('original_price', 12, 0)->nullable(); // Harga asli (untuk coret)
            
            // Stok dan Atribut
            $table->integer('stock')->default(0); 
            $table->string('image')->nullable();      // Path gambar produk
            $table->string('variant')->nullable();    // Varian ukuran (misal: 20ml, 50ml)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};