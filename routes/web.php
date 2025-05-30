<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ChatbotController;
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



Route::get('/dashboard', function () {
    $recipes = Recipe::all(); // atau Recipe::latest()->take(4)->get();
    return view('dashboard', compact('recipes'));
})->middleware('auth')->name('dashboard');


// Protected routes

