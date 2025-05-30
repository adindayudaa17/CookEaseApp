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

    .instruction-container {
        max-width: 900px;
        margin: 24px auto;
        padding: 0 24px;
    }

    .instruction-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .instruction-hero {
        position: relative;
        height: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .instruction-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .instruction-content {
        padding: 32px;
    }

    .instruction-header {
        margin-bottom: 32px;
    }

    .instruction-title {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .instruction-subtitle {
        color: #64748b;
        font-size: 16px;
    }

    .instructions-section {
        margin-bottom: 32px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .instructions-content {
        background: #f8fafc;
        border-radius: 16px;
        padding: 24px;
        border-left: 4px solid #b73e3e;
    }

    .instruction-text {
        font-size: 16px;
        line-height: 1.8;
        color: #374151;
        white-space: pre-line;
    }

    .back-button-container {
        display: flex;
        justify-content: flex-start;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        padding: 12px 20px;
        background: #f8fafc;
        border-radius: 12px;
        transition: all 0.2s ease;
        font-size: 14px;
        border: 1px solid #e2e8f0;
    }

    .back-btn:hover {
        background: #f1f5f9;
        color: #374151;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .back-btn svg {
        width: 18px;
        height: 18px;
    }

    .cooking-icon {
        color: #b73e3e;
    }
</style>

<div class="header">
    <div class="logo">CookEase</div>
    <nav class="navbar">
        <a href="/dashboard">Home</a>
        <a href="/favorites">Favorites</a>
        <a href="/chatbot">Cooking Ast</a>
        <a href="/settings">Settings</a>
    </nav>
</div>

<div class="instruction-container">
    <div class="instruction-card">
        <div class="instruction-hero">
            <img src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}" class="instruction-image">
        </div>

        <div class="instruction-content">
            <div class="instruction-header">
                <h1 class="instruction-title">{{ $recipe->name }}</h1>
                <p class="instruction-subtitle">Cooking Instructions</p>
            </div>

            <div class="instructions-section">
                <h2 class="section-title">
                    <span class="cooking-icon">👨‍🍳</span>
                    Step-by-Step Instructions
                </h2>
                <div class="instructions-content">
                    <p class="instruction-text">{{ $recipe->instructions }}</p>
                </div>
            </div>

            <div class="back-button-container">
                <a href="{{ route('recipes.show', $recipe->recipe_id) }}" class="back-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Recipe
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
