<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //

    protected $fillable = [
        'user_id',
        'gst_no',
        'store_no',
        'role',
        'gst_no',
        'address',
        'location',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
