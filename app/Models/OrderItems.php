<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{

    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',
        'option_id',
        'product_name',
        'quantity',
        'price',
        'mrp',
        'discount_rate',
        'discount_amount',
        'subtotal',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
