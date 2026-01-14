<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ingredient
 * 
 * @property int $id
 * @property string $name
 * @property string $unit
 * @property float $price_per_unit
 * @property string $description
 * @property int $stock_quantity
 * @property int $minimum_stock_level
 * @property Carbon $last_update
 * @property string $image_url
 * @property int $ingredient_category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property IngredientCategory $ingredient_category
 * @property Collection|OrderDetail[] $order_details
 * @property Collection|Recipe[] $recipes
 *
 * @package App\Models
 */
class Ingredient extends Model
{
	protected $table = 'ingredients';

	protected $casts = [
		'price_per_unit' => 'float',
		'stock_quantity' => 'int',
		'minimum_stock_level' => 'int',
		'last_update' => 'datetime',
		'ingredient_category_id' => 'int'
	];

	protected $fillable = [
		'name',
		'unit',
		'price_per_unit',
		'description',
		'stock_quantity',
		'minimum_stock_level',
		'last_update',
		'image_url',
		'ingredient_category_id'
	];

	public function ingredient_category()
	{
		return $this->belongsTo(IngredientCategory::class);
	}

	public function order_details()
	{
		return $this->hasMany(OrderDetail::class);
	}

	public function recipes()
	{
		return $this->belongsToMany(Recipe::class, 'recipe_ingredients')
					->withPivot('id', 'quantity_required', 'unit')
					->withTiAmestamps();
	}
}
