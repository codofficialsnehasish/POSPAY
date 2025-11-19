<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProductStock extends Model
{
    protected $fillable = [
        'vendor_product_id',
        'variation_id',
        'option_id',
        'stock',
        'low_stock_alert',
    ];

    public function vendorProduct()
    {
        return $this->belongsTo(VendorProduct::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    public function option()
    {
        return $this->belongsTo(ProductVariationOption::class, 'option_id');
    }
}
