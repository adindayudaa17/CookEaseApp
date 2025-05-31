<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - CookEase</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            text-align: center;
            background: white;
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 48px;
            font-weight: bold;
            color: #b73e3e;
            margin-bottom: 20px;
        }

        .subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }

        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .btn {
            padding: 15px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #b73e3e;
            color: white;
        }

        .btn-secondary {
            background-color: white;
            color: #b73e3e;
            border: 2px solid #b73e3e;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">CookEase</div>
        <div class="subtitle">Your cooking companion for delicious recipes</div>

        <div class="buttons">
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            <a href="{{ route('signup') }}" class="btn btn-secondary">Sign Up</a>
        </div>
    </div>
</body>
</html>
