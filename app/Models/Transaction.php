<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'vendor_id',
        'transaction_number',
        'amount',
        'payment_method',
        'payment_status',
        'gateway_transaction_id',
        'currency',
        'paid_at',
    ];

    /**
     * Transaction belongs to an order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Transaction belongs to a user (payer)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Transaction belongs to a vendor (receiver)
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
