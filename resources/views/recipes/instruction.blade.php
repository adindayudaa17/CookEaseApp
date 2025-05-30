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
        max-width: 800px;
        margin: 40px auto;
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    h2 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    img.img-fluid {
        border-radius: 15px;
        width: 100%;
        height: auto;
        max-height: 300px;
        object-fit: cover;
        margin-bottom: 20px;
    }

    .card {
        border: none;
        border-radius: 12px;
        background-color: #fafafa;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .card-title {
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 20px;
    }

    .card-text {
        font-size: 16px;
        line-height: 1.6;
        color: #333;
        white-space: pre-line;
    }

    .btn-secondary {
        background-color: #ccc;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.3s;
        text-decoration: none;
        color: #333;
    }

    .btn-secondary:hover {
        background-color: #bbb;
        color: #000;
    }
</style>

{{-- Navbar --}}
<div class="header">
    <div class="logo">CookEase</div>
    <nav class="navbar">
        <a href="/dashboard">Home</a>
        <a href="/favorites">Favorites</a>
        <a href="/chatbot">Cooking Ast</a>
        <a href="/settings">Settings</a>
    </nav>
</div>

{{-- Main Content --}}
<div class="container">
    <h2>{{ $recipe->name }} - Cooking Instructions</h2>

    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}" class="img-fluid">

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
