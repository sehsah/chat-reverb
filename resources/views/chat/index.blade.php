@extends('layout.master')

@section('content')

<div class="row h-100">
    <!-- Users Sidebar -->
    <div class="col-md-3 chat-sidebar">
        <div class="sidebar-content">
            <h5 class="mb-3">Online Users</h5>
            <div class="user-list">
                @foreach($users as $user)
                <div class="d-flex align-items-center mb-3">
                    <div class="user-status status-offline"></div>
                    <div>{{ $user->name }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="col-md-9 chat-container">
        <div class="chat-messages" id="chat-messages">
            @foreach($messages as $message)
            <div class="message {{ $message->user_id === Auth::id() ? 'message-sent' : 'message-received' }}">
                <div class="message-content">
                    <div class="message-header">
                        <span class="user-name">{{ $message->user->name }}</span>
                    </div>
                    <div class="message-text">{{ $message->message }}</div>
                    <div class="message-meta">
                        <span class="message-time">{{ $message->created_at->format('h:i A') }}</span>
                        <span class="message-date">{{ $message->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Chat Input -->
        <div class="chat-input">
            <form id="message-form" class="d-flex" method="POST">
                @csrf
                <input type="text" 
                       id="message-input" 
                       class="form-control me-2" 
                       placeholder="Type your message..."
                       required>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

<style>
.typing-indicator {
    padding-bottom: 20px;
}
.chat-sidebar {
    /* position: fixed; */
    left: 0;
    top: 0;
    bottom: 0;
    width: 25%;
    background-color: white;
    border-right: 1px solid #dee2e6;
    z-index: 1000;
}

.sidebar-content {
    height: 100%;
    overflow-y: auto;
    padding: 20px;
}

.chat-container {
    position: relative;
    height: 100vh;
    display: flex;
    flex-direction: column;
    /* margin-left: 25%; */
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    padding-bottom: 80px;
    background-color: #f8f9fa;
}

.message {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
}

.message-sent {
    align-items: flex-end;
}

.message-received {
    align-items: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.message-sent .message-content {
    background-color: #b6b6b6;
    color: #111b21;
}

.message-received .message-content {
    background-color: white;
    color: #111b21;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.user-name {
    font-weight: 600;
    font-size: 0.9rem;
}

.message-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 4px;
    font-size: 0.7rem;
}

.message-sent .message-meta {
    color: rgba(255, 255, 255, 0.7);
    justify-content: flex-end;
}

.message-received .message-meta {
    color: #667781;
}

.message-time {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.message-date {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.message-text {
    word-wrap: break-word;
    line-height: 1.4;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.chat-input {
    position: fixed;
    bottom: 0;
    right: 0;
    left: 25%;
    padding: 20px;
    background-color: white;
    border-top: 1px solid #dee2e6;
    z-index: 1000;
}

.chat-input .form-control {
    border-radius: 20px;
    padding: 10px 20px;
}

.chat-input .btn {
    border-radius: 20px;
    padding: 10px 25px;
}

.user-status {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 10px;
}

.status-online {
    background-color: #28a745;
}

.status-offline {
    background-color: #dc3545;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const messagesContainer = document.getElementById('chat-messages');

    let typingTimer;
    const TYPING_TIMEOUT = 3000; // 3 ثواني بدون كتابة = اختفاء "يكتب"
    messageInput.addEventListener('input', function () {
        window.Echo.join('general')
            .whisper('typing', {
                user: {
                    id: {{ Auth::id() }},
                    name: '{{ Auth::user()->name }}'
                }
            });

        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            window.Echo.join('general')
                .whisper('stopTyping', {
                    user: {
                        id: {{ Auth::id() }},
                        name: '{{ Auth::user()->name }}'
                    }
                });
        }, TYPING_TIMEOUT);
    });

    // Handle message submission
    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        try {
            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message: message,
                    conversation_id: '1'
                })
            });

            const data = await response.json();
            messageInput.value = '';
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Listen for broadcast events
    window.Echo.channel('general')
        .listen('MessageSent', (e) => {
            appendMessage(e.message);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });

    function appendMessage(message) {
        const currentUserId = {{ Auth::id() }};
        const isCurrentUser = message.user.id === currentUserId;
        const now = new Date();
        const messageHtml = `
            <div class="message ${isCurrentUser ? 'message-sent' : 'message-received'}">
                <div class="message-content">
                    <div class="message-header">
                        <span class="user-name">${message.user.name}</span>
                    </div>
                    <div class="message-text">${message.message}</div>
                    <div class="message-meta">
                        <span class="message-time">${now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</span>
                        <span class="message-date">${now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                    </div>
                </div>
            </div>
        `;
        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
    }
});
</script>
@endpush
@endsection
