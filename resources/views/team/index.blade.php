@extends('layouts.frontend')

@section('title', 'My Team')

@section('content')
    <div class="space-y-6">
        <!-- Referral Link -->
        <div>
            <label class="text-sm font-medium text-gray-400">Your Referral Link</label>
            <div class="relative mt-1">
                <input id="referral-link" type="text" readonly value="{{ url('/register?ref=' . $user->referral_code) }}" class="w-full bg-[#1E1F2B] border border-gray-700 rounded-lg p-3 pr-12 text-sm">
                <button id="copy-button" class="absolute right-1 top-1/2 -translate-y-1/2 bg-yellow-400 text-black p-2.5 rounded-md">
                    <i class="ph ph-copy"></i>
                </button>
            </div>
            <span id="copy-feedback" class="text-green-500 text-xs mt-2 hidden">Link Copied!</span>
        </div>

        <!-- Summary Cards -->
        <div class="space-y-2">
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-red-800/50 p-3 rounded-lg text-center">
                    <p class="text-sm">Total Team</p>
                    <p class="font-bold text-lg">{{ $totalTeam }}</p>
                </div>
                <div class="bg-red-800/50 p-3 rounded-lg text-center">
                    <p class="text-sm">Total Earn</p>
                    <p class="font-bold text-lg">${{ number_format($totalEarn, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Level Tabs -->
        <div id="level-tabs" class="grid grid-cols-3 gap-2">
            <button data-level="1" class="tab active w-full py-2 text-sm font-bold rounded-md bg-yellow-400 text-black">Lev1 ({{ $level1->count() }})</button>
            <button data-level="2" class="tab w-full py-2 text-sm font-bold rounded-md text-gray-400 bg-[#1E1F2B]">Lev2 ({{ $level2->count() }})</button>
            <button data-level="3" class="tab w-full py-2 text-sm font-bold rounded-md text-gray-400 bg-[#1E1F2B]">Lev3 ({{ $level3->count() }})</button>
        </div>

        <!-- Team List -->
        <div id="team-list" class="space-y-3">
            <!-- Level 1 Members -->
            <div class="team-members-container" data-level="1">
                @forelse ($level1 as $member)
                    <div class="bg-[#1E1F2B] p-3 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $member->profile_image_url ? asset('storage/' . $member->profile_image_url) : 'https://placehold.co/48x48/1E1F2B/FFFFFF?text=' . strtoupper(substr($member->username, 0, 1)) }}" alt="member" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="font-semibold">Username: {{ $member->username }}</p>
                                <p class="text-xs text-gray-400">Joined: {{ $member->created_at->format('d M, Y') }}</p>
                            </div>
                        </div>
                        <div>
                            @if($member->has_deposited)
                                <span class="text-xs font-bold text-green-400 bg-green-500/10 px-2 py-1 rounded-full">Active</span>
                            @else
                                <span class="text-xs font-bold text-yellow-400 bg-yellow-500/10 px-2 py-1 rounded-full">Pending</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">No members found in this level.</p>
                @endforelse
            </div>


            <!-- Level 2 Members -->
            <div class="team-members-container hidden" data-level="2">
                @forelse ($level2 as $member)
                    <div class="bg-[#1E1F2B] p-3 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $member->profile_image_url ? asset('storage/' . $member->profile_image_url) : 'https://placehold.co/48x48/1E1F2B/FFFFFF?text=' . strtoupper(substr($member->username, 0, 1)) }}" alt="member" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="font-semibold">Username: {{ $member->username }}</p>
                                <p class="text-xs text-gray-400">Joined: {{ $member->created_at->format('d M, Y') }}</p>
                            </div>
                        </div>
                        <div>
                            @if($member->has_deposited)
                                <span class="text-xs font-bold text-green-400 bg-green-500/10 px-2 py-1 rounded-full">Active</span>
                            @else
                                <span class="text-xs font-bold text-yellow-400 bg-yellow-500/10 px-2 py-1 rounded-full">Pending</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">No members found in this level.</p>
                @endforelse
            </div>

            <!-- Level 3 Members -->
            <div class="team-members-container hidden" data-level="3">
                @forelse ($level3 as $member)
                    <div class="bg-[#1E1F2B] p-3 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $member->profile_image_url ? asset('storage/' . $member->profile_image_url) : 'https://placehold.co/48x48/1E1F2B/FFFFFF?text=' . strtoupper(substr($member->username, 0, 1)) }}" alt="member" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="font-semibold">Username: {{ $member->username }}</p>
                                <p class="text-xs text-gray-400">Joined: {{ $member->created_at->format('d M, Y') }}</p>
                            </div>
                        </div>
                        <div>
                            @if($member->has_deposited)
                                <span class="text-xs font-bold text-green-400 bg-green-500/10 px-2 py-1 rounded-full">Active</span>
                            @else
                                <span class="text-xs font-bold text-yellow-400 bg-yellow-500/10 px-2 py-1 rounded-full">Pending</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">No members found in this level.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy referral link
    const copyButton = document.getElementById('copy-button');
    const referralLinkInput = document.getElementById('referral-link');
    const copyFeedback = document.getElementById('copy-feedback');

    copyButton.addEventListener('click', () => {
        referralLinkInput.select();
        document.execCommand('copy');
        copyFeedback.classList.remove('hidden');
        setTimeout(() => {
            copyFeedback.classList.add('hidden');
        }, 2000);
    });

    // Tab switching logic
    const tabsContainer = document.getElementById('level-tabs');
    const memberContainers = document.querySelectorAll('.team-members-container');

    tabsContainer.addEventListener('click', (e) => {
        const clickedTab = e.target.closest('.tab');
        if (!clickedTab) return;

        tabsContainer.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active', 'bg-yellow-400', 'text-black');
            tab.classList.add('text-gray-400', 'bg-[#1E1F2B]');
        });
        clickedTab.classList.add('active', 'bg-yellow-400', 'text-black');
        clickedTab.classList.remove('text-gray-400', 'bg-[#1E1F2B]');

        const level = clickedTab.dataset.level;

        memberContainers.forEach(container => {
            if (container.dataset.level === level) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        });
    });
});
</script>
@endpush
