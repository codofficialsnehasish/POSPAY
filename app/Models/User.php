<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia , HasRoles;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'status',
        'role',
        'email',
        'phone',
        'password',
        'address',
        'store_number',
        'store_location',
        'gst_no',
        'admin_id',
        'vendor_id',
        'pos_number',
        'pos_password',
        'prod_app_key',
        'merchant_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function otp()
    {
        return $this->hasOne(Otp::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class,'vendor_id','id' );
    }
    
    public function vendors()
    {
        return $this->belongsToMany(User::class, 'user_vendors', 'user_id', 'vendor_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class,'admin_id','id' );
    }
    
    public function branch()
    {
        return $this->hasMany(Branch::class,'user_id','id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'vendor_id');
    }

}
