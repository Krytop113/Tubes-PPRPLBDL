<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'name', 'email', 'password', 'phone', 'created_at', 'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function coupons()
    {
        return $this->hasMany(CouponUser::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
