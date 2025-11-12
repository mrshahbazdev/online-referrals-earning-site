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

    <!-- Sign Up Container -->
    <div id="signup-container" class="w-full max-w-sm mx-auto">
         <div class="bg-[#10111A] text-white shadow-2xl rounded-3xl p-8 space-y-6">
            <div class="text-center">
                <i class="ph-bold ph-user-plus text-5xl text-yellow-400"></i>
                <h1 class="text-2xl font-bold text-white mt-4">Create Account</h1>
                <p class="text-gray-400">Join us and start your crypto journey.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <!-- Username -->
                <div>
                    <label for="username" class="text-sm font-medium text-gray-400">Username</label>
                    <div class="relative mt-1">
                        <i class="ph ph-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="text" id="username" name="username" placeholder="Choose a username" value="{{ old('username') }}" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg py-2 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-yellow-400" required autofocus>
                    </div>
                    @error('username') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <!-- Email -->
                <div>
                    <label for="email" class="text-sm font-medium text-gray-400">Email</label>
                    <div class="relative mt-1">
                        <i class="ph ph-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="email" id="email" name="email" placeholder="email@example.com" value="{{ old('email') }}" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg py-2 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
                    </div>
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <!-- Password -->
                <div>
                    <label for="password" class="text-sm font-medium text-gray-400">Password</label>
                    <div class="relative mt-1">
                         <i class="ph ph-lock-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg py-2 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
                    </div>
                    @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <!-- Confirm Password -->
                 <div>
                    <label for="password_confirmation" class="text-sm font-medium text-gray-400">Confirm Password</label>
                    <div class="relative mt-1">
                         <i class="ph ph-lock-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg py-2 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
                    </div>
                </div>

                <!-- Referral Code (Hidden) -->
                <input type="hidden" id="referral_code" name="referral_code">

                <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg hover:bg-yellow-500 transition-colors flex items-center justify-center">
                    Create Account
                </button>
            </form>
            <p class="text-center text-sm text-gray-400">Already have an account? <a href="{{ route('login') }}" class="font-medium text-yellow-400 hover:underline">Log In</a></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the referral code from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const refCode = urlParams.get('ref');

            // If a referral code exists in the URL, set it to the hidden input field
            if (refCode) {
                document.getElementById('referral_code').value = refCode;
            }
        });
    </script>

</body>
</html>
