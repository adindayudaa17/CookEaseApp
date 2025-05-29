<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
