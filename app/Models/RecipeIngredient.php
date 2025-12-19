<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecipeIngredient
 * 
 * @property int $id
 * @property int $quantity_required
 * @property string $unit
 * @property int $recipe_id
 * @property int $ingredient_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Ingredient $ingredient
 * @property Recipe $recipe
 *
 * @package App\Models
 */
class RecipeIngredient extends Model
{
	protected $table = 'recipe_ingredients';

	protected $casts = [
		'quantity_required' => 'int',
		'recipe_id' => 'int',
		'ingredient_id' => 'int'
	];

	protected $fillable = [
		'quantity_required',
		'unit',
		'recipe_id',
		'ingredient_id'
	];

	public function ingredient()
	{
		return $this->belongsTo(Ingredient::class);
	}

	public function recipe()
	{
		return $this->belongsTo(Recipe::class);
	}
}
