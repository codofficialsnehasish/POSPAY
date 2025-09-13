<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVendor extends Model
{
    //


    protected $fillable = [
        'user_id',
        'vendor_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id','id');
    }
}
