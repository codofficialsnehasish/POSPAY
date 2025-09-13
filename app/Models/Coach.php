<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    //

    protected $fillable = ['vendor_id','name','is_visible'];
    public function vendor()
    {
        return $this->belongsTo(User::class,'vendor_id','id' );
    }
    
       public function seat_number()
    {
        return $this->hasMany(SeatNumber::class,'coach_id','id' );
    }
}
