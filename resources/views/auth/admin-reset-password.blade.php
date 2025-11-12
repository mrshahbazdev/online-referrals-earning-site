<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <!-- Is page mein bhi aap admin-login.blade.php wala CSS istemal kar sakte hain -->
    <style>
        body { font-family: sans-serif; background: #111827; color: white; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .container { background: #1E293B; padding: 2rem; border-radius: 1rem; width: 400px; }
        h1 { text-align: center; margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; }
        input { width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #475569; background: #334155; color: white; margin-bottom: 1rem; }
        button { width: 100%; padding: 0.75rem; border: none; border-radius: 0.5rem; background: #facc15; color: #111827; font-weight: bold; cursor: pointer; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Your Password</h1>
        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required>
            </div>
            <div>
                <label for="password">New Password</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div>
                <label for="password_confirmation">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
