<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeCategory extends Model
{
    protected $table = 'recipe_category';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id','name','description'];
}
