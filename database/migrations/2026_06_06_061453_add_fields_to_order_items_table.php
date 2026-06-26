<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            $table->unsignedBigInteger('order_id')->nullable();

            $table->string('product_name')->nullable();

            $table->integer('price')->default(0);

            $table->integer('qty')->default(1);

            $table->integer('subtotal')->default(0);

        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            $table->dropColumn([
                'order_id',
                'product_name',
                'price',
                'qty',
                'subtotal'
            ]);

        });
    }
};