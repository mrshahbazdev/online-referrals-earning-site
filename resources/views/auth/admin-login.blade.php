<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* General Styling */
        :root {
            --bg-dark: #111827;
            --bg-card: #1E293B;
            --bg-input: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-color: #facc15;
            --border-color: #475569;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        /* Login Card */
        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: var(--bg-card);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header .icon {
            font-size: 3rem;
            color: var(--accent-color);
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Form Styling */
        .input-group {
            margin-bottom: 1.25rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.25rem;
        }

        .input-field {
            width: 100%;
            padding: 12px 12px 12px 45px; /* Left padding for icon */
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-field::placeholder {
            color: var(--text-secondary);
        }

        .input-field:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.3);
        }

        /* Extra Options */
        .extra-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
        }

        #remember {
            width: 16px;
            height: 16px;
            accent-color: var(--accent-color);
        }

        .forgot-password a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Login Button */
        .login-button {
            width: 100%;
            padding: 14px;
            background-color: var(--accent-color);
            color: #111827;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .login-button:hover {
            background-color: #fde047; /* Lighter yellow */
        }

        .login-button:active {
            transform: scale(0.98);
        }

    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <i class="ph-bold ph-shield-check icon"></i>
            <h1>Admin Panel</h1>
            <p>Sign in to access the dashboard</p>
        </div>

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf

            <div class="input-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <i class="ph ph-envelope-simple"></i>
                    <input type="email" id="email" name="email" class="input-field" placeholder="you@example.com" required>
                </div>
                 @error('email')
                    <span style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="ph ph-key"></i>
                    <input type="password" id="password" name="password" class="input-field" placeholder="••••••••" required>
                </div>
            </div>

            <div class="extra-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>
                <div class="forgot-password">
                    <a href="{{ route('admin.password.request') }}">Forgot Password?</a>

                </div>
            </div>

            <button type="submit" class="login-button">Sign In</button>
        </form>
    </div>

</body>
</html>
