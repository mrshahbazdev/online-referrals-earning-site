@extends('admin.layouts.app')

@section('title', 'User Management')

@push('styles')
    <style>
        /* Aap apne dashboard se styles copy kar sakte hain */
        body { font-family: sans-serif; background-color: #111827; color: #f1f5f9; }
        .dashboard-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background-color: #1E293B; padding: 1.5rem; }
        .main-content { flex-grow: 1; padding: 2rem; }
        .main-header h1 { font-size: 1.75rem; margin-bottom: 2rem; }
        .table-container { background-color: #1E293B; border-radius: 12px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #334155; }
        th { background-color: #334155; }
    </style>
@endpush
@section('content')
            <header class="main-header"><h1>User Activity Log</h1></header>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $log->user->username ?? 'N/A' }}</td>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->created_at->format('d M, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align: center; padding: 2rem;">No user activities logged yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-links">{{ $logs->links() }}</div>
        </main>
    </div>
    @endsection
