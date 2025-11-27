<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariationOption extends Model
{
    //


    protected $fillable = [
        'variation_id',
        'name',
        'barcode',
        'quantity',
        'color',
        'mrp',
        'selling_price',
        'price',
        'discount_rate',
        'discount_amount',
        'is_default',
        'use_default_price',
        'no_discount'
    ];

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id', 'id');
    }

    public function vendorStocks()
    {
        return $this->hasMany(VendorProductStock::class, 'option_id');
    }

}
