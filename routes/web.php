<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function (Illuminate\Http\Request $request) {
    $search = $request->get('search', '');
    $category = $request->get('category', 'all');
    $showAll = $request->get('show_all', false);

    $query = DB::table('recipes')
        ->leftJoin('categories', 'recipes.category_id', '=', 'categories.category_id')
        ->select('recipes.*', 'categories.name as category_name');

    if ($search) {
        $query->where('recipes.name', 'LIKE', "%{$search}%");
    }

    if ($category && $category !== 'all') {
        $query->where('categories.name', $category);
    }

    if (!$showAll) {
        $query->limit(4);
    }

    $recipes = $query->get();
    $totalRecipes = DB::table('recipes')->count();

    // Make sure all variables are defined
    return view('welcome', compact('recipes', 'totalRecipes', 'search', 'category', 'showAll'));
})->name('home');

Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    $search = $request->get('search', '');
    $category = $request->get('category', 'all');
    $showAll = $request->get('show_all', false);

    $query = DB::table('recipes')
        ->leftJoin('categories', 'recipes.category_id', '=', 'categories.category_id')
        ->select('recipes.*', 'categories.name as category_name');

    if ($search) {
        $query->where('recipes.name', 'LIKE', "%{$search}%");
    }

    if ($category && $category !== 'all') {
        $query->where('categories.name', $category);
    }

    if (!$showAll) {
        $query->limit(4);
    }

    $recipes = $query->get();
    $totalRecipes = DB::table('recipes')->count();

    return view('welcome', compact('recipes', 'totalRecipes', 'search', 'category', 'showAll'));
})->name('dashboard');

Route::get('/recipes/{id}', function ($id) {
    $recipe = DB::table('recipes')
        ->leftJoin('categories', 'recipes.category_id', '=', 'categories.category_id')
        ->select('recipes.*', 'categories.name as category_name')
        ->where('recipes.recipe_id', $id)
        ->first();

    if (!$recipe) {
        abort(404);
    }

    return view('recipes.show', compact('recipe'));
})->name('recipes.show');

Route::get('/recipes/{id}/instruction', function ($id) {
    $recipe = DB::table('recipes')
        ->leftJoin('categories', 'recipes.category_id', '=', 'categories.category_id')
        ->select('recipes.*', 'categories.name as category_name')
        ->where('recipes.recipe_id', $id)
        ->first();

    if (!$recipe) {
        abort(404);
    }

    return view('recipes.instruction', compact('recipe'));
})->name('recipes.instruction');

Route::get('/favorites', function () {
    return view('favorites');
})->name('favorites');

Route::get('/chatbot', function () {
    return view('chatbot');
})->name('chatbot');

Route::get('/cooking-assist', function () {
    return view('chatbot');
})->name('cooking-assist');

Route::get('/settings', function () {
    return view('settings');
})->name('settings');

Route::get('/rate-recipes', function () {
    return view('rate-recipes');
})->name('rate-recipes');
