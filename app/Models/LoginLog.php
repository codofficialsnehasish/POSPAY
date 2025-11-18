<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $fillable = [
        'user_id',
        'vendor_id',
        'device_type',
        'model',
        'serial_number',
        'login_time',
        'logout_time',
    ];

    // User relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Vendor relationship
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }
}
