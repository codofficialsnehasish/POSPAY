<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hsncode extends Model
{
    
    protected $fillable = [
        'hsncode', 
        'gst_rate',
        'vendor_id',
        'admin_id',
        'is_visible'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}
