<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Coupon
 * 
 * @property int $id
 * @property string $title
 * @property string $description
 * @property float $discount_percentage
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Coupon extends Model
{
	protected $table = 'coupons';

	protected $casts = [
		'discount_percentage' => 'float',
		'start_date' => 'datetime',
		'end_date' => 'datetime'
	];

	protected $fillable = [
		'title',
		'description',
		'discount_percentage',
		'start_date',
		'end_date'
	];

	public function users()
	{
		return $this->belongsToMany(User::class, 'coupon_users')
					->withPivot('id', 'status')
					->withTimestamps();
	}
}
