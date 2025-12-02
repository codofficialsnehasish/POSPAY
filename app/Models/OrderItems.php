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
        'app_discount',
        'hsn_code',
        'gst_rate',
        'gst_amount',
        'subtotal',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Get the variation for the order item
     */
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id', 'id');
    }

    /**
     * Get the option for the order item
     */
    public function option()
    {
        return $this->belongsTo(ProductVariationOption::class, 'option_id', 'id');
    }
}
