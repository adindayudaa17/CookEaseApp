<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RatingController; // Pastikan ini di-import
use App\Models\Recipe;

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
Route::get('/settings', function () {return view('settings');})->name('settings');
Route::get('/edit-name', [SettingsController::class, 'editName'])->name('edit-name');
Route::put('/update-name', [SettingsController::class, 'updateName'])->name('updateName');
Route::get('/chatbot', [ChatbotController::class, 'index']);
Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage']);


Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/recipes/{id}/instruction', [RecipeController::class, 'instruction'])->name('recipes.instruction');

Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');

// Rating routes
Route::get('/rate-recipes', [App\Http\Controllers\RatingController::class, 'index'])->middleware('auth')->name('rate-recipes');
Route::post('/rate-recipe/{id}', [App\Http\Controllers\RatingController::class, 'store'])->middleware('auth')->name('rate-recipe');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $recipes = Recipe::all();
        return view('dashboard', compact('recipes'));
    })->name('dashboard');

    // FAVORITES ROUTES
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{recipe_id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Pindahkan route lain yang butuh login ke sini jika ada (misal settings, chatbot jika perlu)
});
// Protected routes

