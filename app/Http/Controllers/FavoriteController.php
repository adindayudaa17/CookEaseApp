<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User; // Baik untuk type hinting, meskipun Auth::user() tidak memerlukan ini secara langsung
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mengambil user yang login

class FavoriteController extends Controller
{
    /**
     * Semua method di controller ini memerlukan user untuk login.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Menampilkan daftar resep favorit user yang sedang login.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user(); // Mengambil user yang sedang login

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

    /**
     * Menyimpan resep baru ke daftar favorit user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|integer|exists:recipes,recipe_id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $recipeId = $request->input('recipe_id');

        // Cek dulu apakah resep ada (meskipun sudah divalidasi exists)
        $recipe = Recipe::find($recipeId);
        if (!$recipe) {
            return back()->with('error', 'Resep tidak ditemukan.');
        }

        // Cek apakah sudah difavoritkan
        if (!$user->favoriteRecipes()->where('recipes.recipe_id', $recipeId)->exists()) {
            $user->favoriteRecipes()->attach($recipeId);
            return back()->with('success', htmlspecialchars($recipe->name) . ' berhasil ditambahkan ke favorit!');
        } else {
            return back()->with('info', htmlspecialchars($recipe->name) . ' sudah ada di daftar favorit Anda.');
        }
    }

    /**
     * Menghapus resep dari daftar favorit user.
     * $recipe_id diambil dari parameter route.
     */
    public function destroy($recipe_id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $recipe = Recipe::find($recipe_id); // Untuk nama di pesan feedback
        $recipeName = $recipe ? htmlspecialchars($recipe->name) : 'Resep tersebut';

        if ($user->favoriteRecipes()->detach($recipe_id)) { // detach mengembalikan jumlah record yang dihapus
            return redirect()->route('favorites.index')->with('success', $recipeName . ' berhasil dihapus dari favorit.');
        } else {
            return redirect()->route('favorites.index')->with('error', 'Gagal menghapus ' . $recipeName . ' dari favorit (mungkin sudah tidak ada).');
        }
    }

    /**
     * Toggle status favorit resep untuk user.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|integer|exists:recipes,recipe_id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $recipeId = $request->input('recipe_id');
        $recipe = Recipe::find($recipeId);

        if (!$recipe) {
            if ($request->expectsJson()) { return response()->json(['error' => 'Resep tidak ditemukan.'], 404); }
            return back()->with('error', 'Resep tidak ditemukan.');
        }

        $message = '';
        $status = '';
        $isFavorited = $user->favoriteRecipes()->where('recipes.recipe_id', $recipeId)->exists();

        if ($isFavorited) {
            $user->favoriteRecipes()->detach($recipeId);
            $message = htmlspecialchars($recipe->name) . ' dihapus dari favorit.';
            $status = 'removed';
        } else {
            $user->favoriteRecipes()->attach($recipeId);
            $message = htmlspecialchars($recipe->name) . ' ditambahkan ke favorit.';
            $status = 'added';
        }

        if ($request->expectsJson()) {
            // Untuk respons AJAX, sertakan juga status favorit baru
            $isNowFavorited = !$isFavorited;
            return response()->json(['message' => $message, 'status' => $status, 'recipe_id' => $recipeId, 'isFavorited' => $isNowFavorited]);
        }
        return back()->with('success', $message);
    }
}