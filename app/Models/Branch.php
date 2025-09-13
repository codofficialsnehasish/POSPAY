<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    //
    protected $fillable = ['coach_id','name','user_id'];


    public function vendor()
    {
        return $this->belongsTo(User::class,'user_id','id' );
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class,'coach_id','id' );
    }

   
}
