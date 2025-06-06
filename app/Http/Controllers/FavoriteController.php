<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $userFavoriteRecipes = $user->favoriteRecipes()->orderBy('favorites.created_at', 'desc')->get();

        $favoritesForView = [];
        if ($userFavoriteRecipes->isNotEmpty()) {
            foreach ($userFavoriteRecipes as $recipe) {
                $favoritesForView[$recipe->recipe_id] = [
                    'title' => $recipe->name,
                    'image' => $recipe->image_url,
                    'cal'   => $recipe->cal,
                    'time'  => $recipe->time,
                ];
            }
        }
        return view('favorites', ['favorites' => $favoritesForView]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|integer|exists:recipes,recipe_id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $recipeId = $request->input('recipe_id');

        // Cek dulu apakah resep ada (walau udah divalidasi exists)
        $recipe = Recipe::find($recipeId);
        if (!$recipe) {
            return back()->with('error', 'Resep tidak ditemukan.');
        }

        // Cek udah difavoritkan ato belum
        if (!$user->favoriteRecipes()->where('recipes.recipe_id', $recipeId)->exists()) {
            $user->favoriteRecipes()->attach($recipeId);
            return back()->with('success', htmlspecialchars($recipe->name) . ' berhasil ditambahkan ke favorit!');
        } else {
            return back()->with('info', htmlspecialchars($recipe->name) . ' sudah ada di daftar favorit Anda.');
        }
    }
    // hapus
    public function destroy($recipe_id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $recipe = Recipe::find($recipe_id);
        $recipeName = $recipe ? htmlspecialchars($recipe->name) : 'Resep tersebut';

        if ($user->favoriteRecipes()->detach($recipe_id)) {
            return redirect()->route('favorites.index')->with('success', $recipeName . ' berhasil dihapus dari favorit.');
        } else {
            return redirect()->route('favorites.index')->with('error', 'Gagal menghapus ' . $recipeName . ' dari favorit (mungkin sudah tidak ada).');
        }
    }
    //toggle status favorit resep untuk user
    public function toggle(Request $request)
    {
        try {
            $request->validate([
                'recipe_id' => 'required|integer|exists:recipes,recipe_id',
            ]);

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $recipeId = $request->input('recipe_id');
            $recipe = Recipe::find($recipeId);

            if (!$recipe) {
                if ($request->expectsJson()) { 
                    return response()->json(['error' => 'Resep tidak ditemukan.'], 404); 
                }
                return back()->with('error', 'Resep tidak ditemukan.');
            }

            $message = '';
            $status = '';
            $isFavorited = $user->favoriteRecipes()->where('recipes.recipe_id', $recipeId)->exists();

            if ($isFavorited) {
                // hapus dari favorit
                $user->favoriteRecipes()->detach($recipeId);
                $message = htmlspecialchars($recipe->name) . ' dihapus dari favorit.';
                $status = 'removed';
                Log::info("Recipe {$recipeId} removed from favorites for user {$user->id}");
            } else {
                // tambahkan ke favorit
                $user->favoriteRecipes()->attach($recipeId);
                $message = htmlspecialchars($recipe->name) . ' ditambahkan ke favorit.';
                $status = 'added';
                Log::info("Recipe {$recipeId} added to favorites for user {$user->id}");
            }

            if ($request->expectsJson()) {
                // Untuk respons AJAX
                $isNowFavorited = !$isFavorited;
                return response()->json([
                    'message' => $message, 
                    'status' => $status, 
                    'recipe_id' => $recipeId, 
                    'isFavorited' => $isNowFavorited
                ]);
            }
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Error in toggle favorite: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Terjadi kesalahan. Silakan coba lagi.'], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    // Cek status favorit resep untuk user
    public function checkStatus($recipe_id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $isFavorited = $user->favoriteRecipes()->where('recipes.recipe_id', $recipe_id)->exists();
        
        return response()->json(['isFavorited' => $isFavorited]);
    }
}