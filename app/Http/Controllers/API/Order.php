<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'vendor_id',
        'order_type',
        'coupone_code',
        'coupone_discount',
        'price_subtotal',
        'price_gst',
        'price_shipping',
        'discounted_price',
        'total_amount',
        'payment_method',
        'formatted_address',
        'latitude',
        'longitude',
        'contact_number',
        'contact_purson',
        'delevery_note',
        'seat_number',
    ];

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }
}
