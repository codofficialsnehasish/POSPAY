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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id')->nullable();     // who paid
            $table->unsignedBigInteger('vendor_id')->nullable();   // who received (if applicable)

            $table->string('transaction_number')->unique();        // Unique transaction ID
            $table->decimal('amount', 10, 2);                      // Paid amount
            $table->enum('payment_method', ['UPI','Card','Cash'])->nullable();
            $table->enum('payment_status', ['Awaiting Payment','Payment Received','Failed','Refunded'])->default('Awaiting Payment');

            $table->string('gateway_transaction_id')->nullable();  // Razorpay/Stripe/UPI reference
            $table->string('currency', 10)->default('INR');

            $table->timestamp('paid_at')->nullable();              // When the payment was done
            $table->timestamps();

            // Relations
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
