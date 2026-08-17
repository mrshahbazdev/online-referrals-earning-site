@extends('layouts.frontend')

@section('title', 'Support Chat')

@push('styles')
<style>
    .chat-container {
        display: flex;
        flex-direction: column;
        height: 520px;
        background: #1E1F2B;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #334155;
    }
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .message {
        max-width: 80%;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-size: 0.9rem;
        line-height: 1.4;
        word-break: break-word;
    }
    .message.user {
        align-self: flex-end;
        background: #facc15;
        color: #111827;
        border-bottom-right-radius: 4px;
    }
    .message.admin {
        align-self: flex-start;
        background: #334155;
        color: #f1f5f9;
        border-bottom-left-radius: 4px;
    }
    .message time {
        display: block;
        font-size: 0.65rem;
        margin-top: 0.35rem;
        opacity: 0.7;
    }
    .chat-form {
        padding: 0.75rem;
        background: #14151c;
        border-top: 1px solid #334155;
        display: flex;
        gap: 0.5rem;
    }
    .chat-form input {
        flex: 1;
        background: #334155;
        border: 1px solid #475569;
        color: #f1f5f9;
        padding: 0.65rem 1rem;
        border-radius: 999px;
        outline: none;
    }
    .chat-form button {
        background: #facc15;
        color: #111827;
        border: none;
        border-radius: 999px;
        padding: 0 1.2rem;
        font-weight: 600;
        cursor: pointer;
    }
    .empty-chat {
        text-align: center;
        color: #94a3b8;
        margin: auto;
    }
    .success-toast {
        background: #166534;
        color: #dcfce7;
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
    <div class="space-y-4">
        <h2 class="text-xl font-bold">Support Chat</h2>

        @if (session('success'))
            <div class="success-toast">{{ session('success') }}</div>
        @endif

        <div class="chat-container" id="chatContainer">
            <div class="chat-messages" id="chatMessages">
                @forelse ($messages as $msg)
                    <div class="message {{ $msg->sender }}">
                        {{ $msg->message }}
                        <time>{{ $msg->created_at->format('h:i A') }}</time>
                    </div>
                @empty
                    <div class="empty-chat">No messages yet. Start a conversation with admin below.</div>
                @endforelse
            </div>

            <form class="chat-form" method="POST" action="{{ route('support.store') }}">
                @csrf
                <input type="text" name="message" placeholder="Type your message..." required maxlength="2000" autofocus>
                <button type="submit"><i class="ph ph-paper-plane-right text-xl"></i></button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function() {
        const container = document.getElementById('chatMessages');
        if (container) container.scrollTop = container.scrollHeight;
    })();
</script>
@endpush
