@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-3">{{ $recipe->name }}</h2>

    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}"
         class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover; width: 100%;">

    <div class="mb-3">
        <p>🔥 {{ $recipe->cal }} cal &nbsp; ⏱ {{ $recipe->time }} Min</p>
        <p>⭐ {{ $recipe->rate }}/5 ({{ $recipe->reviews }} Reviews)</p>
    </div>

    <div class="mb-3">
        <label for="servings">How many servings?</label>
        <div class="d-flex align-items-center gap-2">
            <button id="minus" class="btn btn-sm btn-outline-secondary">-</button>
            <span id="serving-count">1</span>
            <button id="plus" class="btn btn-sm btn-outline-secondary">+</button>
        </div>
    </div>

    <h5>Ingredients</h5>
    <ul id="ingredients-list">
        @foreach($recipe->ingredients_name as $index => $name)
            <li data-base-amount="{{ $recipe->ingredients_amount[$index] ?? 0 }}" class="ingredient-item mb-2">
                <img src="{{ $recipe->ingredients_image[$index] ?? '' }}" alt="{{ $name }}" width="40">
                <span>{{ $name }} - </span>
                <span class="amount">{{ $recipe->ingredients_amount[$index] ?? 0 }}</span> g
            </li>
        @endforeach
    </ul>

    <a href="{{ route('recipes.instruction', $recipe->recipe_id) }}" class="btn btn-danger mt-4">
        Start Cooking →
    </a>
</div>

<script>
    let servingCount = 1;

    const updateIngredients = () => {
        document.querySelectorAll('.ingredient-item').forEach(item => {
            const base = parseFloat(item.dataset.baseAmount);
            const amount = item.querySelector('.amount');
            amount.textContent = (base * servingCount).toFixed(1);
        });
        document.getElementById('serving-count').textContent = servingCount;
    };

    document.getElementById('plus').addEventListener('click', () => {
        servingCount++;
        updateIngredients();
    });

    document.getElementById('minus').addEventListener('click', () => {
        if (servingCount > 1) {
            servingCount--;
            updateIngredients();
        }
    });

    updateIngredients(); // Initialize
</script>
@endsection
