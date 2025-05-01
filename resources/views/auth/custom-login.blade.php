<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f2f5;
        }
        
        .login-container {
            background-color: white;
            border-radius: 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        
        .login-title {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 600;
            color: #222;
            margin-bottom: 30px;
        }
        
        .input-field {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #4285f4;
        }
        
        .sign-in-button {
            width: 50%;
            padding: 12px;
            background-color: #4285f4;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .sign-in-button:hover {
            background-color: #3367d6;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            display: none;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .toggle-form {
            background: none;
            border: none;
            color: #4285f4;
            cursor: pointer;
            font-size: 14px;
            text-decoration: underline;
        }

        .signup-container {
            display: none;
            background-color: white;
            border-radius: 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .signup-title {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 600;
            color: #222;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">Admin Login</h1>
        
        <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="input-field" 
                   placeholder="Enter Your Email Address" 
                   value="{{ old('email') }}"
                   required>

            <input type="password" 
                   name="password" 
                   id="password" 
                   class="input-field" 
                   placeholder="Password" 
                   required>

            @error('email')
                <p class="error-message" style="display: block;">{{ $message }}</p>
            @enderror

            @error('password')
                <p class="error-message" style="display: block;">{{ $message }}</p>
            @enderror

            <button type="submit" class="sign-in-button">Log In</button>
        </form>
    </div>

    <script>
        const errorMessage = document.getElementById('errorMessage');
        @if($errors->any())
            errorMessage.style.display = 'block';
        @endif
    </script>
</body>
</html>