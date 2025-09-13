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
            $table->decimal('mrp',10, 2)->nullable()->default(0.00)->after('price');
            $table->decimal('discount_rate',10, 2)->default(0.00)->after('mrp');
            $table->decimal('discount_amount',10, 2)->default(0.00)->after('discount_rate');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            //

            $table->dropColumn('mrp');
            $table->dropColumn('discount_rate');
            $table->dropColumn('discount_amount');


        });
    }
};
