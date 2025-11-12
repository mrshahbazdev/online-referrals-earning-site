@extends('layouts.frontend')

@section('title', 'Upgrade Level')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <i class="ph-bold ph-rocket-launch text-5xl text-yellow-400"></i>
            <h2 class="text-2xl font-bold text-white mt-4">Upgrade Your Level</h2>
            <p class="text-gray-400">Unlock more benefits and increase your daily earnings.</p>
        </div>

        @if (session('success'))
            <div class="bg-green-500/10 text-green-300 p-4 rounded-lg text-center">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-500/10 text-red-300 p-4 rounded-lg text-center">
                {{ session('error') }}
                <a href="{{ route('deposit.create') }}" class="font-bold underline">Click here to deposit.</a>
            </div>
        @endif

        <div class="space-y-4">
            @foreach ($levels as $level)
                <div class="bg-[#1E1F2B] p-4 rounded-lg border-2 {{ $user->level_id == $level->id ? 'border-yellow-400' : 'border-transparent' }}">
                    <div class="flex items-center gap-4">
                        @if($level->icon_url)
                            <img src="{{ asset('storage/' . $level->icon_url) }}" alt="{{ $level->name }}" class="w-16 h-16 rounded-full object-cover">
                        @endif
                        <div class="flex-grow">
                            <h3 class="font-bold text-lg text-white">{{ $level->name }}</h3>
                            <p class="text-sm text-gray-400">Daily Tasks: <span class="font-semibold">{{ $level->daily_task_limit }}</span></p>
                            <p class="text-sm text-gray-400">Upgrade Cost: <span class="font-semibold">${{ number_format($level->upgrade_cost, 2) }}</span></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        @if($user->level_id > $level->id)
                            <button class="w-full bg-gray-600 text-gray-400 font-bold py-2 rounded-lg cursor-not-allowed">Unlocked</button>
                        @elseif($user->level_id == $level->id)
                            <button class="w-full bg-green-600 text-white font-bold py-2 rounded-lg cursor-not-allowed">Current Plan</button>
                        @else
                            <form action="{{ route('levels.upgrade', $level) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-2 rounded-lg hover:bg-yellow-500 transition-colors">Upgrade</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
