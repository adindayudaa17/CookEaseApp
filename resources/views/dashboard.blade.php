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
            --primary-red: #8A2424; /* Warna utama dari Tailwind config-mu */
        }
        .bg-primary { background-color: var(--primary-red); }
        .text-primary { color: var(--primary-red); }
        .border-primary { border-color: var(--primary-red); }
        .hover\:bg-primary:hover { background-color: var(--primary-red); }

        /* Style untuk ikon hati */
        .favorite-icon-active {
            color: #ef4444; /* Tailwind's red-500 */
            fill: currentColor;
        }
        .favorite-icon-inactive {
            color: #6b7280; /* Tailwind's gray-500 */
        }
        .favorite-icon-inactive:hover {
            color: #ef4444; /* Merah saat hover jika tidak aktif */
        }
        .favorite-button { /* Common class for the button */
            position: absolute;
            top: 0.75rem; /* top-3 */
            right: 0.75rem; /* right-3 */
            padding: 0.5rem; /* p-2 */
            background-color: white;
            border-radius: 9999px; /* rounded-full */
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /* shadow-md */
            transition: background-color 0.15s ease-in-out;
        }
        .favorite-button:hover {
            background-color: #f9fafb; /* gray-50 */
        }
        .favorite-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(138, 36, 36, 0.5); /* Contoh focus ring, sesuaikan dengan warna primary-mu */
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
                    {{-- Pastikan nama route ini sudah benar --}}
                    <a href="{{ route('dashboard') }}" class="text-primary font-medium {{ request()->routeIs('dashboard') ? 'border-b-2 border-primary' : '' }}">Home</a>
                    <a href="{{ route('favorites.index') }}" class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('favorites.index') ? 'text-primary border-b-2 border-primary' : '' }}">Favorites</a>
                    <a href="{{ route('rate-recipes') }}" class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('rate-recipes') ? 'text-primary border-b-2 border-primary' : '' }}">Rate Recipe</a>
                    <a href="{{ route('chatbot.index') }}" class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('chatbot.index') ? 'text-primary border-b-2 border-primary' : '' }}">Cooking Assist</a>
                    <a href="{{ route('settings') }}" class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('settings') ? 'text-primary border-b-2 border-primary' : '' }}">Settings</a>
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
                <input type="text" id="searchInput" name="search" value="{{ $search ?? '' }}" placeholder="Search any recipes" class="pl-10 pr-10 w-full h-12 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <button type="button" id="clearSearch" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 h-5 w-5 {{ ($search ?? '') ? '' : 'hidden' }}" onclick="clearSearchInput()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                {{-- Kirim parameter category yang sedang aktif saat search --}}
                <input type="hidden" name="category" value="{{ $categoryFilter ?? 'all' }}">
                {{-- Kirim parameter show_all yang sedang aktif saat search --}}
                <input type="hidden" name="show_all" value="{{ $showAll ? '1' : '0' }}">
            </form>

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

        <div class="mb-8" id="recipes-section">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Categories</h3>
            <div class="flex flex-wrap gap-3">
                {{-- Pertahankan search query dan show_all saat ganti kategori --}}
                <a href="{{ route('dashboard', ['category' => 'all', 'search' => $search ?? '', 'show_all' => $showAll ? '1' : '0']) }}"
                   class="px-6 py-2 rounded-full font-medium {{ (!$categoryFilter || $categoryFilter === 'all') ? 'bg-primary text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    All
                </a>
                @php $categories = ['Breakfast', 'Lunch', 'Dinner', 'Dessert']; @endphp
                @foreach($categories as $catName)
                <a href="{{ route('dashboard', ['category' => $catName, 'search' => $search ?? '', 'show_all' => $showAll ? '1' : '0']) }}"
                   class="px-6 py-2 rounded-full font-medium {{ ($categoryFilter ?? '') === $catName ? 'bg-primary text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    {{ $catName }}
                </a>
                @endforeach
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">
                    Quick & Easy 
                    @if(isset($recipes))
                    <span class="text-sm font-normal text-gray-500">({{ $recipes->count() }} recipes)</span>
                    @endif
                </h3>
                {{-- Tombol View All / Show Less menggunakan link yang membawa parameter show_all --}}
                @if(isset($recipes) && $recipes->count() > 4) {{-- Hanya tampilkan jika resep lebih dari 4 --}}
                    @if($showAll)
                        <a href="{{ route('dashboard', ['category' => $categoryFilter ?? 'all', 'search' => $search ?? '', 'show_all' => '0']) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                            Show Less
                        </a>
                    @else
                        <a href="{{ route('dashboard', ['category' => $categoryFilter ?? 'all', 'search' => $search ?? '', 'show_all' => '1']) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            View All
                        </a>
                    @endif
                @endif
            </div>

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
            const form = searchInput.form;
            const categoryInput = form.querySelector('input[name="category"]');
            const showAllInput = form.querySelector('input[name="show_all"]');
            
            // Buat URL baru tanpa search, tapi pertahankan category dan show_all jika ada
            let currentUrl = new URL('{{ route('dashboard') }}');
            if (categoryInput && categoryInput.value !== 'all') {
                currentUrl.searchParams.set('category', categoryInput.value);
            }
            if (showAllInput && showAllInput.value === '1') {
                currentUrl.searchParams.set('show_all', '1');
            }
            window.location.href = currentUrl.toString();
        }

        document.getElementById('searchInput').addEventListener('input', function() {
            const clearButton = document.getElementById('clearSearch');
            clearButton.classList.toggle('hidden', this.value.length === 0);
            
            // Debounce search submission (opsional)
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500); // Submit setelah 500ms tidak ada ketikan baru
        });

        // Hapus event listener keypress 'Enter' jika sudah ada auto-submit dari input
        // document.getElementById('searchInput').addEventListener('keypress', function(e) {
        //     if (e.key === 'Enter') {
        //         e.preventDefault();
        //         this.form.submit();
        //     }
        // });

        // JavaScript untuk toggleRecipeView (View All / Show Less) di Quick & Easy
        // Ini hanya mengontrol tampilan di sisi klien. Pengambilan data penuh/terbatas diatur oleh parameter show_all di URL.
        // Kode toggleRecipeView dari HTML-mu sebelumnya (yang menggunakan button) bisa disesuaikan di sini
        // jika kamu ingin tetap menggunakan JavaScript untuk show/hide client-side.
        // Namun, dengan parameter URL 'show_all', logika utamanya ada di backend.
        // Jika kamu mau pakai JavaScript untuk ini, pastikan konsisten dengan jumlah resep yang di-load.
        // Untuk sekarang, saya akan mengasumsikan logika 'hidden recipe-extra' dari Blade sudah cukup
        // berdasarkan variabel $showAll.

        // Jika kamu ingin mengimplementasikan AJAX untuk tombol favorit,
        // kode JavaScript yang sudah kita diskusikan bisa ditambahkan di sini.
        // Jangan lupa <meta name="csrf-token" content="{{ csrf_token() }}"> di <head>.
    </script>
</body>
</html>