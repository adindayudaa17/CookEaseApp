<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Name - Settings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 60px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #b73e3e;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-update {
            background-color: #b73e3e;
            color: white;
        }

        .btn-cancel {
            background-color: #ccc;
            color: #333;
            text-decoration: none;
            display: inline-block;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Edit Your Name</h2>

        <form action="{{ route('updateName') }}" method="POST">
            @csrf
            @method('PUT')

            <label for="name">New Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" required>

            @if ($errors->has('name'))
                <div class="error">{{ $errors->first('name') }}</div>
            @endif

            <div class="buttons">
                <button type="submit" class="btn btn-update">Update</button>
                <a href="{{ route('settings') }}" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

</body>
</html>
