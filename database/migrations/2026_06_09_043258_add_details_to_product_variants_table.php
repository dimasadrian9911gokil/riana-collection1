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
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'image_path')) {
                $table->string('image_path')->nullable()->after('name');
            }
            if (!Schema::hasColumn('product_variants', 'additional_price')) {
                $table->decimal('additional_price', 12, 2)->default(0)->after('name');
            }
            if (!Schema::hasColumn('product_variants', 'description')) {
                $table->text('description')->nullable()->after('stock');
            }
            if (!Schema::hasColumn('product_variants', 'how_to_use')) {
                $table->text('how_to_use')->nullable()->after('description');
            }
            if (!Schema::hasColumn('product_variants', 'ingredients')) {
                $table->text('ingredients')->nullable()->after('how_to_use');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'additional_price', 'description', 'how_to_use', 'ingredients']);
        });
    }
};
