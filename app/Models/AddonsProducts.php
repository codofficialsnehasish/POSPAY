<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonsProducts extends Model
{
    protected $fillable = [
        'product_id',
        'addons_id',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function product_addons()
    {
        return $this->belongsTo(Product::class, 'addons_id','id');
    }
}
