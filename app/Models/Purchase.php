<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'seller_name',
        'vendor_id',
        'invoice_number',
        'purchase_date',
        'total_amount',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
