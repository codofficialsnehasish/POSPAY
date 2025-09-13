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
        DB::statement("
            ALTER TABLE orders 
            MODIFY COLUMN order_status 
            ENUM('Order Placed', 'Order Confirmed', 'Preparing', 'Ready for Pickup', 'Out For Delivery', 'Delivered', 'Rejected', 'Cancelled') 
            DEFAULT 'Order Placed'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE orders 
            MODIFY COLUMN order_status 
            ENUM('Order Placed', 'Order Confirmed', 'Preparing', 'Ready for Pickup', 'Out For Delivery', 'Delivered', 'Rejected') 
            DEFAULT 'Order Placed'
        ");
    }
};
