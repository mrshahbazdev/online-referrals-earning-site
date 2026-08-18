@extends('layouts.frontend')

@section('title', 'Mine')

@section('content')
    <div class="space-y-4">
        <!-- Profile Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ $user->profile_image_url ? asset('public/uploads/' . $user->profile_image_url) : 'https://placehold.co/64x64/fbbF24/10111A?text=' . strtoupper(substr($user->username, 0, 1)) }}" alt="Profile Picture" class="w-16 h-16 rounded-full border-2 border-yellow-400 object-cover">
                <span class="font-semibold text-lg">{{ $user->username }}</span>
            </div>

            <!-- Dynamic Level Icon (Clickable) -->
            <a href="{{ route('levels.index') }}" class="text-center">
                @if($user->level && $user->level->icon_url)
                    <img src="{{ asset('public/uploads/' . $user->level->icon_url) }}" alt="{{ $user->level->name }}" class="w-12 h-12 rounded-full mx-auto">
                @else
                    <div class="bg-gradient-to-b from-pink-500 to-rose-600 p-2 rounded-lg inline-block">
                        <i class="ph-fill ph-crown-simple text-2xl text-white"></i>
                    </div>
                @endif
                <span class="text-xs font-bold text-pink-400 mt-1 block">{{ $user->level->name ?? 'VIP' }}</span>
            </a>
        </div>

        <!-- Wallets -->
        <div class="mt-6 grid grid-cols-2 gap-4">
            <div class="bg-[#1E1F2B] p-4 rounded-lg">
                <p class="text-sm text-gray-400">Main Wallet</p>
                <p class="text-2xl font-bold mt-1">${{ number_format($mainWallet, 2) }}</p>
            </div>
             <div class="bg-[#1E1F2B] p-4 rounded-lg">
                <p class="text-sm text-gray-400">Commission Wallet</p>
                <p class="text-2xl font-bold mt-1">${{ number_format($commissionWallet, 2) }}</p>
            </div>
        </div>

        <!-- Wallet Actions -->
        <div class="mt-4 grid grid-cols-4 gap-4 text-center">
            <a href="{{ route('deposit.create') }}" class="flex flex-col items-center"><div class="bg-[#1E1F2B] p-3 rounded-full"><i class="ph ph-arrow-down text-xl text-green-400"></i></div><span class="text-xs mt-2">Deposit</span></a>
            <a href="{{ route('withdraw.create') }}" class="flex flex-col items-center"><div class="bg-[#1E1F2B] p-3 rounded-full"><i class="ph ph-arrow-up text-xl text-red-400"></i></div><span class="text-xs mt-2">Withdraw</span></a>
            <a href="{{ route('transactions.index') }}" class="flex flex-col items-center"><div class="bg-[#1E1F2B] p-3 rounded-full"><i class="ph ph-scroll text-xl text-blue-400"></i></div><span class="text-xs mt-2">Records</span></a>
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center"><div class="bg-[#1E1F2B] p-3 rounded-full"><i class="ph ph-bank text-xl text-purple-400"></i></div><span class="text-xs mt-2">Account</span></a>
        </div>

        <!-- Summary -->
        <div class="px-4 py-2 mt-4 bg-[#1E1F2B] rounded-lg space-y-3">
            <div class="flex justify-between text-sm"><span class="text-gray-400">Total Deposit</span><span class="font-semibold text-green-400">${{ number_format($totalDeposit, 2) }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-400">Total Withdraw</span><span class="font-semibold text-red-400">${{ number_format(abs($totalWithdraw), 2) }}</span></div>
             <div class="flex justify-between text-sm"><span class="text-gray-400">Today Profit</span><span class="font-semibold text-green-400">${{ number_format($todayProfit, 2) }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-400">Total Profit</span><span class="font-semibold text-green-400">${{ number_format($totalProfit, 2) }}</span></div>
        </div>

        <!-- Watch & Earn -->
        <a href="{{ route('tasks.index') }}" >
            <div class="px-4 py-3 mt-4 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg flex items-center justify-between shadow-lg border border-blue-500/30">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-full">
                        <i class="ph-fill ph-play-circle text-3xl text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold text-white text-lg">Watch & Earn</p>
                        <p class="text-xs text-blue-100">Complete tasks to earn rewards</p>
                    </div>
                </div>
                <div class="bg-white/10 rounded-full p-1">
                    <i class="ph ph-caret-right text-white text-xl"></i>
                </div>
            </div>
        </a>

        <!-- Referral -->
        <a href="{{ route('team.index') }}" >
            <div class="px-4 py-3 mt-4 bg-[#1E1F2B] rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="ph ph-gift text-3xl text-yellow-400"></i>
                    <div>
                        <p class="font-semibold">Referral Earn</p>
                        <p class="text-xs text-gray-400">Invite a friend to earn ${{ number_format($joiningBonus, 2) }}</p>
                    </div>
                </div>
                <span class="font-bold text-lg text-yellow-400">${{ number_format($commissionWallet, 2) }}</span>
            </div>
        </a>

        <!-- Menu List -->
        <div class="mt-4 bg-[#1E1F2B] rounded-lg divide-y divide-gray-700">
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4"><div class="flex items-center gap-4"><i class="ph ph-user-circle text-2xl text-sky-400"></i><span>Account Info</span></div><i class="ph ph-caret-right text-gray-500"></i></a>
            <a href="{{ route('kyc.create') }}" class="flex items-center justify-between p-4"><div class="flex items-center gap-4"><i class="ph ph-identification-card text-2xl text-teal-400"></i><span>KYC Status</span></div><i class="ph ph-caret-right text-gray-500"></i></a>
            <a href="{{ route('support.index') }}" class="flex items-center justify-between p-4"><div class="flex items-center gap-4"><i class="ph ph-chat-circle-dots text-2xl text-yellow-400"></i><span>Support</span></div><i class="ph ph-caret-right text-gray-500"></i></a>
            <a href="{{ route('team.index') }}" class="flex items-center justify-between p-4"><div class="flex items-center gap-4"><i class="ph ph-users-three text-2xl text-indigo-400"></i><span>My Team</span></div><i class="ph ph-caret-right text-gray-500"></i></a>
            <a href="{{ route('terms') }}" class="flex items-center justify-between p-4"><div class="flex items-center gap-4"><i class="ph ph-file-text text-2xl text-gray-400"></i><span>Terms & Conditions</span></div><i class="ph ph-caret-right text-gray-500"></i></a>
            <a href="{{ route('about') }}" class="flex items-center justify-between p-4"><div class="flex items-center gap-4"><i class="ph ph-info text-2xl text-orange-400"></i><span>About Us</span></div><i class="ph ph-caret-right text-gray-500"></i></a>
            @if(!empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <i class="ph ph-whatsapp-logo text-2xl text-green-400"></i>
                        <span>WhatsApp</span>
                    </div>
                    <i class="ph ph-caret-right text-gray-500"></i>
                </a>
            @endif
            @if(!empty($settings['support_telegram']))
                @php
                    $telegram = $settings['support_telegram'];
                    $telegramLink = str_starts_with($telegram, 'http') ? $telegram : 'https://t.me/' . ltrim($telegram, '@');
                @endphp
                <a href="{{ $telegramLink }}" target="_blank" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <i class="ph ph-telegram-logo text-2xl text-blue-400"></i>
                        <span>Telegram</span>
                    </div>
                    <i class="ph ph-caret-right text-gray-500"></i>
                </a>
            @endif
            @if(!empty($settings['support_imo']))
                @php
                    $imo = $settings['support_imo'];
                    $imoLink = str_starts_with($imo, 'http') ? $imo : 'https://imo.im/' . ltrim($imo, '+');
                @endphp
                <a href="{{ $imoLink }}" target="_blank" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <i class="ph ph-chat-circle text-2xl text-indigo-400"></i>
                        <span>IMO</span>
                    </div>
                    <i class="ph ph-caret-right text-gray-500"></i>
                </a>
            @endif
        </div>
    </div>
@endsection


