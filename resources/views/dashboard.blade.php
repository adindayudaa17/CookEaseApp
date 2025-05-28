<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - CookEase</title>
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
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
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
            flex-grow: 1;
            justify-content: flex-end;
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

        .logout-btn {
            padding: 10px 20px;
            background-color: #b73e3e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .content {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">CookEase</div>
        
        <nav class="navbar">
            <a href="dashboard" class="active">Home</a>
            <a href="/favorites">Favorites</a>
            <a href="/meal-plan">Meal Plan</a>
            <a href="/cooking-ast">Cooking Ast</a>
            <a href="/settings">Settings</a>
        </nav>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="content">
        <div class="welcome">
            <h1>Welcome, {{ Auth::user()->name }}!</h1>
            <p>You are successfully logged in to CookEase.</p>
            <p><strong>Username:</strong> {{ Auth::user()->username }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
        </div>
    </div>
</body>
</html>
