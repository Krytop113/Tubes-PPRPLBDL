<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CouponUser
 * 
 * @property int $id
 * @property string $status
 * @property int $coupon_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Coupon $coupon
 * @property User $user
 * @property Collection|Payment[] $payments
 *
 * @package App\Models
 */
class CouponUser extends Model
{
	protected $table = 'coupon_users';

	protected $casts = [
		'coupon_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'status',
		'coupon_id',
		'user_id'
	];

	public function coupon()
	{
		return $this->belongsTo(Coupon::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class, 'coupon_id');
	}
}
