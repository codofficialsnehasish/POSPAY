<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'product_type',
        'sort_description',
        'long_description',
        'brand_id',
        'hsncode_id',
        'is_gst_included',
        'brand_owner',
        'vendor_id',
        'store_id',
        'veg',
        'price',
        'product_price',
        'discount_rate',
        'discount_price',
        'gst_rate',
        'gst_amount',
        'total_price',
        'is_available',
        'is_special',
        'barcode',
        'measure',
        'is_visible',
    ];
    
    public function product_subcategories()
    {
        return $this->hasMany(ProductCategory::class, 'product_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function subcategories()
    {
        return $this->belongsToMany(Category::class, 'product_sub_categories', 'product_id', 'subcategory_id');
    }

    public function addons()
    {
        return $this->hasMany(AddonsProducts::class, 'product_id', 'id');
    }

    public function addon_products()
    {
        return $this->belongsToMany(Product::class,AddonsProducts::class,'product_id', 'addons_id');
    }

    public function complamentary()
    {
        return $this->hasMany(ComplementaryProducts::class, 'product_id', 'id');
    }

    public function complamentary_products()
    {
        return $this->belongsToMany(Product::class,ComplementaryProducts::class,'product_id', 'complamentary_id');
    }


    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id','id');

    }
    
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }

    public function hsncode()
    {
        return $this->belongsTo(Hsncode::class, 'hsncode_id', 'id');
    }

    public function vendorProducts()
    {
        return $this->hasMany(VendorProduct::class);
    }

    public function vendorStocks()
    {
        return $this->hasManyThrough(
            VendorProductStock::class,
            VendorProduct::class,
            'product_id',       // Foreign key on VendorProduct table
            'vendor_product_id' // Foreign key on VendorProductStock table
        );
    }

    public function vendorStock($vendorId, $option_id)
    {
        $stock = $this->vendorProducts()
            ->where('vendor_id', $vendorId)
            ->with(['stocks' => function ($q) use ($option_id) {
                $q->where('option_id', $option_id);
            }])
            ->first()
            ?->stocks
            ->first();

        return $stock->stock ?? 0;
    }


}
