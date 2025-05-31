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
    Route::get('/dashboard', function (Request $request) { // <-- Tambahkan Request $request
        $search = $request->input('search');
        $categoryFilter = $request->input('category', 'all'); // Ambil category, default 'all'

        $query = Recipe::with('category'); // Eager load category untuk efisiensi

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($categoryFilter && $categoryFilter !== 'all') {
            $query->whereHas('category', function($q) use ($categoryFilter) {
                $q->where('name', $categoryFilter); // Asumsi filter berdasarkan nama kategori
            });
        }
        
        $recipes = $query->get();

        // ---  LOGIKA UNTUK STATUS FAVORIT ---
        if (Auth::check()) { // Hanya jika user sudah login
            /** @var \App\Models\User $user */
            $user = Auth::user();
            // Ambil semua ID resep favorit user dalam satu query untuk efisiensi
            $favoriteRecipeIds = $user->favoriteRecipes()->pluck('recipes.recipe_id')->toArray();

            foreach ($recipes as $recipe) {
                $recipe->is_favorited = in_array($recipe->recipe_id, $favoriteRecipeIds);
            }
        } else {
            // Jika tidak ada user login (seharusnya tidak terjadi karena middleware 'auth')
            // atau jika kamu ingin handle kasus ini secara eksplisit:
            foreach ($recipes as $recipe) {
                $recipe->is_favorited = false; // Defaultnya tidak ada yang difavoritkan
            }
        }
        // --- AKHIR DARI LOGIKA STATUS FAVORIT ---
        
        // Ambil variabel $showAll untuk logika View All/Show Less di view
        // Jika tidak ada parameter 'show_all' di URL, defaultnya false (tidak menampilkan semua)
        // Kamu menggunakan $index >= 4 ? 'hidden recipe-extra' : '' di view,
        // jadi kita perlu cara untuk toggle ini. Jika menggunakan parameter URL:
        $showAll = $request->input('show_all', '0') === '1'; // '0' atau '1' dari URL, konversi ke boolean

        return view('dashboard', compact('recipes', 'search', 'categoryFilter', 'showAll'));
    })->name('dashboard');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
    Route::get('/edit-name', [SettingsController::class, 'editName'])->name('edit-name');
    Route::put('/update-name', [SettingsController::class, 'updateName'])->name('updateName');
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index'); // Beri nama jika belum
    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send'); // Beri nama jika belum

    // Favorites routes
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{recipe_id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    
    // Rating routes
    Route::get('/rate-recipes', [RatingController::class, 'index'])->name('rate-recipes');
    Route::post('/rate-recipe/{id}', [RatingController::class, 'store'])->name('rate-recipe');
});