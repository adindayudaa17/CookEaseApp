@extends('layouts.app')

@section('content')
<style>
     * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            background: white;
            padding: 20px 40px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #b73e3e;
            flex-shrink: 0;
        }

        /* Navbar di header */
        .navbar {
            display: flex;
            gap: 25px;
            font-weight: normal;
            font-size: 16px;
        }

        .navbar a {
            text-decoration: none;
            color: #444;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }

        .navbar a:hover {
            background-color: #f0f0f0;
        }

        .navbar a.active {
            color: #b73e3e;
            font-weight: bold;
        }

    .container {
        max-width: 1200px;
        margin: 40px auto;
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    h2 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    img.img-fluid {
        border-radius: 15px;
        width: 100%;
        height: auto;
        max-height: 300px;
        object-fit: cover;
        margin-bottom: 20px;
    }

    .mb-3 p {
        font-size: 16px;
        color: #555;
        margin-bottom: 5px;
    }

    .mb-3 label {
        font-weight: 500;
        margin-bottom: 5px;
        display: block;
    }

    ul#ingredients-list {
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }

    .ingredient-item {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 16px;
        color: #333;
        margin-bottom: 10px;
    }

    .ingredient-item img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }

    .btn {
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.3s;
    }

    .btn-outline-secondary {
        border: 1px solid #ccc;
        color: #444;
        background-color: transparent;
    }

    .btn-outline-secondary:hover {
        background-color: #eee;
    }

    .btn-danger {
        background-color: #b73e3e;
        border: none;
        color: white;
    }

    .btn-danger:hover {
        background-color: #a03030;
    }

    .d-flex {
        display: flex;
        align-items: center;
    }

    .gap-2 {
        gap: 10px;
    }

    .mt-4 {
        margin-top: 30px;
    }

    .text-center {
        text-align: center;
    }

    .rate-btn {
        background-color: #b73e3e;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 15px;
        cursor: pointer;
        float: right;
    }

    .rate-btn:hover {
        background-color: #9c2f2f;
    }

</style>
 <div class="header">
        <div class="logo">CookEase</div>
        <nav class="navbar">
            <a href="/dashboard">Home</a>
            <a href="/favorites">Favorites</a>
            <a href="/chatbot" >Cooking Ast</a>
            <a href="/settings">Settings</a>
        </nav>
    </div>   

<div class="container">
    
    <h2>{{ $recipe->name }}</h2>

    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}" class="img-fluid">

    <div class="mb-3">
        <p>🔥 {{ $recipe->cal }} cal &nbsp; ⏱ {{ $recipe->time }} Min</p>
        <p>⭐ {{ $recipe->rate }}/5 ({{ $recipe->reviews }} Reviews)</p>
    </div>

    <div class="mb-3">
        <label for="servings">How many servings?</label>
        <div class="d-flex gap-2">
            <button id="minus" class="btn btn-sm btn-outline-secondary">-</button>
            <span id="serving-count">1</span>
            <button id="plus" class="btn btn-sm btn-outline-secondary">+</button>
        </div>
    </div>

    <h5>Ingredients</h5>
    <ul id="ingredients-list">
        @foreach($recipe->ingredients_name as $index => $name)
            <li data-base-amount="{{ $recipe->ingredients_amount[$index] ?? 0 }}" class="ingredient-item">
                <img src="{{ $recipe->ingredients_image[$index] ?? '' }}" alt="{{ $name }}">
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
