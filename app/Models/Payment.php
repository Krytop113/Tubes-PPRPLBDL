<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * 
 * @property int $id
 * @property float $coupon_amount
 * @property float $amount
 * @property string $method
 * @property Carbon $date
 * @property int $order_id
 * @property int $coupon_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CouponUser $coupon_user
 * @property Order $order
 *
 * @package App\Models
 */
class Payment extends Model
{
	protected $table = 'payments';

	protected $casts = [
		'coupon_amount' => 'float',
		'amount' => 'float',
		'date' => 'datetime',
		'order_id' => 'int',
		'coupon_id' => 'int'
	];

	protected $fillable = [
		'coupon_amount',
		'amount',
		'method',
		'date',
		'order_id',
		'coupon_id'
	];

	public function coupon_user()
	{
		return $this->belongsTo(CouponUser::class, 'coupon_id');
	}

	public function order()
	{
		return $this->belongsTo(Order::class);
	}
}
