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
                <th>Method</th>
                <th>Account Details</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $request)
                <tr>
                    <td data-label="User">
                        <strong>{{ $request->user->username ?? 'N/A' }}</strong><br>
                        <span style="font-size: 0.8rem; color: #94a3b8;">Bal: ${{ number_format($request->user->balance ?? 0, 2) }}</span>
                    </td>
                    <td data-label="Amount" style="font-weight: bold; color: var(--green);">${{ number_format($request->amount, 2) }}</td>
                    <td data-label="Method">{{ $request->method ?? 'N/A' }}</td>
                    <td data-label="Account Details" style="font-size: 0.9rem;">
                        <strong>Name:</strong> {{ $request->account_title ?? 'N/A' }}<br>
                        <strong>Number:</strong> {{ $request->account_number ?? $request->wallet_address ?? 'N/A' }}
                        @if($request->bank_name)
                            <br><strong>Bank:</strong> {{ $request->bank_name }}
                        @endif
                    </td>
                    <td data-label="Request Date">{{ $request->created_at->format('d M, Y H:i') }}</td>
                    <td data-label="Status">
                        <span class="status-badge status-{{ $request->status }}">{{ $request->status }}</span>
                        @if($request->status == 'rejected' && $request->reject_reason)
                            <div style="font-size: 0.75rem; color: #ef4444; margin-top: 4px;">{{ $request->reject_reason }}</div>
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
                                <form action="{{ route('admin.withdrawals.update', $request) }}" method="POST" onsubmit="return handleReject(this);">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <input type="hidden" name="reject_reason" class="reject-reason-input" value="">
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
function handleReject(form) {
    let reason = prompt("Please enter a reason for rejecting this withdrawal:");
    if (reason === null) {
        return false; // User cancelled
    }
    form.querySelector('.reject-reason-input').value = reason;
    return true;
}
</script>
@endpush
