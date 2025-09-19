<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = [
        'product_id',
        'veriation_option_id',
        'batch_number',
        'transaction_type',
        'transaction_date',
        'quantity_in',
        'quantity_out',
        'opening_balance',
        'closing_balance',
        'expiry_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variationOption()
    {
        return $this->belongsTo(ProductVariationOption::class, 'veriation_option_id');
    }
}
