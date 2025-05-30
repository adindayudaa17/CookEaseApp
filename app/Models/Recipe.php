<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;     // <-- PASTIKAN INI ADA
use App\Models\Category; 

class Recipe extends Model
{
    protected $table = 'recipes';
    protected $primaryKey = 'recipe_id';
    public $timestamps = true;

    protected $casts = [
        'ingredients_name' => 'array',
        'ingredients_amount' => 'array',
        'ingredients_image' => 'array',
    ];

    protected $fillable = [
        'firestore_doc_id',
        'category_id',
        'name',
        'cal',
        'difficulty',
        'image_url',
        'instructions',
        'ingredients_name',
        'ingredients_amount',
        'ingredients_image',
        'rate',
        'reviews',
        'time'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'recipe_id', 'recipe_id');
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?: 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->ratings()->count();
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'recipe_id', 'user_id')
                    ->withTimestamps();
    }
}
