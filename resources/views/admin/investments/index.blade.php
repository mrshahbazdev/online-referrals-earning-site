@extends('admin.layouts.app')

@section('title', 'Investment Management')

@push('styles')
    <style>
        :root {
            --bg-dark: #111827; --sidebar-bg: #1E293B; --card-bg: #1E293B;
            --text-primary: #f1f5f9; --text-secondary: #94a3b8; --accent-color: #facc15;
            --border-color: #334155; --green: #22c55e; --red: #ef4444; --blue: #3b82f6;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-dark); color: var(--text-primary); }
        /* Add all other necessary styles from your previous admin pages */
        .dashboard-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background-color: var(--sidebar-bg); padding: 1.5rem; }
        .main-content { flex-grow: 1; padding: 2rem; }
        .main-header h1 { font-size: 1.75rem; margin-bottom: 2rem; }
        .table-container { background-color: var(--card-bg); border-radius: 12px; overflow-x: auto; }
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
            <header class="main-header"><h1>Investment Requests</h1></header>

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
                            <th>Transaction Proof</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $request)
                            <tr>
                                <td data-label="User">{{ $request->user->username ?? 'N/A' }}</td>
                                <td data-label="Amount">${{ number_format($request->amount, 2) }}</td>
                                <td data-label="Transaction Proof">
                                    <a href="{{ asset('storage/' . $request->transaction_id_image_url) }}" target="_blank" style="color: var(--accent-color);">View Proof</a>
                                </td>
                                <td data-label="Date">{{ $request->created_at->format('d M, Y') }}</td>
                                <td data-label="Status">
                                    <span class="status-badge status-{{ $request->status }}">{{ $request->status }}</span>
                                </td>
                                <td data-label="Actions">
                                    @if($request->status == 'pending')
                                        <div class="action-form">
                                            <form action="{{ route('admin.investments.update', $request) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" style="background-color: var(--green); color: white;">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.investments.update', $request) }}" method="POST">
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
                            <tr><td colspan="6" style="text-align: center;">No investment requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-links">{{ $requests->links() }}</div>
        </main>
    </div>
    @endsection
