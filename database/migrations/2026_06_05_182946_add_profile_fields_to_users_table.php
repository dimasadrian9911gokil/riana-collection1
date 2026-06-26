<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('phone')->nullable();

            $table->date('birth_date')->nullable();

            $table->enum('gender', [
                'Laki-laki',
                'Perempuan'
            ])->nullable();

            $table->boolean('agree_integrity')
                  ->default(false);

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'phone',
                'birth_date',
                'gender',
                'agree_integrity'
            ]);

        });
    }
};