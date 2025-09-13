<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hsncode extends Model
{
    
    protected $fillable = [
        'hsncode', 
        'gst_rate',
        'vendor_id',
        'is_visible'
    ];
}
