<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $showAll = $request->get('show_all', false);

        $query = DB::table('recipes')
            ->leftJoin('categories', 'recipes.category_id', '=', 'categories.category_id')
            ->select('recipes.*', 'categories.name as category_name');

        // Search functionality
        if ($search) {
            $query->where('recipes.name', 'LIKE', "%{$search}%");
        }

        // Category filtering
        if ($category && $category !== 'all') {
            $query->where('categories.name', $category);
        }

        // Limit results unless showing all
        if (!$showAll) {
            $query->limit(4);
        }

        $recipes = $query->get();
        $totalRecipes = DB::table('recipes')->count();

        return view('welcome', compact('recipes', 'totalRecipes', 'search', 'category', 'showAll'));
    }

    public function show($id)
    {
        $recipe = DB::table('recipes')
            ->leftJoin('categories', 'recipes.category_id', '=', 'categories.category_id')
            ->select('recipes.*', 'categories.name as category_name')
            ->where('recipes.recipe_id', $id)
            ->first();

        if (!$recipe) {
            abort(404);
        }

        return view('recipes.show', compact('recipe'));
    }
}
