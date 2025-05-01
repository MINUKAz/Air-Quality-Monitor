<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Register</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .title {
            text-align: center;
            font-size: 2rem;
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 2rem;
        }
        .input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #dcdde1;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #f8f9fc;
        }
        .button {
            width: 100%;
            padding: 0.75rem;
            background-color: #4475f2;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #2652d4;
        }
        .validation-error {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1 class="title">Register</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input type="text" 
                   name="name" 
                   class="input" 
                   placeholder="Name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus />
            @error('name')
                <div class="validation-error">{{ $message }}</div>
            @enderror

            <input type="email" 
                   name="email" 
                   class="input" 
                   placeholder="Email" 
                   value="{{ old('email') }}" 
                   required />
            @error('email')
                <div class="validation-error">{{ $message }}</div>
            @enderror

            <input type="password" 
                   name="password" 
                   class="input" 
                   placeholder="Password" 
                   required />
            @error('password')
                <div class="validation-error">{{ $message }}</div>
            @enderror

            <input type="password" 
                   name="password_confirmation" 
                   class="input" 
                   placeholder="Confirm Password" 
                   required />

            <button type="submit" class="button">Register</button>
        </form>
    </div>
</body>
</html>
