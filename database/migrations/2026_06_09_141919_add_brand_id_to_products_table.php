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
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
        });

        // Migrasi data
        $products = \App\Models\Product::all();
        foreach($products as $product) {
            if (!empty($product->brand)) {
                $brand = \App\Models\Brand::firstOrCreate(
                    ['name' => $product->brand],
                    ['slug' => \Illuminate\Support\Str::slug($product->brand)]
                );
                $product->brand_id = $brand->id;
                $product->save();
            }
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('brand');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand')->nullable();
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });
    }
};
