<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CodeShack</title>
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
</head>
<body class="flex items-center justify-center min-h-screen">

    <!-- Reset Password Container -->
    <div class="w-full max-w-sm mx-auto">
        <div class="bg-[#10111A] text-white shadow-2xl rounded-3xl p-8 space-y-6">
            <div class="text-center">
                <i class="ph-bold ph-key text-5xl text-yellow-400"></i>
                <h1 class="text-2xl font-bold text-white mt-4">Set a New Password</h1>
                <p class="text-gray-400">Please enter your new password below.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="text-sm font-medium text-gray-400">Email</label>
                    <div class="relative mt-1">
                        <i class="ph ph-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg py-2 pl-10 pr-3" required autofocus>
                    </div>
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="text-sm font-medium text-gray-400">New Password</label>
                    <div class="relative mt-1">
                         <i class="ph ph-lock-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="password" id="password" name="password" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg py-2 pl-10 pr-3" required>
                    </div>
                    @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="text-sm font-medium text-gray-400">Confirm Password</label>
                    <div class="relative mt-1">
                         <i class="ph ph-lock-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg py-2 pl-10 pr-3" required>
                    </div>
                </div>

                <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg hover:bg-yellow-500 transition-colors">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
