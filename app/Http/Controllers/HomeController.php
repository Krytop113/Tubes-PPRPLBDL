<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::inRandomOrder()->limit(4)->get();
        $recipes = Recipe::inRandomOrder()->limit(3)->get();
        $user = Auth::user();

        return view('customer.home', compact('ingredients', 'recipes','user'));
    }
}
