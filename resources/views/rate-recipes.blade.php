<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Recipes - CookEase</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-title {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: #b73e3e;
            margin-bottom: 40px;
        }

        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .recipe-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .recipe-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .recipe-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .recipe-content {
            padding: 20px;
        }

        .recipe-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .recipe-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: #666;
            font-size: 14px;
        }

        .rating-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .rating-display {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stars {
            display: flex;
            gap: 2px;
        }

        .star {
            color: #ffd700;
            font-size: 16px;
        }

        .star.empty {
            color: #ddd;
        }

        .rating-text {
            font-weight: bold;
            color: #333;
        }

        .reviews-text {
            color: #666;
            font-size: 14px;
        }

        .rate-btn {
            background-color: #b73e3e;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .rate-btn:hover {
            background-color: #a03333;
        }

        .rate-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .user-rating {
            background-color: #e8f5e8;
            color: #2d5a2d;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            animation: slideIn 0.3s ease;
        }

        .modal-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .modal-subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .rating-stars {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .rating-star {
            font-size: 40px;
            color: #ddd;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .rating-star:hover,
        .rating-star.active {
            color: #ffd700;
            transform: scale(1.1);
        }

        .rating-feedback {
            font-size: 18px;
            font-weight: bold;
            color: #b73e3e;
            margin-bottom: 30px;
            min-height: 25px;
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
        }

        .modal-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cancel-btn {
            background-color: #f0f0f0;
            color: #333;
        }

        .cancel-btn:hover {
            background-color: #e0e0e0;
        }

        .submit-btn {
            background-color: #b73e3e;
            color: white;
        }

        .submit-btn:hover {
            background-color: #a03333;
        }

        .submit-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">CookEase</div>
        <nav class="navbar">
            <a href="/dashboard">Home</a>
            <a href="/favorites">Favorites</a>
            <a href="/rate-recipes" class="active">Rate Recipe</a>
            <a href="/chatbot">Cooking Ast</a>
            <a href="/settings">Settings</a>
        </nav>
    </div>

    <div class="container">
        <h1 class="page-title">Rate Our Recipes</h1>
        
        <div class="recipes-grid">
            @foreach($recipes as $recipe)
                <div class="recipe-card">
                    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}" class="recipe-image">
                    <div class="recipe-content">
                        <h3 class="recipe-name">{{ $recipe->name }}</h3>
                        
                        <div class="recipe-info">
                            <span>🔥 {{ $recipe->cal }} cal</span>
                            <span>⏱ {{ $recipe->time }} Min</span>
                            <span>📊 {{ $recipe->difficulty }}</span>
                        </div>

                        <div class="rating-section">
                            <div class="rating-display">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star {{ $i <= $recipe->average_rating ? '' : 'empty' }}"></i>
                                    @endfor
                                </div>
                                <span class="rating-text">{{ number_format($recipe->average_rating, 1) }}/5</span>
                                <span class="reviews-text">({{ $recipe->total_reviews }} reviews)</span>
                            </div>
                        </div>

                        @php
                            $userRating = $recipe->ratings->where('user_id', Auth::id())->first();
                        @endphp

                        @if($userRating)
                            <div class="user-rating">
                                You rated: {{ $userRating->rating }} stars ⭐
                            </div>
                        @else
                            <button class="rate-btn" onclick="openRatingModal({{ $recipe->recipe_id }}, '{{ $recipe->name }}')">
                                Rate this recipe
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Rating Modal -->
    <div id="ratingModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-title">Rate Recipe</h2>
            <p class="modal-subtitle">How would you rate <strong id="recipeName"></strong>?</p>
            
            <div class="rating-stars" id="ratingStars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star rating-star" data-rating="{{ $i }}" onclick="setRating({{ $i }})"></i>
                @endfor
            </div>
            
            <div class="rating-feedback" id="ratingFeedback">Select a rating</div>
            
            <div class="modal-buttons">
                <button class="modal-btn cancel-btn" onclick="closeRatingModal()">Cancel</button>
                <button class="modal-btn submit-btn" id="submitBtn" onclick="submitRating()" disabled>Submit Rating</button>
            </div>
        </div>
    </div>

    <script>
        let currentRecipeId = null;
        let selectedRating = 0;

        const ratingTexts = {
            1: "Poor 😞",
            2: "Fair 😐", 
            3: "Good 🙂",
            4: "Very Good 😊",
            5: "Excellent! 🤩"
        };

        function openRatingModal(recipeId, recipeName) {
            currentRecipeId = recipeId;
            document.getElementById('recipeName').textContent = recipeName;
            document.getElementById('ratingModal').style.display = 'block';
            resetRating();
        }

        function closeRatingModal() {
            document.getElementById('ratingModal').style.display = 'none';
            resetRating();
        }

        function setRating(rating) {
            selectedRating = rating;
            
            // Update star display
            const stars = document.querySelectorAll('.rating-star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });

            // Update feedback text
            document.getElementById('ratingFeedback').textContent = ratingTexts[rating];
            
            // Enable submit button
            document.getElementById('submitBtn').disabled = false;
        }

        function resetRating() {
            selectedRating = 0;
            document.querySelectorAll('.rating-star').forEach(star => {
                star.classList.remove('active');
            });
            document.getElementById('ratingFeedback').textContent = 'Select a rating';
            document.getElementById('submitBtn').disabled = true;
        }

        async function submitRating() {
            if (!selectedRating || !currentRecipeId) return;

            try {
                const response = await fetch(`/rate-recipe/${currentRecipeId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        rating: selectedRating
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Rating submitted successfully!');
                    location.reload(); // Refresh page to show updated rating
                } else {
                    alert(data.message || 'Error submitting rating');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error submitting rating');
            }

            closeRatingModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('ratingModal');
            if (event.target === modal) {
                closeRatingModal();
            }
        }
    </script>
</body>
</html>
