<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    protected $table = 'recipe_ingredient';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id','quantity_required','unit','recipes_id','ingredients_id'
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipes_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredients_id');
    }
}
