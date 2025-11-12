@extends('admin.layouts.app')

@section('title', 'Log Management')

@push('styles')
    <style>
        :root { --bg-dark: #111827; --sidebar-bg: #1E293B; --card-bg: #1E293B; --text-primary: #f1f5f9; --text-secondary: #94a3b8; --accent-color: #facc15; --border-color: #334155; }
        body { font-family: sans-serif; background-color: var(--bg-dark); color: var(--text-primary); }
        .dashboard-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background-color: var(--sidebar-bg); padding: 1.5rem; /* Add full sidebar styles */ }
        .main-content { flex-grow: 1; padding: 2rem; }
        .main-header h1 { font-size: 1.75rem; margin-bottom: 1rem; }
        .log-tabs { display: flex; gap: 0.5rem; border-bottom: 1px solid var(--border-color); margin-bottom: 2rem; }
        .log-tabs a { padding: 0.75rem 1.5rem; text-decoration: none; color: var(--text-secondary); border-bottom: 2px solid transparent; }
        .log-tabs a.active { color: var(--accent-color); border-bottom-color: var(--accent-color); font-weight: 600; }
        .table-container { background-color: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { background-color: #334155; }
        .pagination-links { margin-top: 1.5rem; }
        /* New Pagination Styles */
        .pagination-links nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pagination-links a, .pagination-links span {
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .pagination-links a {
            background-color: var(--accent-color);
            color: var(--bg-dark);
            transition: background-color 0.2s;
        }
        .pagination-links a:hover {
            background-color: #fde047; /* Lighter yellow */
        }
        /* This styles the disabled "Previous" or "Next" button */
        .pagination-links span {
            background-color: var(--border-color);
            color: var(--text-secondary);
            cursor: not-allowed;
        }
    </style>
@endpush
@section('content')
            <header class="main-header"><h1>Admin Activity Log</h1></header>

            <div class="log-tabs">
                @foreach($logTypes as $type)
                    <a href="{{ route('admin.activity_logs.index', ['type' => $type]) }}" class="{{ $selectedType == $type ? 'active' : '' }}">
                        {{ $type }}
                    </a>
                @endforeach
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $log->admin->username ?? 'N/A' }}</td>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->created_at->format('d M, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align: center; padding: 2rem;">No activities logged for this category.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-links">{{ $logs->links() }}</div>
        </main>
    </div>
    @endsection
