<!-- Menu Header -->
<div class="flex justify-between items-center mb-10">
    <div class="flex items-center gap-3">
        <div class="bg-yellow-400 p-2 rounded-full">
            <i class="ph-fill ph-user text-black text-xl"></i>
        </div>
        <span class="font-bold text-lg text-white">{{ $settings['site_name'] ?? config('app.name') }}</span>
    </div>
    <i id="close-menu-button" class="ph ph-x text-2xl cursor-pointer p-1 rounded-full hover:bg-gray-800"></i>
</div>


<!-- Menu Links -->
<div class="flex flex-col h-full">
    <p class="text-sm text-gray-500 uppercase tracking-wider mb-4">Menu</p>
    <nav class="flex flex-col space-y-2">
        <a href="{{ route('home') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-[#1E1F2B] {{ request()->routeIs('home') ? 'bg-[#1E1F2B]' : '' }}">
            <div class="flex items-center gap-4"><i class="ph ph-house text-xl text-yellow-400"></i><span class="text-white">Home</span></div>
            <i class="ph ph-caret-right text-gray-500"></i>
        </a>
        <a href="{{ route('team.index') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-[#1E1F2B] {{ request()->routeIs('team.index') ? 'bg-[#1E1F2B]' : '' }}">
            <div class="flex items-center gap-4"><i class="ph ph-users-three text-xl text-yellow-400"></i><span class="text-white">My Team</span></div>
            <i class="ph ph-caret-right text-gray-500"></i>
        </a>
        <a href="{{ route('levels.index') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-[#1E1F2B] {{ request()->routeIs('levels.index') ? 'bg-[#1E1F2B]' : '' }}">
            <div class="flex items-center gap-4"><i class="ph ph-stairs text-xl text-yellow-400"></i><span class="text-white">Upgrade Level</span></div>
            <i class="ph ph-caret-right text-gray-500"></i>
        </a>
    </nav>
    <p class="text-sm text-gray-500 uppercase tracking-wider mt-10 mb-4">Others</p>
    <nav class="flex flex-col space-y-2">
        <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-[#1E1F2B] {{ request()->routeIs('profile.edit') ? 'bg-[#1E1F2B]' : '' }}">
            <div class="flex items-center gap-4"><i class="ph ph-gear text-xl text-yellow-400"></i><span class="text-white">Settings</span></div>
            <i class="ph ph-caret-right text-gray-500"></i>
        </a>
       <a href="{{ route('app.download') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-[#1E1F2B] {{ request()->routeIs('app.download') ? 'bg-[#1E1F2B]' : '' }}">
            <div class="flex items-center gap-4"><i class="ph ph-download-simple text-xl text-yellow-400"></i><span class="text-white">App Download</span></div>
            <i class="ph ph-caret-right text-gray-500"></i>
        </a>
        <a href="{{ route('terms') }}" class="flex items-center justify-between p-4">
            <div class="flex items-center gap-4"><i class="ph ph-file-text text-2xl text-gray-400"></i><span>Terms & Conditions</span></div>
            <i class="ph ph-caret-right text-gray-500"></i>
        </a>
        <a href="{{ route('about') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-[#1E1F2B] {{ request()->routeIs('about') ? 'bg-[#1E1F2B]' : '' }}">
            <div class="flex items-center gap-4"><i class="ph ph-info text-xl text-yellow-400"></i><span class="text-white">About Us</span></div>
            <i class="ph ph-caret-right text-gray-500"></i>
        </a>
    </nav>
    <nav class="flex flex-col space-y-2">
      <div class="mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full">
                <span class="flex items-center justify-between p-3 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 w-full">
                    <div class="flex items-center gap-4">
                        <i class="ph ph-sign-out text-xl"></i>
                        <span class="font-semibold">Logout</span>
                    </div>
                </span>
            </button>
        </form>
    </div>
    </nav>
</div>
</div>
