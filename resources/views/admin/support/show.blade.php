@extends('admin.layouts.app')

@section('title', 'Chat: ' . $user->username)

@push('styles')
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --bg-dark: #111827; --sidebar-bg: #1E293B; --card-bg: #1E293B;
            --text-primary: #f1f5f9; --text-secondary: #94a3b8; --accent-color: #facc15;
            --border-color: #334155; --green: #22c55e; --red: #ef4444; --blue: #3b82f6;
        }
        .main-content { flex-grow: 1; padding: 2rem; overflow-y: auto; }
        .chat-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
        .chat-header h1 { font-size: 1.5rem; }
        .back-link { color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.25rem; }
        .chat-box {
            background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px;
            height: 500px; display: flex; flex-direction: column; overflow: hidden;
        }
        .chat-messages {
            flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;
        }
        .message {
            max-width: 70%; padding: 0.75rem 1rem; border-radius: 12px; font-size: 0.9rem; line-height: 1.4; word-break: break-word;
        }
        .message.user { align-self: flex-start; background: #334155; color: var(--text-primary); border-bottom-left-radius: 4px; }
        .message.admin { align-self: flex-end; background: var(--accent-color); color: #111827; border-bottom-right-radius: 4px; }
        .message time { display: block; font-size: 0.65rem; margin-top: 0.35rem; opacity: 0.7; }
        .reply-form {
            padding: 0.75rem; border-top: 1px solid var(--border-color); background: #14151c;
            display: flex; gap: 0.5rem;
        }
        .reply-form input {
            flex: 1; background: #334155; border: 1px solid #475569; color: var(--text-primary); padding: 0.65rem 1rem; border-radius: 8px; outline: none;
        }
        .reply-form button {
            background: var(--accent-color); color: #111827; border: none; border-radius: 8px; padding: 0 1.2rem; font-weight: 600; cursor: pointer;
        }
        .empty-chat { text-align: center; color: var(--text-secondary); margin: auto; }
    </style>
@endpush

@section('content')
    <main class="main-content">
        <div class="chat-header">
            <a href="{{ route('admin.support.index') }}" class="back-link"><i class="ph ph-arrow-left"></i> Back</a>
            <h1>{{ $user->username }}</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="chat-box">
            <div class="chat-messages" id="chatMessages">
                @forelse ($messages as $msg)
                    <div class="message {{ $msg->sender }}">
                        {{ $msg->message }}
                        <time>{{ $msg->created_at->format('M d, h:i A') }}</time>
                    </div>
                @empty
                    <div class="empty-chat">No messages yet.</div>
                @endforelse
            </div>

            <form class="reply-form" method="POST" action="{{ route('admin.support.reply', $user) }}">
                @csrf
                <input type="text" name="message" placeholder="Type admin reply..." required maxlength="2000" autofocus>
                <button type="submit"><i class="ph ph-paper-plane-right text-xl"></i> Reply</button>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        (function() {
            const container = document.getElementById('chatMessages');
            if (container) container.scrollTop = container.scrollHeight;

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
        })();
    </script>
@endpush
