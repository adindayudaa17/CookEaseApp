<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
Route::put('/edit-name', [SettingsController::class, 'updateName'])->name('edit-name');




// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
