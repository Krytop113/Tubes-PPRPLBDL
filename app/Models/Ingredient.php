<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'ingredients';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id','name','unit','price_per_unit','description',
        'quantity','last_update','image_url','min_stock','ingredient_category_id'
    ];

    public function category()
    {
        return $this->belongsTo(IngredientCategory::class, 'ingredient_category_id');
    }
}
