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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBiginteger('vendor_id')->nullable();
            $table->enum('order_type', ['attendee', 'customer']);
            $table->string('coupone_code');
            $table->decimal('coupone_discount', 10, 2)->default(0.00)->nullable();
            $table->decimal('price_subtotal', 10, 2)->default(0.00)->nullable();
            $table->decimal('price_gst', 10, 2)->default(0.00)->nullable();
            $table->decimal('price_shipping', 10, 2)->default(0.00)->nullable();
            $table->decimal('discounted_price', 10, 2)->default(0.00)->nullable();
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->enum('order_status', ['Order Placed','Order Confirmed','Preparing', 'Ready for Pickup', 'Out For Delevery', 'Delivered', 'Rejected','Order Pending'])->default('Order Placed');
            $table->enum('payment_method', ['Cash On Delevery', 'UPI','Card','Cash']);
            $table->enum('payment_status', ['Awaiting Payment','Payment Received'])->default('Awaiting Payment');
            $table->tinyInteger('status')->default(0);
            $table->text('cancel_cause')->nullable();
            $table->text('formatted_address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_purson')->nullable();
            $table->string('delevery_note')->nullable();
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
