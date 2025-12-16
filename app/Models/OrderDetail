<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'orderdetail';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id','quantity','price','status','order_id','ingredients_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredients_id');
    }
}
