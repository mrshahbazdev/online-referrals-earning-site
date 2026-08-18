@extends('layouts.frontend')

@section('title', 'Support Chat')

@push('styles')
<style>
    .chat-wrapper {
        display: flex;
        flex-direction: column;
        height: 540px;
        background: #1E1F2B;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #334155;
    }
    .chat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        background: #14151c;
        border-bottom: 1px solid #334155;
    }
    .chat-header-info { display: flex; align-items: center; gap: 0.6rem; }
    .chat-header-title { font-weight: 700; font-size: 0.95rem; }
    .chat-header-actions { display: flex; align-items: center; gap: 0.5rem; }
    .chat-header-actions a {
        color: #facc15; text-decoration: none; display: flex; align-items: center; justify-content: center;
        width: 34px; height: 34px; border-radius: 50%; background: #1E1F2B; border: 1px solid #334155;
    }
    .chat-header-actions a:hover { background: #334155; }
    .online-dot {
        width: 8px; height: 8px; background: #22c55e; border-radius: 50%;
        box-shadow: 0 0 0 2px #1E1F2B;
    }
    .chat-status { font-size: 0.7rem; color: #94a3b8; }
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }
    .message { display: flex; width: 100%; }
    .message.user { justify-content: flex-end; }
    .message.admin { justify-content: flex-start; }
    .message-bubble {
        max-width: 80%;
        padding: 0.65rem 0.85rem;
        border-radius: 14px;
        font-size: 0.9rem;
        line-height: 1.4;
        word-break: break-word;
    }
    .message.user .message-bubble {
        background: #facc15;
        color: #111827;
        border-bottom-right-radius: 4px;
    }
    .message.admin .message-bubble {
        background: #334155;
        color: #f1f5f9;
        border-bottom-left-radius: 4px;
    }
    .message-sender { font-size: 0.65rem; font-weight: 600; opacity: 0.75; margin-bottom: 0.15rem; }
    .message-meta { display: flex; align-items: center; gap: 0.35rem; margin-top: 0.25rem; font-size: 0.65rem; opacity: 0.7; justify-content: flex-end; }
    .message.admin .message-meta { justify-content: flex-start; }
    .status-icon { font-size: 0.75rem; }
    .status-icon.read { color: #22c55e; }
    .date-separator {
        text-align: center;
        color: #94a3b8;
        font-size: 0.7rem;
        margin: 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .date-separator::before, .date-separator::after {
        content: ''; flex: 1; height: 1px; background: #334155;
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
        font-size: 0.9rem;
    }
    .chat-form input:focus { border-color: #facc15; }
    .chat-form button {
        background: #facc15;
        color: #111827;
        border: none;
        border-radius: 999px;
        width: 42px; height: 42px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 600;
        cursor: pointer;
        flex-shrink: 0;
    }
    .chat-form button:disabled { opacity: 0.6; cursor: not-allowed; }
    .file-label {
        display: flex; align-items: center; justify-content: center;
        width: 42px; height: 42px; background: #334155; border-radius: 50%;
        color: #f1f5f9; cursor: pointer; flex-shrink: 0;
    }
    .file-label:hover { background: #475569; }
    .chat-image {
        max-width: 100%; border-radius: 8px; margin-top: 5px; margin-bottom: 5px; display: block;
    }
    .empty-chat {
        text-align: center;
        color: #94a3b8;
        margin: auto;
        font-size: 0.85rem;
    }
    .typing {
        display: none;
        align-items: center;
        gap: 0.25rem;
        color: #94a3b8;
        font-size: 0.75rem;
        padding: 0 1rem 0.5rem;
    }
    .typing.dots span {
        display: inline-block;
        width: 5px; height: 5px; background: #94a3b8; border-radius: 50%;
        animation: bounce 1.2s infinite ease-in-out;
    }
    .typing.dots span:nth-child(2) { animation-delay: 0.15s; }
    .typing.dots span:nth-child(3) { animation-delay: 0.3s; }
    @keyframes bounce { 0%, 80%, 100% { transform: translateY(0); } 40% { transform: translateY(-4px); } }
</style>
@endpush

@section('content')
    <div class="space-y-3">
        <div class="chat-wrapper" id="chatContainer">
            <div class="chat-header">
                <div class="chat-header-info">
                    <div class="online-dot"></div>
                    <div>
                        <div class="chat-header-title">Support Chat</div>
                        <div class="chat-status">Admin is online</div>
                    </div>
                </div>
                <div class="chat-header-actions">
                    @if(!empty($settings['support_telegram']))
                        @php
                            $telegram = $settings['support_telegram'];
                            $telegramLink = str_starts_with($telegram, 'http') ? $telegram : 'https://t.me/' . ltrim($telegram, '@');
                        @endphp
                        <a href="{{ $telegramLink }}" target="_blank" title="Telegram"><i class="ph ph-telegram-logo text-xl"></i></a>
                    @endif
                    @if(!empty($settings['support_imo']))
                        @php
                            $imo = $settings['support_imo'];
                            $imoLink = str_starts_with($imo, 'http') ? $imo : 'https://imo.im/' . ltrim($imo, '+');
                        @endphp
                        <a href="{{ $imoLink }}" target="_blank" title="IMO"><i class="ph ph-chat-circle text-xl"></i></a>
                    @endif
                    @if(empty($settings['support_telegram']) && empty($settings['support_imo']))
                        <i class="ph ph-headset text-xl text-yellow-400"></i>
                    @endif
                </div>
            </div>

            <div class="chat-messages" id="chatMessages"
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
                            <div class="message-sender">{{ $msg->sender === 'user' ? 'You' : 'Admin' }}</div>
                            @if($msg->image_path)
                                <img src="{{ asset('uploads/' . $msg->image_path) }}" class="chat-image" alt="Attachment">
                            @endif
                            @if($msg->message)
                                <div class="message-text">{{ $msg->message }}</div>
                            @endif
                            <div class="message-meta">
                                <time>{{ $msg->created_at->format('h:i A') }}</time>
                                @if ($msg->sender === 'user')
                                    <i class="ph {{ $msg->is_read ? 'ph-checks read' : 'ph-check' }} status-icon"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-chat" id="emptyState">No messages yet.<br>Start a conversation with admin.</div>
                @endforelse
            </div>

            <div class="typing dots" id="typingIndicator"><span></span><span></span><span></span></div>

            <form class="chat-form" id="chatForm" method="POST" action="{{ route('support.store') }}" enctype="multipart/form-data">
                @csrf
                <label for="imageInput" class="file-label" title="Attach Image">
                    <i class="ph ph-paperclip text-xl"></i>
                    <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;">
                </label>
                <input type="text" id="messageInput" name="message" placeholder="Type your message..." maxlength="2000" autocomplete="off">
                <button type="submit" id="sendButton"><i class="ph ph-paper-plane-right text-xl"></i></button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const messagesEl = document.getElementById('chatMessages');
    const form = document.getElementById('chatForm');
    const input = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendButton');
    const emptyState = document.getElementById('emptyState');
    let lastId = parseInt(messagesEl.dataset.lastId || '0', 10);
    let lastDate = messagesEl.dataset.lastDate || '';
    let pollTimer = null;

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
        const isUser = msg.sender === 'user';
        const wrapper = document.createElement('div');
        wrapper.className = 'message ' + (isUser ? 'user' : 'admin');
        wrapper.dataset.id = msg.id;

        const statusIcon = isUser
            ? `<i class="ph ${msg.is_read ? 'ph-checks read' : 'ph-check'} status-icon"></i>`
            : '';

        const imageHtml = msg.image_url ? `<img src="${msg.image_url}" class="chat-image" alt="Attachment">` : '';
        const textHtml = msg.message ? `<div class="message-text">${escapeHtml(msg.message)}</div>` : '';

        wrapper.innerHTML = `
            <div class="message-bubble">
                <div class="message-sender">${isUser ? 'You' : 'Admin'}</div>
                ${imageHtml}
                ${textHtml}
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

    async function sendMessage(e) {
        e.preventDefault();
        const imageInput = document.getElementById('imageInput');
        const text = input.value.trim();
        if (!text && imageInput.files.length === 0) return;

        sendBtn.disabled = true;
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
                imageInput.value = '';
                appendMessage(result.data);
            } else {
                alert('Message send failed. Please try again.');
            }
        } catch (err) {
            console.error(err);
            alert('Message send failed. Please try again.');
        } finally {
            sendBtn.disabled = false;
            input.focus();
        }
    }

    async function pollMessages() {
        if (document.hidden) return;
        try {
            const response = await fetch(`/support/messages?last_id=${lastId}`, {
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

    form.addEventListener('submit', sendMessage);
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

