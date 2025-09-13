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
        Schema::table('order_items', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('variation_id')->nullable()->after('product_id');
            $table->unsignedBigInteger('option_id')->nullable()->after('variation_id');
            $table->foreign('variation_id')->references('id')->on('product_variations')->onDelete('set null');
            $table->foreign('option_id')->references('id')->on('product_variation_options')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            //

            $table->dropColumn('variation_id');
            $table->dropColumn('option_id');
            $table->dropForeign('variation_id');
            $table->dropForeign('option_id');
        });
    }
};
