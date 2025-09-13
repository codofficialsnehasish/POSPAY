<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    //

    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'variation_type',
        'insert_type',
        'option_display_type',
        'show_images_on_slider',
        'use_different_price',
        'use_different_price',
        'is_visible'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function options()
    {
        return $this->hasMany(ProductVariationOption::class, 'variation_id','id');

    }
}
