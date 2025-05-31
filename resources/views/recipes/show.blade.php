@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: #fafafa;
        line-height: 1.6;
    }

    .header {
        background: white;
        padding: 20px 40px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        width: 100%;
        position: sticky;
        top: 0;
        z-index: 1000;
        backdrop-filter: blur(10px);
    }

    .logo {
        font-size: 24px;
        font-weight: 700;
        color: #b73e3e;
        flex-shrink: 0;
    }

    .navbar {
        display: flex;
        gap: 32px;
        font-weight: 500;
        font-size: 15px;
    }

    .navbar a {
        text-decoration: none;
        color: #64748b;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .navbar a:hover {
        background-color: #f1f5f9;
        color: #334155;
    }

    .navbar a.active {
        color: #b73e3e;
        font-weight: 600;
        background-color: #fef2f2;
    }

    .recipe-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px;
    }

    .recipe-hero {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
    }

    .hero-image-container {
        position: relative;
        height: 400px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .back-button {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 10;
    }

    .back-button a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        font-weight: 500;
        padding: 12px 16px;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .back-button a:hover {
        background: rgba(0, 0, 0, 0.5);
        transform: translateY(-1px);
    }

    .back-button svg {
        width: 18px;
        height: 18px;
    }

    .hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .recipe-content {
        padding: 32px;
    }

    .recipe-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .recipe-title {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .recipe-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        color: #64748b;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .rating {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #f59e0b;
        font-weight: 500;
    }

    .recipe-stats {
        display: flex;
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        color: #475569;
    }

    .servings-section {
        margin-bottom: 32px;
    }

    .servings-label {
        font-size: 16px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 12px;
        display: block;
    }

    .servings-control {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #f8fafc;
        padding: 8px;
        border-radius: 12px;
        width: fit-content;
    }

    .serving-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 600;
        color: #64748b;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .serving-btn:hover {
        background: #e2e8f0;
        transform: translateY(-1px);
    }

    .serving-count {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        min-width: 40px;
        text-align: center;
    }

    .ingredients-section {
        margin-bottom: 32px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
    }

    .ingredients-list {
        list-style: none;
        padding: 0;
        display: grid;
        gap: 16px;
    }

    .ingredient-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 16px;
        transition: all 0.2s ease;
    }

    .ingredient-item:hover {
        background: #f1f5f9;
        transform: translateY(-1px);
    }

    .ingredient-image {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .ingredient-info {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ingredient-name {
        font-weight: 500;
        color: #374151;
        font-size: 15px;
    }

    .ingredient-amount {
        font-weight: 600;
        color: #b73e3e;
        font-size: 14px;
    }

    .start-cooking-btn {
        width: 100%;
        padding: 16px 24px;
        background: linear-gradient(135deg, #b73e3e 0%, #991b1b 100%);
        color: white;
        border: none;
        border-radius: 16px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(183, 62, 62, 0.3);
    }

    .start-cooking-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(183, 62, 62, 0.4);
    }

    .favorite-btn {
        width: 48px;
        height: 48px;
        border: none;
        background: #f8fafc;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-left: 16px;
    }

    .favorite-btn:hover {
        background: #fef2f2;
        transform: scale(1.05);
    }

    .favorite-btn svg {
        width: 20px;
        height: 20px;
        color: #64748b;
    }

    .favorite-btn:hover svg {
        color: #b73e3e;
    }
</style>

<div class="header">
    <div class="logo cookease-brand">CookEase</div>
    <nav class="navbar">
        <a href="{{ route('dashboard') }}">Home</a>
        <a href="{{ route('favorites.index') }}">Favorites</a>
        <a href="{{ route('rate-recipes') }}">Rate Recipe</a>
        <a href="{{ route('chatbot') }}">Cooking Assistant</a>
        <a href="{{ route('settings') }}">Settings</a>
    </nav>
</div>

<div class="recipe-container">
    <div class="recipe-hero">
        <div class="hero-image-container">
            <!-- Back Button positioned over the image -->
            <div class="back-button">
                <a href="{{ route('dashboard') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Home
                </a>
            </div>
            <img src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}" class="hero-image">
        </div>

        <div class="recipe-content">
            <div class="recipe-header">
                <div>
                    <h1 class="recipe-title">{{ $recipe->name }}</h1>
                    <div class="recipe-meta">
                        <div class="rating">
                            <span>⭐</span>
                            <span>{{ $recipe->rate }}/5 ({{ $recipe->reviews }} Reviews)</span>
                        </div>
                    </div>
                </div>
                <button class="favorite-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
            </div>

            <div class="recipe-stats">
                <div class="stat-item">
                    <span>🔥</span>
                    <span>{{ $recipe->cal }} cal</span>
                </div>
                <div class="stat-item">
                    <span>⏱</span>
                    <span>{{ $recipe->time }} Min</span>
                </div>
            </div>

            <div class="servings-section">
                <label class="servings-label">How many servings?</label>
                <div class="servings-control">
                    <button id="minus" class="serving-btn">−</button>
                    <span id="serving-count" class="serving-count">1</span>
                    <button id="plus" class="serving-btn">+</button>
                </div>
            </div>

            <div class="ingredients-section">
                <h2 class="section-title">Ingredients</h2>
                <ul id="ingredients-list" class="ingredients-list">
                    @if(isset($recipe->ingredients_name))
                        @php
                            $ingredientNames = is_array($recipe->ingredients_name)
                                ? $recipe->ingredients_name
                                : json_decode($recipe->ingredients_name, true) ?? [];

                            $ingredientAmounts = is_array($recipe->ingredients_amount)
                                ? $recipe->ingredients_amount
                                : json_decode($recipe->ingredients_amount, true) ?? [];

                            $ingredientImages = isset($recipe->ingredients_image) ? (is_array($recipe->ingredients_image)
                                ? $recipe->ingredients_image
                                : json_decode($recipe->ingredients_image, true) ?? []) : [];
                        @endphp

                        @foreach($ingredientNames as $index => $name)
                            <li data-base-amount="{{ $ingredientAmounts[$index] ?? 0 }}" class="ingredient-item">
                                <img src="{{ isset($ingredientImages[$index]) ? $ingredientImages[$index] : '/placeholder.svg?height=48&width=48' }}" alt="{{ $name }}" class="ingredient-image">
                                <div class="ingredient-info">
                                    <span class="ingredient-name">{{ $name }}</span>
                                    <span class="ingredient-amount">
                                        <span class="amount">{{ $ingredientAmounts[$index] ?? 0 }}</span> g
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="ingredient-item">
                            <img src="/placeholder.svg?height=48&width=48" alt="Ingredient" class="ingredient-image">
                            <div class="ingredient-info">
                                <span class="ingredient-name">No ingredients listed</span>
                                <span class="ingredient-amount">0 g</span>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>

            <a href="{{ route('recipes.instruction', $recipe->recipe_id) }}" class="start-cooking-btn">
                <span>Start Cooking</span>
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<script>
    let servingCount = 1;

    const updateIngredients = () => {
        document.querySelectorAll('.ingredient-item').forEach(item => {
            const base = parseFloat(item.dataset.baseAmount || 0);
            const amount = item.querySelector('.amount');
            if (amount) {
                amount.textContent = (base * servingCount).toFixed(1);
            }
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
