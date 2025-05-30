<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with(['ratings' => function($query) {
            $query->where('user_id', Auth::id());
        }])->get();

        return view('rate-recipes', compact('recipes'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5'
        ]);

        $recipe = Recipe::findOrFail($id);
        
        // Check if user already rated this recipe
        $existingRating = Rating::where('user_id', Auth::id())
                               ->where('recipe_id', $id)
                               ->first();

        if ($existingRating) {
            return response()->json([
                'success' => false,
                'message' => 'You have already rated this recipe.'
            ], 400);
        }

        // Create new rating
        Rating::create([
            'user_id' => Auth::id(),
            'recipe_id' => $id,
            'rating' => $request->rating
        ]);

        // Calculate new average rating
        $averageRating = Rating::where('recipe_id', $id)->avg('rating');
        $totalReviews = Rating::where('recipe_id', $id)->count();

        // Update recipe rating and reviews count
        $recipe->update([
            'rate' => round($averageRating, 1),
            'reviews' => $totalReviews
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'new_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews
        ]);
    }
}
