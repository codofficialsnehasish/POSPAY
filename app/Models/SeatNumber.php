<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatNumber extends Model
{

    protected $fillable = ['coach_id','name','is_visible'];


    public function coach()
    {
        return $this->belongsTo(Coach::class,'coach_id','id' );
    }
}
