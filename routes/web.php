<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RatingController;
use App\Models\Recipe;
use Illuminate\Http\Request; // <-- PASTIKAN INI DI-IMPORT
use Illuminate\Support\Facades\Auth; // <-- PASTIKAN INI DI-IMPORT

// Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public recipe routes
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/recipes/{id}/instruction', [RecipeController::class, 'instruction'])->name('recipes.instruction');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { // <-- Hapus Request $request dari sini
        $search = request('search'); // Menggunakan helper request()
        $categoryFilter = request('category', 'all'); // Menggunakan helper request()

        $query = Recipe::with('category');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($categoryFilter && $categoryFilter !== 'all') {
            $query->whereHas('category', function($q) use ($categoryFilter) {
                $q->where('name', $categoryFilter);
            });
        }
        
        $recipes = $query->get();

        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $favoriteRecipeIds = $user->favoriteRecipes()->pluck('recipes.recipe_id')->toArray();
            foreach ($recipes as $recipe) {
                $recipe->is_favorited = in_array($recipe->recipe_id, $favoriteRecipeIds);
            }
        } else {
            foreach ($recipes as $recipe) {
                $recipe->is_favorited = false;
            }
        }
        
        $showAll = request('show_all', '0') === '1'; // Menggunakan helper request()

        return view('dashboard', compact('recipes', 'search', 'categoryFilter', 'showAll'));
    })->name('dashboard');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
    Route::get('/edit-name', [SettingsController::class, 'editName'])->name('edit-name');
    Route::put('/update-name', [SettingsController::class, 'updateName'])->name('updateName');
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage']);
    // Favorites routes
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{recipe_id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    
    // Rating routes
    Route::get('/rate-recipes', [RatingController::class, 'index'])->name('rate-recipes');
    Route::post('/rate-recipe/{id}', [RatingController::class, 'store'])->name('rate-recipe');
});