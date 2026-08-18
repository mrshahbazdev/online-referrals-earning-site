@extends('layouts.frontend')

@section('title', 'Make a Withdrawal')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <i class="ph-bold ph-arrow-circle-up-right text-5xl text-yellow-400"></i>
            <h2 class="text-2xl font-bold text-white mt-4">Request a Withdrawal</h2>
            <p class="text-gray-400">Your current balance: <span class="font-bold text-white">${{ number_format($user->balance, 2) }}</span></p>
        </div>

        @if (session('success'))
            <div class="bg-green-500/10 text-green-300 p-4 rounded-lg text-center">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-500/10 text-red-300 p-4 rounded-lg text-center">{{ session('error') }}</div>
        @endif

        @if($isEligible)
            @if(!$canWithdraw)
                <div class="bg-blue-500/10 text-blue-300 p-6 rounded-lg text-center space-y-2">
                    <h3 class="font-bold text-lg">Withdrawal Limit Reached</h3>
                    <p class="text-sm">You have a recent withdrawal. You can make another request in:</p>
                    <div id="countdown-timer" class="text-3xl font-bold text-white py-2">
                        <span id="days">0</span>d <span id="hours">0</span>h <span id="minutes">0</span>m <span id="seconds">0</span>s
                    </div>
                </div>
            @elseif(!$referralRequirementMet)
                <div class="bg-yellow-500/10 text-yellow-300 p-6 rounded-lg text-center space-y-2">
                    <h3 class="font-bold text-lg">Referral Requirement</h3>
                    <p class="text-sm">To make your next withdrawal, you must refer at least <strong>1 new user</strong> who completes their KYC verification.</p>
                    <p class="text-xs text-gray-400 mt-2">The user must register using your referral link and have their KYC approved after your last withdrawal.</p>
                    <div class="mt-4">
                        <a href="{{ route('team.index') }}" class="bg-yellow-400 text-black font-bold py-2 px-4 rounded-lg inline-block text-sm">Go to Referral Page</a>
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('withdraw.store') }}" class="space-y-4 bg-[#1E1F2B] p-6 rounded-lg">
                    @csrf
                    <div>
                        <label for="amount" class="text-sm font-medium text-gray-400">Amount (USD) - Max: ${{ number_format($maxWithdrawal, 2) }}</label>
                        <input type="number" step="0.01" id="amount" name="amount" class="mt-1 w-full bg-[#334155] rounded-lg p-3" required>
                        @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <div class="mb-4">
                            <label for="method" class="text-sm font-medium text-gray-400">Withdrawal Method</label>
                            <select id="method" name="method" class="mt-1 w-full bg-[#334155] rounded-lg p-3 text-white" required>
                                <option value="" disabled selected>Select a method</option>
                                <option value="USDT TRC20">USDT TRC20</option>
                                <option value="JazzCash">JazzCash</option>
                                <option value="Easypaisa">Easypaisa</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                            @error('method') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4" id="bank_name_container">
                            <label for="bank_name" class="text-sm font-medium text-gray-400">Bank Name <span class="text-xs">(Only for Bank Transfer)</span></label>
                            <input type="text" id="bank_name" name="bank_name" class="mt-1 w-full bg-[#334155] rounded-lg p-3 text-white">
                            @error('bank_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="account_title" class="text-sm font-medium text-gray-400">Account Title (Name)</label>
                            <input type="text" id="account_title" name="account_title" class="mt-1 w-full bg-[#334155] rounded-lg p-3 text-white" required>
                            @error('account_title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="account_number" class="text-sm font-medium text-gray-400">Account Number / Wallet Address</label>
                            <input type="text" id="account_number" name="account_number" class="mt-1 w-full bg-[#334155] rounded-lg p-3 text-white" required>
                            @error('account_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg">Submit Request</button>
                </form>
            @endif
        @else
            <div class="bg-red-500/10 text-red-300 p-4 rounded-lg text-center space-y-4">
                <p class="font-bold">Withdrawals are disabled.</p>
                <p class="text-sm">To enable withdrawals, please complete your profile information and get your KYC verified.</p>
                <div class="flex gap-4 justify-center mt-2">
                    <a href="{{ route('profile.edit') }}" class="bg-yellow-400 text-black font-bold py-2 px-4 rounded-lg text-sm">Complete Profile</a>
                    <a href="{{ route('kyc.create') }}" class="bg-yellow-400 text-black font-bold py-2 px-4 rounded-lg text-sm">Verify KYC</a>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const methodSelect = document.getElementById('method');
    const bankNameContainer = document.getElementById('bank_name_container');

    function toggleBankName() {
        if (methodSelect.value === 'Bank Transfer') {
            bankNameContainer.style.display = 'block';
        } else {
            bankNameContainer.style.display = 'none';
        }
    }

    methodSelect.addEventListener('change', toggleBankName);
    toggleBankName(); // Initial check
});
</script>
@if(isset($nextAvailableDate) && $nextAvailableDate)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownDate = new Date("{{ $nextAvailableDate->toIso8601String() }}").getTime();

    const timerInterval = setInterval(function() {
        const now = new Date().getTime();
        const distance = countdownDate - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("days").innerText = days;
        document.getElementById("hours").innerText = hours;
        document.getElementById("minutes").innerText = minutes;
        document.getElementById("seconds").innerText = seconds;

        if (distance < 0) {
            clearInterval(timerInterval);
            window.location.reload();
        }
    }, 1000);
});
</script>
@endif
@endpush
