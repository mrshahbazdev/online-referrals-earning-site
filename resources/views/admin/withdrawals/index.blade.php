@extends('admin.layouts.app')

@section('title', 'Withdrawal Requests')

@push('styles')
    {{-- Is page ke makhsoos styles yahan add karein --}}
    <style>
        .table-container { background-color: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { background-color: #334155; }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
        .status-pending { background-color: #a16207; color: #fefce8; }
        .status-approved { background-color: #166534; color: #dcfce7; }
        .status-rejected { background-color: #991b1b; color: #fee2e2; }
        .action-form { display: inline-flex; gap: 0.5rem; }
        .action-form button { border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 600; }
    </style>
@endpush

@section('content')
    <header class="main-header">
        <button class="mobile-nav-toggle" id="mobileNavToggle"><i class="ph ph-list"></i></button>
        <h1>Withdrawal Requests</h1>
    </header>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Amount</th>
                <th>Wallet Address</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Next Available In</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $request)
                <tr>
                    <td data-label="User">{{ $request->user->username ?? 'N/A' }}</td>
                    <td data-label="Amount">${{ number_format($request->amount, 2) }}</td>
                    <td data-label="Wallet Address">{{ $request->wallet_address }}</td>
                    <td data-label="Request Date">{{ $request->created_at->format('d M, Y') }}</td>
                    <td data-label="Status">
                        <span class="status-badge status-{{ $request->status }}">{{ $request->status }}</span>
                    </td>
                    <td data-label="Next Available In">
                        @php
                            // **FIX:** Hafte ke aakhir ke bajaye, request ki date se 7 din aage ki date calculate karein
                            $nextAvailableDate = \Carbon\Carbon::parse($request->created_at)->addDays(7);
                        @endphp
                        @if($nextAvailableDate->isPast())
                            <span class="text-green-400">Available Now</span>
                        @else
                            <div class="countdown-timer text-sm" data-end-date="{{ $nextAvailableDate->toIso8601String() }}"></div>
                        @endif
                    </td>
                    <td data-label="Actions">
                        @if($request->status == 'pending')
                            <div class="action-form">
                                <form action="{{ route('admin.withdrawals.update', $request) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" style="background-color: var(--green); color: white;">Approve</button>
                                </form>
                                <form action="{{ route('admin.withdrawals.update', $request) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" style="background-color: var(--red); color: white;">Reject</button>
                                </form>
                            </div>
                        @else
                            <span>Processed</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align: center;">No withdrawal requests found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

    <div class="pagination-links">{{ $requests->links() }}</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.countdown-timer').forEach(function(timerElement) {
        const countdownDate = new Date(timerElement.dataset.endDate).getTime();

        const timerInterval = setInterval(function() {
            const now = new Date().getTime();
            const distance = countdownDate - now;

            if (distance < 0) {
                clearInterval(timerInterval);
                timerElement.innerHTML = "<span class='text-green-400'>Available Now</span>";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

            timerElement.innerHTML = `${days}d ${hours}h ${minutes}m`;
        }, 1000);
    });
});
</script>
@endpush
