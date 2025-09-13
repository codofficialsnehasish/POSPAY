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
        Schema::create('product_variation_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variation_id');
            $table->string('name')->nullable();
            $table->integer('quantity');
            $table->string('color')->nullable();
            $table->decimal('price',10, 2)->nullable();
            $table->string('discount_rate',10, 2)->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('use_default_price')->default(0);
            $table->tinyInteger('no_discount')->default(0);
            $table->timestamps();
            $table->foreign('variation_id')->references('id')->on('product_variations')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variation_options');
    }
};
