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
        Schema::table('order_seats', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('seat_number_id')->nullable()->after('order_id');
            $table->foreign('seat_number_id')->references('id')->on('seat_numbers')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_seats', function (Blueprint $table) {
            //
        });
    }
};
