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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            // Menambahkan foreign key ke tabel products
            // onDelete('cascade') memastikan jika produk dihapus, fotonya ikut terhapus
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Menyimpan path atau lokasi file gambar di storage
            $table->string('image_path'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};