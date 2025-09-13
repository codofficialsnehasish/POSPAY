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
        Schema::table('orders', function (Blueprint $table) {
            //

            $table->decimal('gst_amount', 10, 2)->nullable()->default(0.00)->after('total_amount');
            $table->decimal('cgst_amount', 5, 2)->nullable()->default(0.00)->after('gst_amount');
            $table->decimal('sgst_amount', 5, 2)->nullable()->default(0.00)->after('cgst_amount');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->dropColumn('gst_amount');
            $table->dropColumn('cgst_amount');
            $table->dropColumn('sgst_amount');
        });
    }
};
