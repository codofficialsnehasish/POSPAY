<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSeat extends Model
{
    //
    protected $fillable = [
        'order_id',
        'seat_number_id',
        'seat_number',

    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
       public function seat_number()
    {
        return $this->belongsTo(SeatNumber::class,'seat_number_id','id');
    }
}
