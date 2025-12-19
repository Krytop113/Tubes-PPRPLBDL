<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $phone
 * @property int $role_id
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Role $role
 * @property Collection|Coupon[] $coupons
 * @property Collection|Notification[] $notifications
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime',
		'role_id' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'phone',
		'role_id',
		'remember_token'
	];

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function coupons()
	{
		return $this->belongsToMany(Coupon::class, 'coupon_users')
					->withPivot('id', 'status')
					->withTimestamps();
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class);
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
