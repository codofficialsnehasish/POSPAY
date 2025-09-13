<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'pincode',
        'city',
        'state',
        'country',
        'address_type',
        'is_default',
        'formatted_address',
        'street_addresses',
        'landmark',
        'latitude',
        'longitude',
    ];
}
