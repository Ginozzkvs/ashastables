<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ASHA Stables</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            color: #e0e0e0;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cormorant Garamond', serif;
            letter-spacing: -1px;
            font-weight: 600;
        }

        .login-container {
            width: 100%;
            max-width: 600px;
            padding: 3rem;
        }

        .login-card {
            background: #1a1f2e;
            border: 1px solid #d4af37;
            border-radius: 0.5rem;
            padding: 4rem 3rem;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.3);
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo h1 {
            color: #d4af37;
            font-size: 2.8rem;
            margin-bottom: 0.5rem;
        }

        .logo p {
            color: #9ca3af;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        label {
            display: block;
            color: #d4af37;
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 1rem 1.25rem;
            background: #0f1419;
            border: 1px solid #d4af37;
            border-radius: 0.375rem;
            color: #e0e0e0;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #fcd34d;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #6b7280;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 2.5rem;
            gap: 0.75rem;
        }

        input[type="checkbox"] {
            width: 22px;
            height: 22px;
            accent-color: #d4af37;
            cursor: pointer;
        }

        .checkbox-group label {
            margin: 0;
            color: #9ca3af;
            font-weight: 400;
            font-size: 1rem;
            cursor: pointer;
        }

        .error-message {
            color: #fca5a5;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .forgot-link {
            color: #d4af37;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #fcd34d;
            text-decoration: underline;
        }

        .btn {
            padding: 1rem 2.5rem;
            border-radius: 0.375rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-login {
            background: #d4af37;
            color: #0f1419;
            flex: 1;
        }

        .btn-login:hover {
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
            transform: translateY(-2px);
        }

        .status-message {
            background: #065f46;
            border: 1px solid #10b981;
            color: #86efac;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        @media (max-width: 640px) {
            .login-container {
                padding: 1rem;
            }

            .login-card {
                padding: 2rem 1.5rem;
            }

            .logo h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo">
                <h1>ASHA Stables</h1>
                <p>Luxury Resort Management System</p>
            </div>

            <!-- Session Status -->
            @if ($status = session('status'))
                <div class="status-message">
                    {{ $status }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="admin@example.com"
                        required 
                        autofocus 
                        autocomplete="username"
                    >
                    @if ($errors->has('email'))
                        <div class="error-message">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        placeholder="••••••••"
                        required 
                        autocomplete="current-password"
                    >
                    @if ($errors->has('password'))
                        <div class="error-message">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="checkbox-group">
                    <input id="remember" type="checkbox" name="remember">
                    <label for="remember">Remember me</label>
                </div>

                <!-- Actions -->
                <div class="form-footer">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot password?
                        </a>
                    @endif
                    <button type="submit" class="btn btn-login">
                        Login
                    </button>
                </div>
            </form>

            <!-- Back to Home -->
            <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(212, 175, 55, 0.2);">
                <a href="/" style="color: #9ca3af; text-decoration: none; font-size: 0.9rem; transition: color 0.3s ease;" onmouseover="this.style.color='#d4af37'" onmouseout="this.style.color='#9ca3af'">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
