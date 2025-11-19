<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    protected $fillable = [
        'vendor_id',
        'product_id',
        'availability'
    ];

    // Vendor Relationship
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // Product Relationship
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function stocks()
    {
        return $this->hasMany(VendorProductStock::class);
    }

}
