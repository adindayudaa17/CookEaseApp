<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CookEase - My Favorites</title>
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
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card {
            display: flex;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            align-items: center;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card img {
            width: 130px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
            flex-shrink: 0;
        }
        .card-content {
            flex: 1;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .card-title a {
            text-decoration: none;
            color: inherit;
        }
        .card-title a:hover {
            text-decoration: underline;
            color: #b73e3e;
        }
        .card-details {
            margin-top: 5px;
            color: #555;
            font-size: 0.9em;
        }
        .delete-btn-container {
            margin-left: auto;
            padding-left: 15px;
            flex-shrink: 0;
        }
        .delete-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }
        .delete-btn:hover {
            background-color: #f0f0f0;
        }
        .delete-btn img {
            width: 24px;
            height: 24px;
            display: block;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
            text-align: center;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
        .no-favorites {
            padding: 40px 20px;
            text-align: center;
            color: #777;
            background-color: #fff;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .test-info {
            text-align: center;
            font-style: italic;
            margin-bottom: 20px;
            color: #777;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">CookEase</div>
        <div class="navbar">
            <a href="/dashboard">Home</a>
            <a href="/favorites" class="active">Favorites</a>
            <a href="/rate-recipes">Rate Recipe</a>
            <a href="/chatbot">Cooking Ast</a>
            <a href="/settings">Settings</a>
        </div>
    </div>

    <div class="container">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @forelse($favorites as $recipeId => $recipeData)
            <div class="card">
                <a href="{{ route('recipes.show', $recipeId) }}">
                    <img src="{{ $recipeData['image'] ?: 'https://placehold.co/130x100/e0e0e0/777?text=No+Image' }}" alt="{{ htmlspecialchars($recipeData['title']) }}">
                </a>
                <div class="card-content">
                    <div class="card-title">
                        <a href="{{ route('recipes.show', $recipeId) }}">
                            {{ htmlspecialchars($recipeData['title']) }}
                        </a>
                    </div>
                    <div class="card-details">
                        <span>🔥 {{ $recipeData['cal'] ?? 'N/A' }} cal</span> &nbsp;&nbsp; • &nbsp;&nbsp; <span>⏱ {{ $recipeData['time'] ?? 'N/A' }} Min</span>
                    </div>
                </div>
                <div class="delete-btn-container">
                    <form method="POST" action="{{ route('favorites.destroy', $recipeId) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" title="Hapus dari favorit">
                            <img src="https://cdn-icons-png.flaticon.com/512/1214/1214428.png" alt="Delete">
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="no-favorites">
                <p>
                    @isset($viewing_for_user_id)
                        User ID {{ $viewing_for_user_id }} tidak memiliki resep favorit saat ini.
                    @else
                        Anda tidak memiliki resep favorit saat ini.
                    @endisset
                </p>
            </div>
        @endforelse
    </div>
</body>
</html>