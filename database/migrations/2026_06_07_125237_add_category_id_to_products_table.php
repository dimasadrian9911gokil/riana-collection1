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
        Schema::table('products', function (Blueprint $table) {
            // Menambahkan kolom category_id sebagai foreign key
            // Nullable agar produk bisa dibuat tanpa kategori jika diperlukan
            $table->unsignedBigInteger('category_id')->nullable()->after('id');
            
            // Menambahkan foreign key constraint
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null'); // Jika kategori dihapus, category_id pada produk jadi null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu sebelum kolomnya
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};