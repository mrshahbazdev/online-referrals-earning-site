@extends('layouts.frontend')

@section('title', 'Transaction Records')

@push('styles')
<style>
    .tab.active {
        background-color: #facc15; /* yellow-400 */
        color: #10111A;
    }
</style>
@endpush

@section('content')
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-center">Records</h2>

        <!-- Filter Tabs -->
        <div id="filter-tabs" class="flex flex-wrap gap-2 bg-[#1E1F2B] p-2 rounded-lg">
            <a href="{{ route('transactions.index') }}" class="tab {{ !$selectedType ? 'active' : '' }} flex-grow text-center w-auto py-2 text-sm font-bold rounded-full transition-colors {{ !$selectedType ? '' : 'text-gray-400' }}">All</a>
            @foreach($types as $type)
                <a href="{{ route('transactions.index', ['type' => $type]) }}" class="tab {{ $selectedType == $type ? 'active' : '' }} flex-grow text-center w-auto py-2 text-sm font-bold rounded-full transition-colors {{ $selectedType == $type ? '' : 'text-gray-400' }} capitalize">{{ str_replace('_', ' ', $type) }}</a>
            @endforeach
        </div>

        <!-- Transaction List -->
        <div id="transaction-list" class="space-y-3">
            @forelse ($transactions as $transaction)
                <div class="transaction-item bg-[#1E1F2B] p-3 rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-full
                            @if($transaction->amount > 0 && $transaction->type == 'commission') bg-purple-500/10
                            @elseif($transaction->amount > 0) bg-green-500/10
                            @else bg-red-500/10 @endif">

                            @if($transaction->type == 'commission')
                                <i class="ph ph-users-three text-xl text-purple-400"></i>
                            @elseif($transaction->amount > 0)
                                <i class="ph ph-arrow-down text-xl text-green-400"></i>
                            @else
                                <i class="ph ph-arrow-up text-xl text-red-400"></i>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold capitalize">{{ str_replace('_', ' ', $transaction->type) }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M, Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold {{ $transaction->amount > 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $transaction->amount > 0 ? '+' : '' }} ${{ number_format($transaction->amount, 2) }}
                        </p>
                        <p class="text-xs text-gray-500">Completed</p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-10">
                    <i class="ph ph-files text-4xl"></i>
                    <p class="mt-2">No records found for this filter.</p>
                </div>
            @endforelse
        </div>

        <div class="pagination-links">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
