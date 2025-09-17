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
        Schema::create('seller_masters', function (Blueprint $table) {
            $table->id();
            $table->string('seller_name'); // Seller name
            $table->unsignedBigInteger('vendor_id')->nullable();  
            $table->string('email')->unique()->nullable(); // Seller email
            $table->string('phone', 15)->nullable(); // Seller phone number
            $table->string('address')->nullable(); // Seller address
            $table->string('city')->nullable(); // City
            $table->string('state')->nullable(); // State
            $table->string('country')->default('India'); // Country default
            $table->string('gst_number')->nullable(); // GST / Tax number
            $table->boolean('status')->default(1); // Active or inactive
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_masters');
    }
};
