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
        Schema::table('addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('addresses', 'recipient_name')) {
                $table->string('recipient_name')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'district')) {
                $table->string('district')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'postal_code')) {
                $table->string('postal_code')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'is_default')) {
                $table->boolean('is_default')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn([
                'recipient_name', 
                'phone', 
                'address', 
                'district', 
                'city', 
                'postal_code', 
                'is_default'
            ]);
        });
    }
};