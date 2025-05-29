<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['category_id', 'name', 'image'];

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'category_id', 'category_id');
    }
}
