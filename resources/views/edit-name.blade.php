<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Name</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 400px;
        }

        h2 {
            margin-bottom: 20px;
            color: #b73e3e;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #b73e3e;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #b73e3e;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Name</h2>
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            <label for="name">New Name</label>
            <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required>

            <button type="submit">Update</button>
        </form>

        <a href="{{ route('settings') }}">← Back to Settings</a>
    </div>
</body>
</html>
@if(session('success'))
    <p style="color: green; margin-top: 10px;">{{ session('success') }}</p>
@endif
