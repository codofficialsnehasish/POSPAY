<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id','product_id','variation_id','option_id','quantity','product_title',];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id', 'id');
    }
    public function options()
    {
        return $this->belongsTo(ProductVariationOption::class, 'option_id','id');

    }
    
    public function variationOption()
{
    return $this->belongsTo(ProductVariationOption::class, 'option_id');
}

    


}
