<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CookEase - My Favorites</title>
    {{-- Style CSS kamu yang sudah ada --}}
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; background: #f8f8f8; margin: 0; }
        header { display: flex; justify-content: space-between; align-items: center; background-color: #fff; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom:30px; }
        nav a { margin: 0 10px; text-decoration: none; color: black; font-weight: bold; }
        nav a.active { color: #c0392b; }
        .logo { font-size: 24px; font-weight: bold; color: #c0392b; }
        .container { max-width: 900px; margin: 0 auto; padding: 0 15px; }
        .card { display: flex; background: #eee; border-radius: 10px; overflow: hidden; margin-bottom: 20px; align-items: center; padding: 10px; }
        .card img { width: 130px; height: 100px; object-fit: cover; border-radius: 10px; margin-right: 20px; }
        .card-content { flex: 1; }
        .card-title { font-size: 18px; font-weight: bold; color: #333; margin-bottom: 5px; }
        .card-title a { text-decoration: none; color: inherit; } /* Style untuk link judul */
        .card-title a:hover { text-decoration: underline; color: #c0392b; } /* Hover effect */
        .card-details { margin-top: 5px; color: #555; font-size: 0.9em; }
        .delete-btn-container { margin-left: auto; padding-left: 15px; }
        .delete-btn { background: none; border: none; cursor: pointer; padding: 5px; }
        .delete-btn img { width: 24px; height: 24px; display: block; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; text-align: center; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .alert-info { color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb; }
        .no-favorites { padding: 20px; text-align: center; color: #777; background-color: #fff; border-radius: 8px; margin-top: 20px; }
    </style>
</head>
<body>
    <header>
        <div class="logo">CookEase</div>
        <nav>
            <a href="/dashboard">Home</a>
            <a href="/favorites" class="active">Favorites</a>
            <a href="/rate-recipes">Rate Recipe</a>
            <a href="/chatbot">Cooking Ast</a>
            <a href="/settings">Settings</a>
        </nav>
    </header>

    <div class="container">
        @isset($viewing_for_user_id)
            <p style="text-align: center; font-style: italic; margin-bottom: 20px; color: #777;">
                Menampilkan favorit untuk User ID: {{ $viewing_for_user_id }} (Mode Tes)
            </p>
        @endisset

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
                {{-- Membuat gambar juga bisa diklik (opsional) --}}
                <a href="{{ route('recipes.show', $recipeId) }}">
                    <img src="{{ $recipeData['image'] ?: 'https://placehold.co/130x100/e0e0e0/777?text=No+Image' }}" alt="{{ htmlspecialchars($recipeData['title']) }}">
                </a>
                <div class="card-content">
                    <div class="card-title">
                        {{-- Membuat judul resep menjadi link --}}
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