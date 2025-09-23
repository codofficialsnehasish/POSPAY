<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(SellerMaster::class,'seller_name','id');
    }
}
