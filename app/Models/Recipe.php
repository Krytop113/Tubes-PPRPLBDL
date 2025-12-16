<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id','title','description','steps','cook_time',
        'serving','image_url','recipe_category_id'
    ];

    public function category()
    {
        return $this->belongsTo(RecipeCategory::class, 'recipe_category_id');
    }
}
