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
        'is_darft',
        'order_status',
        'discount_amount',
        'complimentary_amount',
        'gst_amount',
        'cgst_amount',
        'sgst_amount',
    ];

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }
    
     public function seats()
    {
        return $this->hasMany(OrderSeat::class,'order_id', 'id');
    }
}
