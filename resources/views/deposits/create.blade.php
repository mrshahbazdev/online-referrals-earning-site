@extends('layouts.frontend')

@section('title', 'Make a Deposit')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-white">Deposit USDT</h2>
        </div>

        @if (session('success'))
            <div class="bg-green-500/10 text-green-300 p-4 rounded-lg text-center">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-500/10 text-red-300 p-4 rounded-lg text-center">{{ session('error') }}</div>
        @endif

        {{-- Check karein ke koi request pending to nahi hai --}}
        @if($pendingRequest)
            <div class="bg-yellow-500/10 text-yellow-300 p-6 rounded-lg text-center space-y-2">
                <i class="ph-bold ph-clock text-5xl"></i>
                <h3 class="font-bold text-lg">Request Pending</h3>
                <p class="text-sm">Your deposit request of ${{ number_format($pendingRequest->amount, 2) }} is currently under review.</p>
            </div>
        @else
            @if($depositMethods->isNotEmpty())
                <!-- Method Selection Tabs -->
                <div id="method-tabs" class="flex flex-wrap gap-2 bg-[#1E1F2B] p-2 rounded-lg">
                    @foreach($depositMethods as $method)
                        <button class="method-tab flex-grow text-center py-2 text-sm font-bold rounded-full" data-target="method-{{ $method->id }}">
                            {{ $method->name }}
                        </button>
                    @endforeach
                </div>

                <!-- Method Details -->
                @foreach($depositMethods as $method)
                    <div id="method-{{ $method->id }}" class="method-details hidden bg-[#1E1F2B] p-6 rounded-lg text-center space-y-4">
                        <img src="{{ asset('storage/' . $method->qr_code_url) }}" alt="QR Code" class="w-48 h-48 mx-auto rounded-lg bg-white p-1">
                        <div>
                            <p class="text-sm text-gray-400">Network</p>
                            <p class="font-semibold">{{ $method->network }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Deposit Address</p>
                            <p class="font-semibold break-all">{{ $method->address }}</p>
                        </div>
                    </div>
                @endforeach

                <form method="POST" action="{{ route('deposit.store') }}" enctype="multipart/form-data" class="space-y-4 bg-[#1E1F2B] p-6 rounded-lg">
                    @csrf
                    <div>
                        <label for="amount" class="text-sm font-medium text-gray-400">Amount (USD)</label>
                        <div class="relative mt-1">
                            <i class="ph ph-currency-dollar absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                            <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount') }}" class="w-full bg-[#334155] border border-gray-700 rounded-lg py-2 pl-10 pr-3" required>
                        </div>
                        @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="proof" class="text-sm font-medium text-gray-400">Transaction Proof (Screenshot)</label>
                        <div class="relative mt-1">
                            <input type="file" id="proof" name="proof" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-400 file:text-black hover:file:bg-yellow-500" required>
                        </div>
                        @error('proof') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg hover:bg-yellow-500">Submit Request</button>
                </form>
            @else
                <div class="bg-red-500/10 text-red-300 p-4 rounded-lg text-center">
                    Deposit system is currently unavailable.
                </div>
            @endif
        @endif
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.method-tab');
    const details = document.querySelectorAll('.method-details');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active', 'bg-yellow-400', 'text-black'));
            tab.classList.add('active', 'bg-yellow-400', 'text-black');

            const targetId = tab.dataset.target;
            details.forEach(d => {
                if (d.id === targetId) {
                    d.classList.remove('hidden');
                } else {
                    d.classList.add('hidden');
                }
            });
        });
    });

    // Pehla tab by default select karein
    if (tabs.length > 0) {
        tabs[0].click();
    }
});
</script>
@endpush
