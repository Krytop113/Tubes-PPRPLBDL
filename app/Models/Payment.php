<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id','coupon_total','total','method','date','coupon_user_id','order_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function couponUser()
    {
        return $this->belongsTo(CouponUser::class, 'coupon_user_id');
    }
}
