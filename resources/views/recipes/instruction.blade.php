@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-3">{{ $recipe->name }} - Cooking Instructions</h2>

    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}"
         class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover; width: 100%;">

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Step-by-Step</h5>
            <p class="card-text">{{ $recipe->instructions }}</p>
        </div>
    </div>

    <a href="{{ route('recipes.show', $recipe->recipe_id) }}" class="btn btn-secondary mt-3">
        ← Back to Recipe
    </a>
</div>
@endsection
