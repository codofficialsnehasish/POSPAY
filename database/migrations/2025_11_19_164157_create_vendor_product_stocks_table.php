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
        Schema::create('vendor_product_stocks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('vendor_product_id');
            $table->unsignedBigInteger('variation_id');
            $table->unsignedBigInteger('option_id');

            $table->integer('stock')->default(0);
            $table->integer('low_stock_alert')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('vendor_product_id')
                ->references('id')
                ->on('vendor_products')
                ->onDelete('cascade');

            $table->foreign('variation_id')
                ->references('id')
                ->on('product_variations')
                ->onDelete('cascade');

            $table->foreign('option_id')
                ->references('id')
                ->on('product_variation_options')
                ->onDelete('cascade');

            // Avoid duplicate entries
            $table->unique(
                ['vendor_product_id', 'variation_id', 'option_id'],
                'vendor_variation_option_unique'
            );
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_product_stocks');
    }
};
