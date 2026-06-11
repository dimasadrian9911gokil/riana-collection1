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
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom admin_fee dengan tipe decimal (15 digit total, 2 angka di belakang koma)
            // Default 0 agar tidak error jika data kosong
            $table->decimal('admin_fee', 15, 2)->default(0)->after('shipping_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus kolom jika migration dibatalkan (rollback)
            $table->dropColumn('admin_fee');
        });
    }
};