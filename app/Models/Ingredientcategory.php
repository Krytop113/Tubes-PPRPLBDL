<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientCategory extends Model
{
    protected $table = 'ingredient_category';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'name', 'description'];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'ingredient_category_id');
    }
}
