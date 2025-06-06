<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- CSRF Token untuk AJAX/Form POST --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CookEase - What are you cooking today?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .recipe-card {
            transition: transform 0.2s;
        }
        .recipe-card:hover {
            transform: translateY(-4px);
        }
        :root {
            --primary-red: #8A2424; 
        }
        .bg-primary { background-color: var(--primary-red); }
        .text-primary { color: var(--primary-red); }
        .border-primary { border-color: var(--primary-red); }
        .hover\:bg-primary:hover { background-color: var(--primary-red); }

        .favorite-icon-active {
            color: #ef4444; 
            fill: currentColor;
        }
        .favorite-icon-inactive {
            color: #6b7280; 
        }
        .favorite-icon-inactive:hover {
            color: #ef4444; 
        }
        .favorite-button { 
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            padding: 0.5rem;
            background-color: white;
            border-radius: 9999px; 
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); 
            transition: background-color 0.15s ease-in-out;
        }
        .favorite-button:hover {
            background-color: #f9fafb; 
        }
        .favorite-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(138, 36, 36, 0.5); 
        }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-primary cookease-brand">CookEase</a>
                </div>
                <nav class="flex space-x-8">
                    <a href="{{ route('dashboard') }}" class="text-primary font-medium">Home</a>
                    <a href="{{ route('favorites.index') }}" class="text-gray-600 hover:text-gray-900">Favorites</a>
                    <a href="{{ route('rate-recipes') }}" class="text-gray-600 hover:text-gray-900">Rate Recipe</a>
                    <a href="{{ route('chatbot') }}" class="text-gray-600 hover:text-gray-900">Cooking Assist</a>
                    <a href="{{ route('settings') }}" class="text-gray-600 hover:text-gray-900">Settings</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                What are you<br>cooking today?
            </h2>

            <form method="GET" action="{{ route('dashboard') }}" class="relative max-w-2xl mb-8">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Search any recipes" class="pl-10 pr-10 w-full h-12 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <button type="button" id="clearSearch" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 h-5 w-5 {{ request('search') ? '' : 'hidden' }}" onclick="clearSearchInput()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <input type="hidden" name="category" value="{{ request('category', 'all') }}">
            </form>

            <!-- Featured Banner -->
            <div class="bg-primary text-white overflow-hidden relative h-44 sm:h-52 rounded-xl">
                <div class="flex h-full relative">
                    <div class="p-6 sm:p-8 flex-1 z-10 flex flex-col justify-center">
                        <h3 class="text-xl sm:text-3xl font-bold mb-4">
                            Cook the best<br>recipes at home
                        </h3>
                        <button onclick="scrollToRecipes()" class="bg-white text-primary hover:bg-gray-100 px-6 py-2 rounded-full font-medium w-fit">
                            Explore
                        </button>
                    </div>
                    <div class="absolute right-8 top-0 h-full w-1/4">
                        <img src="https://purepng.com/public/uploads/large/purepng.com-chefcheftrained-professional-cookfood-preparationkitchenchefsexperienced-1421526669538jccuo.png" alt="Chef cooking" class="h-full w-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="mb-8" id="recipes-section">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Categories</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard', ['category' => 'all', 'search' => request('search')]) }}"
                   class="px-6 py-2 rounded-full font-medium {{ (!request('category') || request('category') === 'all') ? 'bg-primary text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    All
                </a>
                <a href="{{ route('dashboard', ['category' => 'Breakfast', 'search' => request('search')]) }}"
                   class="px-6 py-2 rounded-full font-medium {{ request('category') === 'Breakfast' ? 'bg-primary text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    Breakfast
                </a>
                <a href="{{ route('dashboard', ['category' => 'Lunch', 'search' => request('search')]) }}"
                   class="px-6 py-2 rounded-full font-medium {{ request('category') === 'Lunch' ? 'bg-primary text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    Lunch
                </a>
                <a href="{{ route('dashboard', ['category' => 'Dinner', 'search' => request('search')]) }}"
                   class="px-6 py-2 rounded-full font-medium {{ request('category') === 'Dinner' ? 'bg-primary text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    Dinner
                </a>
                <a href="{{ route('dashboard', ['category' => 'Dessert', 'search' => request('search')]) }}"
                   class="px-6 py-2 rounded-full font-medium {{ request('category') === 'Dessert' ? 'bg-primary text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    Dessert
                </a>
            </div>
        </div>

        <!-- Quick & Easy Section -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">
                    Quick & Easy 
                    @if(isset($recipes))
                    <span class="text-sm font-normal text-gray-500">({{ $recipes->count() }} recipes)</span>
                    @endif
                </h3>
                <button id="viewAllBtn" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors" onclick="toggleRecipeView()">
                    View All
                </button>
                <button id="showLessBtn" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors hidden" onclick="toggleRecipeView()">
                    Show Less
                </button>
            </div>

            <!-- Recipe Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="recipeGrid">
                @if(isset($recipes) && $recipes->count() > 0)
                    @foreach($recipes as $index => $recipe)
                    {{-- Logika class 'hidden' berdasarkan $showAll dan $index --}}
                    <div class="recipe-card bg-white rounded-lg shadow-md overflow-hidden {{ (!$showAll && $index >= 4) ? 'hidden' : '' }}">
                        <div class="h-48 bg-gray-200 relative">
                            <a href="{{ route('recipes.show', $recipe->recipe_id) }}">
                                <img src="{{ $recipe->image_url ?: 'https://placehold.co/300x200?text=No+Image' }}" alt="{{ htmlspecialchars($recipe->name) }}" class="w-full h-full object-cover">
                            </a>
                            
                            {{-- TOMBOL FAVORIT (TOGGLE) --}}
                            @auth
                            <form method="POST" action="{{ route('favorites.toggle') }}" class="favorite-button z-10"> {{-- z-10 agar di atas card lain jika ada overlap --}}
                                @csrf
                                <input type="hidden" name="recipe_id" value="{{ $recipe->recipe_id }}">
                                <button type="submit" 
                                        title="{{ $recipe->is_favorited ? 'Remove from Favorites' : 'Add to Favorites' }}">
                                    <svg class="w-5 h-5 favorite-icon {{ $recipe->is_favorited ? 'favorite-icon-active' : 'favorite-icon-inactive' }}"
                                         fill="{{ $recipe->is_favorited ? 'currentColor' : 'none' }}"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="favorite-button" title="Login to add to favorites">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </a>
                            @endauth
                        </div>
                        {{-- Card content sekarang di dalam tag <a> agar keseluruhan area card di bawah gambar bisa diklik --}}
                        <a href="{{ route('recipes.show', $recipe->recipe_id) }}" class="block p-4">
                            {{-- Pastikan ada relasi 'category' di model Recipe yang me-load nama kategori --}}
                            <span class="inline-block bg-red-100 text-primary text-xs px-2 py-1 rounded-full mb-2">{{ $recipe->category->name ?? 'Recipe' }}</span>
                            <h4 class="font-semibold text-gray-900 mb-1">{{ htmlspecialchars($recipe->name) }}</h4>
                            <p class="text-sm text-gray-600">{{ $recipe->cal ?? 'N/A' }} cal • {{ $recipe->time ?? 'N/A' }} min</p>
                        </a>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-500">No recipes found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function scrollToRecipes() {
            document.getElementById('recipes-section').scrollIntoView({ behavior: 'smooth' });
        }

        function clearSearchInput() {
            const searchInput = document.getElementById('searchInput');
            const clearButton = document.getElementById('clearSearch');

            searchInput.value = '';
            clearButton.classList.add('hidden');
            window.location.href = '{{ route('dashboard') }}';
        }

        document.getElementById('searchInput').addEventListener('input', function() {
            const clearButton = document.getElementById('clearSearch');
            if (this.value.length > 0) {
                clearButton.classList.remove('hidden');
            } else {
                clearButton.classList.add('hidden');
            }
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500); // Submit setelah 500ms tidak ada ketikan baru
        });

        function toggleRecipeView() {
            const extraRecipes = document.querySelectorAll('.recipe-extra');
            const viewAllBtn = document.getElementById('viewAllBtn');
            const showLessBtn = document.getElementById('showLessBtn');

            if (viewAllBtn.classList.contains('hidden')) {
                // Show Less
                extraRecipes.forEach(recipe => {
                    recipe.classList.add('hidden');
                });
                viewAllBtn.classList.remove('hidden');
                showLessBtn.classList.add('hidden');
            } else {
                // View All
                extraRecipes.forEach(recipe => {
                    recipe.classList.remove('hidden');
                });
                viewAllBtn.classList.add('hidden');
                showLessBtn.classList.remove('hidden');
            }
        }

    </script>
</body>
</html>