<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Menambahkan foreign key ke tabel orders
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // Menambahkan foreign key ke tabel products (PENTING untuk relasi stabil)
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            $table->string('product_name');
            $table->decimal('price', 15, 2);
            $table->integer('qty');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};