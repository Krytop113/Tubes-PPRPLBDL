<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Recipe
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $steps
 * @property int $cook_time
 * @property int $serving
 * @property string $image_url
 * @property int $recipe_category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property RecipeCategory $recipe_category
 * @property Collection|Ingredient[] $ingredients
 *
 * @package App\Models
 */
class Recipe extends Model
{
	protected $table = 'recipes';

	protected $casts = [
		'cook_time' => 'int',
		'serving' => 'int',
		'recipe_category_id' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'steps',
		'cook_time',
		'serving',
		'image_url',
		'recipe_category_id'
	];

	public function recipe_category()
	{
		return $this->belongsTo(RecipeCategory::class);
	}

	public function ingredients()
	{
		return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
					->withPivot('id', 'quantity_required', 'unit')
					->withTimestamps();
	}
}
