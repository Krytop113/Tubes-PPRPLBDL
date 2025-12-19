<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property int $id
 * @property string $status
 * @property float $total_raw
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|OrderDetail[] $order_details
 * @property Collection|Payment[] $payments
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'orders';

	protected $casts = [
		'total_raw' => 'float',
		'user_id' => 'int'
	];

	protected $fillable = [
		'status',
		'total_raw',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function order_details()
	{
		return $this->hasMany(OrderDetail::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class);
	}
}
