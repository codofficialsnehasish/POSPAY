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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('store_number')->nullable()->after('address');
            $table->string('store_location')->nullable()->after('store_number');;
            $table->string('gst_no')->nullable()->after('store_location');
            $table->unsignedBigInteger('admin_id')->nullable()->after('store_number');
            $table->unsignedBigInteger('vendor_id')->nullable()->after('admin_id');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('set null');

         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //

            $table->dropColumn('store_number');
            $table->dropColumn('store_location');
            $table->dropColumn('gst_no');
            $table->dropColumn('auth_id');
            $table->dropForeign('auth_id');

        });
    }
};
