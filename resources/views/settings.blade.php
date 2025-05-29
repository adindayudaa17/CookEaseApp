<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
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
        max-width: 1000px; 
        margin: 40px auto;
        padding: 0 20px;
        }

        .welcome {
        background: white;
        padding: 50px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        text-align: center;
        }   

        .welcome h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .welcome p {
            font-size: 14px;
            color: #b73e3e;
            margin-bottom: 30px;
        }

        .section-title {
            text-align: left;
            font-size: 18px;
            font-weight: bold;
            color: #b73e3e;
            margin-bottom: 10px;
            border-bottom: 2px solid #b73e3e;
            display: inline-block;
        }

        .info-group {
            text-align: left;
            margin-top: 20px;
        }

        .info-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info-value {
            flex: 1; 
            color: #b73e3e;
            text-decoration: underline;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .info-value.plain {
            justify-content: space-between;
        }     

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }

        .edit-btn {
            background-color: #f1dddd;
            color: #b73e3e;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="logo">CookEase</div>
        <nav class="navbar">
            <a href="/dashboard">Home</a>
            <a href="/favorites">Favorites</a>
            <a href="/meal-plan">Meal Plan</a>
            <a href="/cooking-ast">Cooking Ast</a>
            <a href="/settings" class="active">Settings</a>
        </nav>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="welcome">
            <h1>{{ Auth::user()->name }}</h1>
            <p>Joined in {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('M,Y') }}</p>

            <div class="section-title">Settings</div>

            <div class="info-group">
                <div class="info-label">Email</div>
                <div class="info-value">{{ Auth::user()->email }}</div>
            </div>

            <div class="info-group">
                <div class="info-label">Name</div>
                <div class="info-value plain">{{ Auth::user()->name }} 
                    <a href="{{ route('edit-name') }}">
                    <button class="edit-btn">Edit</button>
                </a>
                </div>
            </div>

            <div class="actions">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
