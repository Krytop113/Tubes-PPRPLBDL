<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderDetail
 * 
 * @property int $id
 * @property int $quantity
 * @property float $price
 * @property string $status
 * @property int $ingredient_id
 * @property int $order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Ingredient $ingredient
 * @property Order $order
 *
 * @package App\Models
 */
class OrderDetail extends Model
{
	protected $table = 'order_details';

	protected $casts = [
		'quantity' => 'int',
		'price' => 'float',
		'ingredient_id' => 'int',
		'order_id' => 'int'
	];

	protected $fillable = [
		'quantity',
		'price',
		'status',
		'ingredient_id',
		'order_id'
	];

	public function ingredient()
	{
		return $this->belongsTo(Ingredient::class);
	}

	public function order()
	{
		return $this->belongsTo(Order::class);
	}
}
