<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings['site_name'] ?? 'CodeShack')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Phosphor Icons CDN -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #111827;
        }
    </style>
    {!! $settings['header_scripts'] ?? '' !!}
</head>
<body class="flex items-center justify-center min-h-screen">

    <!-- Login Container -->
    <div id="login-container" class="w-full max-w-sm mx-auto">
        <div class="bg-[#10111A] text-white shadow-2xl rounded-3xl p-8 space-y-6">
            <div class="text-center">
                <i class="ph-bold ph-fingerprint text-5xl text-yellow-400"></i>
                <h1 class="text-2xl font-bold text-white mt-4">Welcome Back!</h1>
                <p class="text-gray-400">Login to your account to continue.</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="text-sm font-medium text-gray-400">Email</label>
                    <div class="relative mt-1">
                        <i class="ph ph-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg py-2 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-yellow-400" required autofocus>
                    </div>
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="password" class="text-sm font-medium text-gray-400">Password</label>
                    <div class="relative mt-1">
                         <i class="ph ph-lock-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="password" id="password" name="password" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg py-2 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
                    </div>
                    @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="text-right">
                    <a href="{{ route('password.request') }}" class="text-sm text-yellow-400 hover:underline">Forgot Password?</a>
                </div>
                <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg hover:bg-yellow-500 transition-colors">Login</button>
            </form>

            <div class="relative flex items-center">
                <div class="flex-grow border-t border-gray-700"></div>
                <span class="flex-shrink mx-4 text-gray-500">Or</span>
                <div class="flex-grow border-t border-gray-700"></div>
            </div>

            <div class="flex justify-center gap-4">
                <button class="w-12 h-12 bg-[#1E1F2B] rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors"><i class="ph-bold ph-google-logo text-2xl"></i></button>
                <button class="w-12 h-12 bg-[#1E1F2B] rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors"><i class="ph-bold ph-apple-logo text-2xl"></i></button>
            </div>

            <p class="text-center text-sm text-gray-400">Don't have an account? <a href="{{ route('register') }}" class="font-medium text-yellow-400 hover:underline">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
