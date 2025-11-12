<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <!-- Is page mein aap admin-login.blade.php wala CSS istemal kar sakte hain -->
    <style>
        body { font-family: sans-serif; background: #111827; color: white; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .container { background: #1E293B; padding: 2rem; border-radius: 1rem; width: 400px; }
        h1 { text-align: center; margin-bottom: 1rem; }
        p { color: #94a3b8; margin-bottom: 1.5rem; text-align: center; }
        label { display: block; margin-bottom: 0.5rem; }
        input { width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #475569; background: #334155; color: white; }
        button { width: 100%; padding: 0.75rem; border: none; border-radius: 0.5rem; background: #facc15; color: #111827; font-weight: bold; cursor: pointer; margin-top: 1.5rem; }
        .status { color: #22c55e; background: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Your Password?</h1>
        <p>No problem. Just let us know your email address and we will email you a password reset link.</p>

        @if (session('status'))
            <div class="status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.email') }}">
            @csrf
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit">Email Password Reset Link</button>
        </form>
    </div>
</body>
</html>
