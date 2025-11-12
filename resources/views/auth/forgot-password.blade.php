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
<body class="flex items-center justify-center min-h-screen p-4">

    <!-- Forgot Password Container -->
    <div class="w-full max-w-sm mx-auto">
        <div class="bg-[#10111A] text-white shadow-2xl rounded-3xl p-8 space-y-6">
            <div class="text-center">
                <i class="ph-bold ph-key text-5xl text-yellow-400"></i>
                <h1 class="text-2xl font-bold text-white mt-4">Forgot Password?</h1>
                <p class="text-gray-400">Enter your email and we'll send you a reset link.</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="text-sm font-medium text-gray-400">Email</label>
                    <div class="relative mt-1">
                        <i class="ph ph-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg py-2 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-yellow-400" required autofocus>
                    </div>
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg hover:bg-yellow-500 transition-colors">Email Password Reset Link</button>
            </form>

            <p class="text-center text-sm text-gray-400">Remember your password? <a href="{{ route('login') }}" class="font-medium text-yellow-400 hover:underline">Log In</a></p>
        </div>
    </div>
</body>
</html>
