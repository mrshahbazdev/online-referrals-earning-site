<nav class="grid grid-cols-5 gap-2 p-3 bg-[#1E1F2B] border-t border-gray-700">
    <a href="{{ route('home') }}" class="flex flex-col items-center text-center {{ request()->routeIs('home') ? 'text-yellow-400' : 'text-gray-400' }} hover:text-yellow-400">
        <i class="ph ph-house text-2xl"></i><span class="text-xs">Home</span>
    </a>
    <a href="{{ route('deposit.create') }}" class="flex flex-col items-center text-center {{ request()->routeIs('deposit.create') ? 'text-yellow-400' : 'text-gray-400' }} hover:text-yellow-400">
        <i class="ph ph-wallet text-2xl"></i><span class="text-xs">Deposit</span>
    </a>
    <a href="{{ route('tasks.index') }}" class="flex flex-col items-center text-center {{ request()->routeIs('tasks.index') ? 'text-yellow-400' : 'text-gray-400' }} hover:text-yellow-400">
        <i class="ph ph-check-square-offset text-2xl"></i><span class="text-xs">Tasks</span>
    </a>
    <a href="{{ route('withdraw.create') }}" class="flex flex-col items-center text-center {{ request()->routeIs('withdraw.create') ? 'text-yellow-400' : 'text-gray-400' }} hover:text-yellow-400">
        <i class="ph ph-arrow-circle-up-right text-2xl"></i><span class="text-xs">Withdraw</span>
    </a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex flex-col items-center text-center text-gray-400 hover:text-yellow-400 w-full">
            <i class="ph ph-sign-out text-2xl"></i><span class="text-xs">Logout</span>
        </button>
    </form>
</nav>
