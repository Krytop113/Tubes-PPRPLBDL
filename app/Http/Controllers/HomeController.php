<?php

namespace App\Http\Controllers;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:customer']);
    // }

    public function index()
    {
        $ingredients = Ingredient::inRandomOrder()->limit(3)->get();
        $recipes = Recipe::inRandomOrder()->limit(3)->get();

        return view('customer.home', compact('ingredients', 'recipes'));
    }
}
