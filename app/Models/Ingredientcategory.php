<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IngredientCategory
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Ingredient[] $ingredients
 *
 * @package App\Models
 */
class IngredientCategory extends Model
{
	protected $table = 'ingredient_categories';

	protected $fillable = [
		'name',
		'description'
	];

	public function ingredients()
	{
		return $this->hasMany(Ingredient::class);
	}
}
