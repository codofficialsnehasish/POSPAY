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
            //
            $table->unsignedBigInteger('hsncode_id')->nullable()->after('brand_id');
            $table->unsignedBigInteger('vendor_id')->nullable()->after('hsncode_id');
            $table->foreign('hsncode_id')->references('id')->on('hsncodes')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropColumn('hsncode_id');
            $table->dropForeign('hsncode_id');
        });
    }
};
