@extends('admin.layouts.app')

@section('title', 'Chat: ' . $user->username)

@push('styles')
<style>
    :root {
        --bg-dark: #111827; --sidebar-bg: #1E293B; --card-bg: #1E293B;
        --text-primary: #f1f5f9; --text-secondary: #94a3b8; --accent-color: #facc15;
        --border-color: #334155; --green: #22c55e; --red: #ef4444; --blue: #3b82f6;
    }
    .main-content { flex-grow: 1; padding: 2rem; overflow-y: auto; }
    .chat-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
    .back-link { color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.25rem; }
    .chat-user-card {
        display: flex; align-items: center; gap: 0.75rem;
        background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px;
        padding: 0.75rem 1rem; flex: 1; min-width: 200px;
    }
    .chat-user-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: #334155; display: flex; align-items: center; justify-content: center;
        color: var(--accent-color); font-size: 1.25rem;
    }
    .chat-user-meta { display: flex; flex-direction: column; }
    .chat-user-name { font-weight: 700; }
    .chat-user-status { font-size: 0.75rem; color: var(--green); display: flex; align-items: center; gap: 0.3rem; }
    .chat-box {
        background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px;
        height: 520px; display: flex; flex-direction: column; overflow: hidden;
    }
    .chat-messages {
        flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column; gap: 0.4rem;
    }
    .message { display: flex; width: 100%; }
    .message.user { justify-content: flex-start; }
    .message.admin { justify-content: flex-end; }
    .message-bubble {
        max-width: 75%; padding: 0.65rem 0.85rem; border-radius: 14px; font-size: 0.9rem; line-height: 1.4; word-break: break-word;
    }
    .message.user .message-bubble { background: #334155; color: var(--text-primary); border-bottom-left-radius: 4px; }
    .message.admin .message-bubble { background: var(--accent-color); color: #111827; border-bottom-right-radius: 4px; }
    .message-sender { font-size: 0.65rem; font-weight: 600; opacity: 0.75; margin-bottom: 0.15rem; }
    .message-meta { display: flex; align-items: center; gap: 0.35rem; margin-top: 0.25rem; font-size: 0.65rem; opacity: 0.7; justify-content: flex-start; }
    .message.admin .message-meta { justify-content: flex-end; }
    .status-icon { font-size: 0.75rem; }
    .status-icon.read { color: #111827; opacity: 0.9; }
    .date-separator {
        text-align: center; color: var(--text-secondary); font-size: 0.7rem; margin: 0.5rem 0;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .date-separator::before, .date-separator::after { content: ''; flex: 1; height: 1px; background: var(--border-color); }
    .reply-form {
        padding: 0.75rem; border-top: 1px solid var(--border-color); background: #14151c;
        display: flex; gap: 0.5rem;
    }
    .reply-form input {
        flex: 1; background: #334155; border: 1px solid #475569; color: var(--text-primary);
        padding: 0.65rem 1rem; border-radius: 999px; outline: none;
    }
    .reply-form input:focus { border-color: var(--accent-color); }
    .reply-form button {
        background: var(--accent-color); color: #111827; border: none; border-radius: 999px;
        padding: 0 1.2rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.35rem;
    }
    .reply-form button:disabled { opacity: 0.6; cursor: not-allowed; }
    .empty-chat { text-align: center; color: var(--text-secondary); margin: auto; }
</style>
@endpush

@section('content')
    <main class="main-content">
        <div class="chat-header">
            <a href="{{ route('admin.support.index') }}" class="back-link"><i class="ph ph-arrow-left"></i> Back</a>
            <div class="chat-user-card">
                <div class="chat-user-avatar"><i class="ph ph-user"></i></div>
                <div class="chat-user-meta">
                    <span class="chat-user-name">{{ $user->username }}</span>
                    <span class="chat-user-status"><span class="online-dot" style="width:6px;height:6px;background:var(--green);border-radius:50%;"></span> Active</span>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="chat-box">
            <div class="chat-messages" id="chatMessages"
                 data-user-id="{{ $user->id }}"
                 data-last-id="{{ $messages->max('id') ?? 0 }}"
                 data-last-date="{{ $messages->isNotEmpty() ? ($messages->last()->created_at->isToday() ? 'Today' : ($messages->last()->created_at->isYesterday() ? 'Yesterday' : $messages->last()->created_at->format('M d, Y'))) : '' }}">
                @php
                    $lastDate = null;
                @endphp
                @forelse ($messages as $msg)
                    @php
                        $date = $msg->created_at->isToday()
                            ? 'Today'
                            : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('M d, Y'));
                    @endphp
                    @if ($date !== $lastDate)
                        <div class="date-separator">{{ $date }}</div>
                        @php $lastDate = $date; @endphp
                    @endif
                    <div class="message {{ $msg->sender }}" data-id="{{ $msg->id }}">
                        <div class="message-bubble">
                            <div class="message-sender">{{ $msg->sender === 'admin' ? 'You' : $user->username }}</div>
                            <div class="message-text">{{ $msg->message }}</div>
                            <div class="message-meta">
                                <time>{{ $msg->created_at->format('h:i A') }}</time>
                                @if ($msg->sender === 'admin')
                                    <i class="ph {{ $msg->is_read ? 'ph-checks read' : 'ph-check' }} status-icon"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-chat" id="emptyState">No messages yet.</div>
                @endforelse
            </div>

            <form class="reply-form" id="replyForm" method="POST" action="{{ route('admin.support.reply', $user) }}">
                @csrf
                <input type="text" id="replyInput" name="message" placeholder="Type admin reply..." required maxlength="2000" autocomplete="off">
                <button type="submit" id="replyButton"><i class="ph ph-paper-plane-right text-xl"></i> Reply</button>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const messagesEl = document.getElementById('chatMessages');
    const form = document.getElementById('replyForm');
    const input = document.getElementById('replyInput');
    const replyBtn = document.getElementById('replyButton');
    const emptyState = document.getElementById('emptyState');
    const userId = messagesEl.dataset.userId;
    let lastId = parseInt(messagesEl.dataset.lastId || '0', 10);
    let lastDate = messagesEl.dataset.lastDate || '';
    let pollTimer = null;
    const username = @json($user->username);

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function createDateSeparator(date) {
        const div = document.createElement('div');
        div.className = 'date-separator';
        div.textContent = date;
        return div;
    }

    function createMessageEl(msg) {
        const isAdmin = msg.sender === 'admin';
        const wrapper = document.createElement('div');
        wrapper.className = 'message ' + (isAdmin ? 'admin' : 'user');
        wrapper.dataset.id = msg.id;

        const statusIcon = isAdmin
            ? `<i class="ph ${msg.is_read ? 'ph-checks read' : 'ph-check'} status-icon"></i>`
            : '';

        wrapper.innerHTML = `
            <div class="message-bubble">
                <div class="message-sender">${isAdmin ? 'You' : username}</div>
                <div class="message-text">${escapeHtml(msg.message)}</div>
                <div class="message-meta">
                    <time>${msg.time}</time>
                    ${statusIcon}
                </div>
            </div>
        `;
        return wrapper;
    }

    function appendMessage(msg) {
        if (emptyState) emptyState.style.display = 'none';

        if (msg.date && msg.date !== lastDate) {
            messagesEl.appendChild(createDateSeparator(msg.date));
            lastDate = msg.date;
        }

        messagesEl.appendChild(createMessageEl(msg));
        if (msg.id > lastId) lastId = msg.id;
        messagesEl.dataset.lastId = lastId;
        scrollToBottom();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async function sendReply(e) {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        replyBtn.disabled = true;
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();
            if (response.ok && result.success) {
                input.value = '';
                appendMessage(result.data);
            } else {
                alert('Reply failed. Please try again.');
            }
        } catch (err) {
            console.error(err);
            alert('Reply failed. Please try again.');
        } finally {
            replyBtn.disabled = false;
            input.focus();
        }
    }

    async function pollMessages() {
        if (document.hidden) return;
        try {
            const response = await fetch(`/admin/support/${userId}/messages?last_id=${lastId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await response.json();
            if (response.ok && result.data) {
                result.data.forEach(appendMessage);
            }
        } catch (err) {
            console.error('Poll error', err);
        }
    }

    form.addEventListener('submit', sendReply);
    scrollToBottom();

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            clearInterval(pollTimer);
        } else {
            pollMessages();
            pollTimer = setInterval(pollMessages, 3000);
        }
    });

    pollMessages();
    pollTimer = setInterval(pollMessages, 3000);
})();
</script>
@endpush
