<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerMaster extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * (Optional if table name follows Laravel convention)
     */
    protected $table = 'seller_masters';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'seller_name',
        'vendor_id',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'gst_number',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Example relationship if Seller has Products.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    /**
     * Example relationship if Seller is linked to Vendor.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
