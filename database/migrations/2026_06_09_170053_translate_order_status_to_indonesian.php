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
        // Update default constraint (jika database mendukung) atau ubah langsung datanya
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'pending')->update(['status' => 'menunggu_pembayaran']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'paid')->update(['status' => 'sudah_dibayar']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'process')->update(['status' => 'dikemas']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'processing')->update(['status' => 'dikemas']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'shipping')->update(['status' => 'dikirim']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'shipped')->update(['status' => 'dikirim']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'completed')->update(['status' => 'selesai']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'cancelled')->update(['status' => 'dibatalkan']);
        
        // Ubah default column schema
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('menunggu_pembayaran')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'menunggu_pembayaran')->update(['status' => 'pending']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'sudah_dibayar')->update(['status' => 'paid']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'dikemas')->update(['status' => 'process']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'dikirim')->update(['status' => 'shipping']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'selesai')->update(['status' => 'completed']);
        \Illuminate\Support\Facades\DB::table('orders')->where('status', 'dibatalkan')->update(['status' => 'cancelled']);
        
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }
};
