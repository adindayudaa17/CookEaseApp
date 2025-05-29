<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function show($id)
    {
        $recipe = Recipe::findOrFail($id);
        return view('recipes.show', compact('recipe'));
    }

    public function instruction($id)
    {
        $recipe = Recipe::findOrFail($id);
        return view('recipes.instruction', compact('recipe'));
    }
}
