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
        Schema::table('product_variation_options', function (Blueprint $table) {
            //
            $table->decimal('mrp',10, 2)->nullable()->after('color');
            $table->decimal('discount_amount',10, 2)->nullable()->after('discount_rate');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variation_options', function (Blueprint $table) {
            //
            $table->dropColumn('mrp');
            $table->dropColumn('discount_amount');
        });
    }
};
