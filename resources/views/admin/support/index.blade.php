@extends('admin.layouts.app')

@section('title', 'Support Chats')

@push('styles')
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --bg-dark: #111827; --sidebar-bg: #1E293B; --card-bg: #1E293B;
            --text-primary: #f1f5f9; --text-secondary: #94a3b8; --accent-color: #facc15;
            --border-color: #334155; --green: #22c55e; --red: #ef4444; --blue: #3b82f6;
        }
        .main-content { flex-grow: 1; padding: 2rem; overflow-y: auto; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .main-header h1 { font-size: 1.75rem; }
        .chat-list { display: flex; flex-direction: column; gap: 0.75rem; }
        .chat-card {
            background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px;
            padding: 1rem; display: flex; justify-content: space-between; align-items: center;
            text-decoration: none; color: var(--text-primary); transition: background 0.2s;
        }
        .chat-card:hover { background: #334155; }
        .chat-info { display: flex; flex-direction: column; gap: 0.25rem; }
        .chat-user { font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        .chat-preview { color: var(--text-secondary); font-size: 0.85rem; }
        .chat-meta { text-align: right; font-size: 0.8rem; color: var(--text-secondary); }
        .badge {
            background: var(--red); color: #fff; border-radius: 999px; padding: 0.15rem 0.5rem;
            font-size: 0.7rem; font-weight: 600; margin-left: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <main class="main-content">
        <header class="main-header">
            <h1>Support Chats</h1>
        </header>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="chat-list">
            @forelse ($users as $user)
                @php
                    $lastMessage = $user->supportMessages->first();
                @endphp
                <a href="{{ route('admin.support.show', $user) }}" class="chat-card">
                    <div class="chat-info">
                        <span class="chat-user">
                            <i class="ph ph-user-circle text-xl" style="color: var(--accent-color);"></i>
                            {{ $user->username }}
                            @if ($user->unread_count > 0)
                                <span class="badge">{{ $user->unread_count }}</span>
                            @endif
                        </span>
                        <span class="chat-preview">
                            {{ $lastMessage ? Str::limit($lastMessage->message, 60) : 'No messages yet' }}
                        </span>
                    </div>
                    <div class="chat-meta">
                        <div>{{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}</div>
                        <i class="ph ph-caret-right"></i>
                    </div>
                </a>
            @empty
                <p style="color: var(--text-secondary); text-align: center;">No support chats found.</p>
            @endforelse
        </div>

        <div class="pagination-links">{{ $users->links() }}</div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');

            if (mobileNavToggle) {
                mobileNavToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                    mobileOverlay.classList.toggle('active');
                });
            }
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    mobileOverlay.classList.remove('active');
                });
            }
        });
    </script>
@endpush
